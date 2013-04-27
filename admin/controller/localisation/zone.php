<?php
class ControllerLocalisationZone extends Controller {

	public function index() {
		$this->language->load('localisation/zone');
		M('localisation/zone');

		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/zone');

		M('localisation/zone');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_zone->addZone($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/zone'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/zone');

		M('localisation/zone');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_zone->editZone($this->request->get['zone_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/zone'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/zone');
		M('localisation/zone');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $zone_id) {
				$this->model_localisation_zone->deleteZone($zone_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/zone'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$sort = $qf->get('sort', 'c.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['zones'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$zone_total = $this->model_localisation_zone->getTotalZones();
		$results = $this->model_localisation_zone->getZones($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/zone/update', 'zone_id=' . $result['zone_id'])
			);

			$this->data['zones'][] = array(
				'zone_id'  => $result['zone_id'],
				'country'  => $result['country'],
				'name'     => $result['name'] . (($result['zone_id'] == C('config_zone_id')) ? L('text_default') : null),
				'code'     => $result['code'],
				'selected' => isset($this->request->post['selected']) && in_array($result['zone_id'], $this->request->post['selected']),
				'action'   => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_country'] = UA('localisation/zone', 'sort=c.name&order=' . $url);
		$this->data['sort_name'] = UA('localisation/zone', 'sort=z.name&order=' . $url);
		$this->data['sort_code'] = UA('localisation/zone', 'sort=z.code&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $zone_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/zone', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/zone_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		$zone_id = G('zone_id', 0);
		if ($zone_id) {
			$this->data['action'] = UA('localisation/zone/update', 'zone_id=' . $zone_id);
		} else {
			$this->data['action'] = UA('localisation/zone/insert');
		}

		if ($zone_id && !$this->request->isPost()) {
			$zone_info = $this->model_localisation_zone->getZone($zone_id);
		}

		if (!empty($zone_info)) {
			$this->data['status'] = $zone_info['status'];
			$this->data['name'] = $zone_info['name'];
			$this->data['code'] = $zone_info['code'];
			$this->data['country_id'] = $zone_info['country_id'];
		} else {
			$this->data['status'] = P('status', 1);
			$this->data['name'] = P('name');
			$this->data['code'] = P('code');
			$this->data['country_id'] = P('country_id', 0);
		}

		$this->data['countries'] = cache_read('country.php');

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/zone_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!range_length($this->request->post['name'], 3, 64)) {
			$this->setMessage('error_name', L('error_name'));
			return false;
		}

		return true;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		M('sale/customer');
		M('sale/affiliate');
		M('localisation/geo_zone');

		foreach ($this->request->post['selected'] as $zone_id) {
			if (C('config_zone_id') == $zone_id) {
				$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$store_total = $this->model_setting_store->getTotalStoresByZoneId($zone_id);

			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}

			$address_total = $this->model_sale_customer->getTotalAddressesByZoneId($zone_id);

			if ($address_total) {
				$this->setMessage('error_warning', sprintf(L('error_address'), $address_total));
				return false;
			}

			$affiliate_total = $this->model_sale_affiliate->getTotalAffiliatesByZoneId($zone_id);

			if ($affiliate_total) {
				$this->setMessage('error_warning', sprintf(L('error_affiliate'), $affiliate_total));
				return false;
			}

			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);

			if ($zone_to_geo_zone_total) {
				$this->setMessage('error_warning', sprintf(L('error_zone_to_geo_zone'), $zone_to_geo_zone_total));
				return false;
			}
		}
		return true;
	}
}
?>