<?php
class ControllerLocalisationTaxRate extends Controller {

	public function index() {
		$this->language->load('localisation/tax_rate');

		M('localisation/tax_rate');

		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/tax_rate');

		M('localisation/tax_rate');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_tax_rate->addTaxRate($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/tax_rate'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/tax_rate');

		M('localisation/tax_rate');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_tax_rate->editTaxRate($this->request->get['tax_rate_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/tax_rate'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/tax_rate');

		M('localisation/tax_rate');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tax_rate_id) {
				$this->model_localisation_tax_rate->deleteTaxRate($tax_rate_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/tax_rate'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$sort = $qf->get('sort', 'tr.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['tax_rates'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRates();

		$results = $this->model_localisation_tax_rate->getTaxRates($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/tax_rate/update', 'tax_rate_id=' . $result['tax_rate_id'])
			);

			$this->data['tax_rates'][] = array(
				'tax_rate_id'   => $result['tax_rate_id'],
				'name'          => $result['name'],
				'rate'          => $result['rate'],
				'type'          => ($result['type'] == 'F' ? L('text_amount') : L('text_percent')),
				'geo_zone'      => $result['geo_zone'],
				'date_added'    => date(L('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date(L('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['tax_rate_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}


		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/tax_rate', 'sort=tr.name&order=' . $url);
		$this->data['sort_rate'] = UA('localisation/tax_rate', 'sort=tr.rate&order=' . $url);
		$this->data['sort_type'] = UA('localisation/tax_rate', 'sort=tr.type&order=' . $url);
		$this->data['sort_geo_zone'] = UA('localisation/tax_rate', 'sort=gz.name&order=' . $url);
		$this->data['sort_date_added'] = UA('localisation/tax_rate', 'sort=tr.date_added&order=' . $url);
		$this->data['sort_date_modified'] = UA('localisation/tax_rate', 'sort=tr.date_modified&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $tax_rate_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/tax_rate', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/tax_rate_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (!isset($this->request->get['tax_rate_id'])) {
			$this->data['action'] = UA('localisation/tax_rate/insert');
		} else {
			$this->data['action'] = UA('localisation/tax_rate/update', 'tax_rate_id=' . $this->request->get['tax_rate_id']);
		}

		if (isset($this->request->get['tax_rate_id']) && !$this->request->isPost()) {
			$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($this->request->get['tax_rate_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($tax_rate_info)) {
			$this->data['name'] = $tax_rate_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['rate'])) {
			$this->data['rate'] = $this->request->post['rate'];
		} elseif (!empty($tax_rate_info)) {
			$this->data['rate'] = $tax_rate_info['rate'];
		} else {
			$this->data['rate'] = '';
		}

		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (!empty($tax_rate_info)) {
			$this->data['type'] = $tax_rate_info['type'];
		} else {
			$this->data['type'] = '';
		}

		if (isset($this->request->post['tax_rate_customer_group'])) {
			$this->data['tax_rate_customer_group'] = $this->request->post['tax_rate_customer_group'];
		} elseif (isset($this->request->get['tax_rate_id'])) {
			$this->data['tax_rate_customer_group'] = $this->model_localisation_tax_rate->getTaxRateCustomerGroups($this->request->get['tax_rate_id']);
		} else {
			$this->data['tax_rate_customer_group'] = array();
		}

		M('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['geo_zone_id'])) {
			$this->data['geo_zone_id'] = $this->request->post['geo_zone_id'];
		} elseif (!empty($tax_rate_info)) {
			$this->data['geo_zone_id'] = $tax_rate_info['geo_zone_id'];
		} else {
			$this->data['geo_zone_id'] = '';
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/tax_rate_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_rate')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;
		if (!range_length($this->request->post['name'], 3, 32)) {
			$this->setMessage('error_name', L('error_name'));
			$pass = false;
		}

		if (!$this->request->post['rate']) {
			$this->setMessage('error_rate', L('error_rate'));
			$pass = false;
		}

		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_rate')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('localisation/tax_class');

		foreach ($this->request->post['selected'] as $tax_rate_id) {
			$tax_rule_total = $this->model_localisation_tax_class->getTotalTaxRulesByTaxRateId($tax_rate_id);

			if ($tax_rule_total) {
				$this->setMessage('error_warning', sprintf(L('error_tax_rule'), $tax_rule_total));
				return false;
			}
		}
		return true;
	}
}
?>