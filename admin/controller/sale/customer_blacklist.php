<?php
class ControllerSaleCustomerBlacklist extends Controller {

  	public function index() {
		$this->language->load('sale/customer_blacklist');
		M('sale/customer_blacklist');
    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('sale/customer_blacklist');

		M('sale/customer_blacklist');

		if ($this->request->isPost() && $this->validateForm()) {
      	  	$this->model_sale_customer_blacklist->addCustomerBlacklist($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer_blacklist'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('sale/customer_blacklist');

		M('sale/customer_blacklist');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_customer_blacklist->editCustomerBlacklist($this->request->get['customer_ip_blacklist_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer_blacklist'));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('sale/customer_blacklist');

		M('sale/customer_blacklist');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_ip_blacklist_id) {
				$this->model_sale_customer_blacklist->deleteCustomerBlacklist($customer_ip_blacklist_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer_blacklist'));
    	}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'ip');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['insert'] = UA('sale/customer_blacklist/insert');
		$this->data['delete'] = UA('sale/customer_blacklist/delete');

		$this->data['customer_blacklists'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$customer_blacklist_total = $this->model_sale_customer_blacklist->getTotalCustomerBlacklists($data);

		$results = $this->model_sale_customer_blacklist->getCustomerBlacklists($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/customer_blacklist/update', 'customer_ip_blacklist_id=' . $result['customer_ip_blacklist_id'])
			);

			$this->data['customer_blacklists'][] = array(
				'customer_ip_blacklist_id' => $result['customer_ip_blacklist_id'],
				'ip'                       => $result['ip'],
				'total'                    => $result['total'],
				'customer'                 => UA('sale/customer', 'filter_ip=' . $result['ip']),
				'selected'                 => isset($this->request->post['selected']) && in_array($result['customer_ip_blacklist_id'], $this->request->post['selected']),
				'action'                   => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$this->data['sort_ip'] = UA('sale/customer_blacklist', 'sort=ip&order=' . ($order=='ASC' ? 'DESC': 'ASC'));

		$pagination = new Pagination();
		$pagination->total = $customer_blacklist_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/customer_blacklist', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/customer_blacklist_list.tpl');
  	}

  	protected function getForm() {
    	$this->document->setTitle(L('heading_title'));

		if (!isset($this->request->get['customer_ip_blacklist_id'])) {
			$this->data['action'] = UA('sale/customer_blacklist/insert');
		} else {
			$this->data['action'] = UA('sale/customer_blacklist/update', 'customer_ip_blacklist_id=' . $this->request->get['customer_ip_blacklist_id']);
		}

    	$this->data['cancel'] = UA('sale/customer_blacklist');

    	if (isset($this->request->get['customer_ip_blacklist_id']) && !$this->request->isPost()) {
      		$customer_blacklist_info = $this->model_sale_customer_blacklist->getCustomerBlacklist($this->request->get['customer_ip_blacklist_id']);
    	}

    	if (isset($this->request->post['ip'])) {
      		$this->data['ip'] = $this->request->post['ip'];
		} elseif (!empty($customer_blacklist_info)) {
			$this->data['ip'] = $customer_blacklist_info['ip'];
		} else {
      		$this->data['ip'] = '';
    	}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/customer_blacklist_form.tpl');
	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/customer_blacklist')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

    	if (!range_length($this->request->post['ip'], 1, 40)) {
      		$this->setMessage('error_ip', L('error_ip'));
			return false;
    	}

	  	return true;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/customer_blacklist')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		return true;
  	}
}
?>