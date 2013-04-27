<?php
class ControllerLocalisationCountry extends Controller {

	public function index() {
		$this->language->load('localisation/country');

		M('localisation/country');

		$this->getList();
	}

	public function insert() {

		$this->language->load('localisation/country');

		M('localisation/country');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_country->addCountry($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/country'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/country');

		M('localisation/country');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_country->editCountry($this->request->get['country_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/country'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/country');

		M('localisation/country');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $country_id) {
				$this->model_localisation_country->deleteCountry($country_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/country'));
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

		$this->data['countries'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$country_total = $this->model_localisation_country->getTotalCountries();

		$results = $this->model_localisation_country->getCountries($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/country/update', 'country_id=' . $result['country_id'])
			);

			$this->data['countries'][] = array(
				'country_id' => $result['country_id'],
				'name'       => $result['name'] . (($result['country_id'] == C('config_country_id')) ? L('text_default') : null),
				'iso_code_2' => $result['iso_code_2'],
				'iso_code_3' => $result['iso_code_3'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['country_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/country', 'sort=name&order=' . $url);
		$this->data['sort_iso_code_2'] = UA('localisation/country', 'sort=iso_code_2&order=' . $url);
		$this->data['sort_iso_code_3'] = UA('localisation/country', 'sort=iso_code_3&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $country_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/country', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/country_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['country_id'])) {
			$this->data['action'] = UA('localisation/country/update', 'country_id=' . $this->request->get['country_id']);
		} else {
			$this->data['action'] = UA('localisation/country/insert');
		}

		if (isset($this->request->get['country_id']) && !$this->request->isPost()) {
			$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		}

		if (!empty($country_info)) {
			$this->data['name'] = $country_info['name'];
			$this->data['iso_code_2'] = $country_info['iso_code_2'];
			$this->data['iso_code_3'] = $country_info['iso_code_3'];
			$this->data['address_format'] = $country_info['address_format'];
			$this->data['postcode_required'] = $country_info['postcode_required'];
			$this->data['status'] = $country_info['status'];
		} else {
			$this->data['name'] = P('name', '');
			$this->data['iso_code_2'] = P('iso_code_2', '');
			$this->data['iso_code_3'] = P('iso_code_3', '');
			$this->data['address_format'] = P('address_format', '');
			$this->data['postcode_required'] = P('postcode_required', 0);
			$this->data['status'] = P('status', 1);
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/country_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!range_length($this->request->post['name'], 3, 128)) {
			$this->setMessage('error_name', L('error_name'));
			return false;
		}

		return true;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		M('sale/customer');
		M('sale/affiliate');
		M('localisation/zone');
		M('localisation/geo_zone');

		foreach ($this->request->post['selected'] as $country_id) {
			if (C('config_country_id') == $country_id) {
				$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$store_total = $this->model_setting_store->getTotalStoresByCountryId($country_id);

			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}

			$address_total = $this->model_sale_customer->getTotalAddressesByCountryId($country_id);

			if ($address_total) {
				$this->setMessage('error_warning', sprintf(L('error_address'), $address_total));
				return false;
			}

			$affiliate_total = $this->model_sale_affiliate->getTotalAffiliatesByCountryId($country_id);

			if ($affiliate_total) {
				$this->setMessage('error_warning', sprintf(L('error_affiliate'), $affiliate_total));
				return false;
			}

			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);

			if ($zone_total) {
				$this->setMessage('error_warning', sprintf(L('error_zone'), $zone_total));
				return false;
			}

			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByCountryId($country_id);

			if ($zone_to_geo_zone_total) {
				$this->setMessage('error_warning', sprintf(L('error_zone_to_geo_zone'), $zone_to_geo_zone_total));
				return false;
			}
		}

		return true;
	}
}
?>