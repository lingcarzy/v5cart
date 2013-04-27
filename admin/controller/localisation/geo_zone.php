<?php
class ControllerLocalisationGeoZone extends Controller {

	public function index() {
		$this->language->load('localisation/geo_zone');

		M('localisation/geo_zone');
		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/geo_zone');

		M('localisation/geo_zone');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_geo_zone->addGeoZone($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/geo_zone'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/geo_zone');

		M('localisation/geo_zone');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_geo_zone->editGeoZone($this->request->get['geo_zone_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/geo_zone'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/geo_zone');

		M('localisation/geo_zone');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $geo_zone_id) {
				$this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/geo_zone'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$sort = $qf->get('sort', 'name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['geo_zones'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZones();

		$results = $this->model_localisation_geo_zone->getGeoZones($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/geo_zone/update', 'geo_zone_id=' . $result['geo_zone_id'])
			);

			$this->data['geo_zones'][] = array(
				'geo_zone_id' => $result['geo_zone_id'],
				'name'        => $result['name'],
				'description' => $result['description'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['geo_zone_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/geo_zone', 'sort=name&order=' . $url, 'SSL');
		$this->data['sort_description'] = UA('localisation/geo_zone&order=', 'sort=description' . $url);

		$pagination = new Pagination();
		$pagination->total = $geo_zone_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/geo_zone', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/geo_zone_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['geo_zone_id'])) {
			$this->data['action'] = UA('localisation/geo_zone/update', 'geo_zone_id=' . $this->request->get['geo_zone_id']);
		} else {
			$this->data['action'] = UA('localisation/geo_zone/insert');
		}

		$this->data['cancel'] = UA('localisation/geo_zone');

		if (isset($this->request->get['geo_zone_id']) && !$this->request->isPost()) {
			$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($this->request->get['geo_zone_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($geo_zone_info)) {
			$this->data['name'] = $geo_zone_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (!empty($geo_zone_info)) {
			$this->data['description'] = $geo_zone_info['description'];
		} else {
			$this->data['description'] = '';
		}

		$this->data['countries'] = cache_read('country.php');

		if (isset($this->request->post['zone_to_geo_zone'])) {
			$this->data['zone_to_geo_zones'] = $this->request->post['zone_to_geo_zone'];
		} elseif (isset($this->request->get['geo_zone_id'])) {
			$this->data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZoneToGeoZones($this->request->get['geo_zone_id']);
		} else {
			$this->data['zone_to_geo_zones'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/geo_zone_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;
		if (!range_length($this->request->post['name'], 3, 32)) {
			$this->setMessage('error_name', L('error_name'));
			$pass = false;
		}

		if (!range_length($this->request->post['description'], 3, 255)) {
			$this->setMessage('error_description', L('error_description'));
			$pass = false;
		}

		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('localisation/tax_rate');

		foreach ($this->request->post['selected'] as $geo_zone_id) {
			$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRatesByGeoZoneId($geo_zone_id);

			if ($tax_rate_total) {
				$this->setMessage('error_warning', sprintf(L('error_tax_rate'), $tax_rate_total));
				return false;
			}
		}
		return true;
	}
}
?>