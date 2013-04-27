<?php
class ControllerSaleCustomerGroup extends Controller {

	public function index() {
		$this->language->load('sale/customer_group');

		M('sale/customer_group');

		$this->getList();
	}

	public function insert() {
		$this->language->load('sale/customer_group');

		M('sale/customer_group');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_customer_group->addCustomerGroup($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer_group'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('sale/customer_group');

		M('sale/customer_group');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_customer_group->editCustomerGroup($this->request->get['customer_group_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer_group'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('sale/customer_group');

		M('sale/customer_group');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $customer_group_id) {
				$this->model_sale_customer_group->deleteCustomerGroup($customer_group_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer_group'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter('order_query_filter');
		$sort = $qf->get('sort', 'cgd.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['insert'] = UA('sale/customer_group/insert');
		$this->data['delete'] = UA('sale/customer_group/delete');

		$this->data['customer_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$customer_group_total = $this->model_sale_customer_group->getTotalCustomerGroups();

		$results = $this->model_sale_customer_group->getCustomerGroups($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/customer_group/update', 'customer_group_id=' . $result['customer_group_id'])
			);

			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == C('config_customer_group_id')) ? L('text_default') : null),
				'sort_order'        => $result['sort_order'],
				'selected'          => isset($this->request->post['selected']) && in_array($result['customer_group_id'], $this->request->post['selected']),
				'action'            => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');


		if ($order == 'ASC') {
			$url = '&order=DESC';
		} else {
			$url = '&order=ASC';
		}

		$this->data['sort_name'] = UA('sale/customer_group', 'sort=cgd.name' . $url);
		$this->data['sort_sort_order'] = UA('sale/customer_group', 'sort=cg.sort_order' . $url);

		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/customer_group', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/customer_group_list.tpl');
 	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (!isset($this->request->get['customer_group_id'])) {
			$this->data['action'] = UA('sale/customer_group/insert');
		} else {
			$this->data['action'] = UA('sale/customer_group/update', 'customer_group_id=' . $this->request->get['customer_group_id']);
		}

    	$this->data['cancel'] = UA('sale/customer_group');

		if (isset($this->request->get['customer_group_id']) && !$this->request->isPost()) {
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($this->request->get['customer_group_id']);
		}

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['customer_group_description'])) {
			$this->data['customer_group_description'] = $this->request->post['customer_group_description'];
		} elseif (isset($this->request->get['customer_group_id'])) {
			$this->data['customer_group_description'] = $this->model_sale_customer_group->getCustomerGroupDescriptions($this->request->get['customer_group_id']);
		} else {
			$this->data['customer_group_description'] = array();
		}

		if (!empty($customer_group_info)) {
			$this->data['approval'] = $customer_group_info['approval'];
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
			$this->data['company_id_required'] = $customer_group_info['company_id_required'];
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
			$this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
			$this->data['sort_order'] = $customer_group_info['sort_order'];
		} else {
			$this->data['approval'] = P('approval', 0);
			$this->data['company_id_display'] = P('company_id_display', 0);
			$this->data['company_id_required'] = P('company_id_required', 0);
			$this->data['tax_id_display'] = P('tax_id_display', 0);
			$this->data['tax_id_required'] = P('tax_id_required', 0);
			$this->data['sort_order'] = P('sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/customer_group_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$pass = true;
		foreach ($this->request->post['customer_group_description'] as $language_id => $value) {
			if (!range_length($value['name'], 3, 32)) {
				$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
			}
		}
		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		M('sale/customer');

		foreach ($this->request->post['selected'] as $customer_group_id) {
    		if (C('config_customer_group_id') == $customer_group_id) {
	  			$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_group_id);

			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}

			$customer_total = $this->model_sale_customer->getTotalCustomersByCustomerGroupId($customer_group_id);

			if ($customer_total) {
				$this->setMessage('error_warning', sprintf(L('error_customer'), $customer_total));
				return false;
			}
		}

		return true;
	}
}
?>