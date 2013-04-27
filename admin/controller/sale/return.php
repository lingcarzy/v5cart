<?php
class ControllerSaleReturn extends Controller {

  	public function index() {
		$this->language->load('sale/return');
		M('sale/return');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('sale/return');
		M('sale/return');

		if ($this->request->isPost() && $this->validateForm()) {
      	  	$this->model_sale_return->addReturn($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/return'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('sale/return');

		M('sale/return');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_return->editReturn($this->request->get['return_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/return'));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('sale/return');

		M('sale/return');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $return_id) {
				$this->model_sale_return->deleteReturn($return_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/return'));
    	}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter('return_query_filter');
		$filter_return_id = $qf->get('filter_return_id');
		$filter_order_id = $qf->get('filter_order_id');
		$filter_customer = $qf->get('filter_customer');
		$filter_product = $qf->get('filter_product');
		$filter_model = $qf->get('filter_model');
		$filter_return_status_id = $qf->get('filter_return_status_id');
		$filter_date_added = $qf->get('filter_date_added');
		$filter_date_modified = $qf->get('filter_date_modified');
		$sort = $qf->get('sort', 'r.return_id');
		$order = $qf->get('order', 'DESC');
		$page = $qf->get('page', 1);

		$this->data['returns'] = array();

		$data = array(
			'filter_return_id'        => $filter_return_id,
			'filter_order_id'         => $filter_order_id,
			'filter_customer'         => $filter_customer,
			'filter_product'          => $filter_product,
			'filter_model'            => $filter_model,
			'filter_return_status_id' => $filter_return_status_id,
			'filter_date_added'       => $filter_date_added,
			'filter_date_modified'    => $filter_date_modified,
			'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * C('config_admin_limit'),
			'limit'                   => C('config_admin_limit')
		);

		$return_total = $this->model_sale_return->getTotalReturns($data);

		$results = $this->model_sale_return->getReturns($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_view'),
				'href' => UA('sale/return/info', 'return_id=' . $result['return_id'])
			);

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/return/update', 'return_id=' . $result['return_id'])
			);

			$this->data['returns'][] = array(
				'return_id'     => $result['return_id'],
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'product'       => $result['product'],
				'model'         => $result['model'],
				'status'        => $result['status'],
				'date_added'    => date(L('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date(L('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['return_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		$this->data['sort_return_id'] = UA('sale/return', 'sort=r.return_id' . $url);
		$this->data['sort_order_id'] = UA('sale/return', 'sort=r.order_id' . $url);
		$this->data['sort_customer'] = UA('sale/return', 'sort=customer' . $url);
		$this->data['sort_product'] = UA('sale/return', 'sort=product' . $url);
		$this->data['sort_model'] = UA('sale/return', 'sort=model' . $url);
		$this->data['sort_status'] = UA('sale/return', 'sort=status' . $url);
		$this->data['sort_date_added'] = UA('sale/return', 'sort=r.date_added' . $url);
		$this->data['sort_date_modified'] = UA('sale/return', 'sort=r.date_modified' . $url);

		$pagination = new Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/return', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_return_id'] = $filter_return_id;
		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_product'] = $filter_product;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_return_status_id'] = $filter_return_status_id;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		M('localisation/return_status');

    	$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/return_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['return_id'])) {
			$this->data['action'] = UA('sale/return/update', 'return_id=' . $this->request->get['return_id']);
		} else {
			$this->data['action'] = UA('sale/return/insert');
		}

    	$this->data['cancel'] = UA('sale/return');

    	if (isset($this->request->get['return_id']) && !$this->request->isPost()) {
      		$return_info = $this->model_sale_return->getReturn($this->request->get['return_id']);
    	}

		if (!empty($return_info)) {
			$this->data['order_id'] = $return_info['order_id'];
			$this->data['date_ordered'] = $return_info['date_ordered'];
			$this->data['customer'] = $return_info['customer'];
			$this->data['customer_id'] = $return_info['customer_id'];
			$this->data['firstname'] = $return_info['firstname'];
			$this->data['lastname'] = $return_info['lastname'];
			$this->data['email'] = $return_info['email'];
			$this->data['telephone'] = $return_info['telephone'];
			$this->data['product'] = $return_info['product'];
			$this->data['product_id'] = $return_info['product_id'];
			$this->data['model'] = $return_info['model'];
			$this->data['quantity'] = $return_info['quantity'];
			$this->data['opened'] = $return_info['opened'];
			$this->data['return_reason_id'] = $return_info['return_reason_id'];
			$this->data['return_action_id'] = $return_info['return_action_id'];
			$this->data['comment'] = $return_info['comment'];
			$this->data['return_status_id'] = $return_info['return_status_id'];
			
		} else {
      		$this->data['order_id'] = P('order_id');
			$this->data['date_ordered'] = P('date_ordered');
			$this->data['customer'] = P('customer');
			$this->data['customer_id'] = P('customer_id');
			$this->data['firstname'] = P('firstname');
			$this->data['lastname'] = P('lastname');
			$this->data['email'] = P('email');
			$this->data['telephone'] = P('telephone');
			$this->data['product'] = P('product');
			$this->data['product_id'] = P('product_id');
			$this->data['model'] = P('model');
			$this->data['quantity'] = P('quantity');
			$this->data['opened'] = P('opened');
			$this->data['return_reason_id'] = P('return_reason_id');
			$this->data['return_action_id'] = P('return_action_id');
			$this->data['comment'] = P('comment');
			$this->data['return_status_id'] = P('return_status_id');
    	}

		M('localisation/return_reason');
		$this->data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		M('localisation/return_action');
		$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

		M('localisation/return_status');
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/return_form.tpl');
	}

	public function info() {
		M('sale/return');

		$return_id = G('return_id', 0);

		$return_info = $this->model_sale_return->getReturn($return_id);

		if ($return_info) {
			$this->language->load('sale/return');

			$this->document->setTitle(L('heading_title'));
			
			$this->data['cancel'] = UA('sale/return');

			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($return_info['order_id']);

			$this->data['return_id'] = $return_info['return_id'];
			$this->data['order_id'] = $return_info['order_id'];

			if ($return_info['order_id'] && $order_info) {
				$this->data['order'] = UA('sale/order/info', 'order_id=' . $return_info['order_id']);
			} else {
				$this->data['order'] = '';
			}

			$this->data['date_ordered'] = date(L('date_format_short'), strtotime($return_info['date_ordered']));
			$this->data['firstname'] = $return_info['firstname'];
			$this->data['lastname'] = $return_info['lastname'];

			if ($return_info['customer_id']) {
				$this->data['customer'] = UA('sale/customer/update', 'customer_id=' . $return_info['customer_id']);
			} else {
				$this->data['customer'] = '';
			}

			$this->data['email'] = $return_info['email'];
			$this->data['telephone'] = $return_info['telephone'];

			M('localisation/return_status');

			$return_status_info = $this->model_localisation_return_status->getReturnStatus($return_info['return_status_id']);

			if ($return_status_info) {
				$this->data['return_status'] = $return_status_info['name'];
			} else {
				$this->data['return_status'] = '';
			}

			$this->data['date_added'] = date(L('date_format_short'), strtotime($return_info['date_added']));
			$this->data['date_modified'] = date(L('date_format_short'), strtotime($return_info['date_modified']));
			$this->data['product'] = $return_info['product'];
			$this->data['model'] = $return_info['model'];
			$this->data['quantity'] = $return_info['quantity'];

			M('localisation/return_reason');

			$return_reason_info = $this->model_localisation_return_reason->getReturnReason($return_info['return_reason_id']);

			if ($return_reason_info) {
				$this->data['return_reason'] = $return_reason_info['name'];
			} else {
				$this->data['return_reason'] = '';
			}

			$this->data['opened'] = $return_info['opened'] ? L('text_yes') : L('text_no');
			$this->data['comment'] = nl2br($return_info['comment']);

			M('localisation/return_action');

			$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

			$this->data['return_action_id'] = $return_info['return_action_id'];

			$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

			$this->data['return_status_id'] = $return_info['return_status_id'];

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->display('sale/return_info.tpl');
		} else {
			$this->language->load('error/not_found');
			$this->document->setTitle(L('heading_title'));
			$this->data['heading_title'] = L('heading_title');
			$this->data['text_not_found'] = L('text_not_found');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => UA('common/home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('heading_title'),
				'href'      => UA('error/not_found'),
				'separator' => ' :: '
			);

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->display('error/not_found.tpl');
		}
	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/return')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$pass = true;
    	if (!range_length($this->request->post['firstname'], 1, 32)) {
      		$this->setMessage('error_firstname', L('error_firstname'));
			$pass = false;
    	}

    	if (!range_length($this->request->post['lastname'], 1, 32)) {
      		$this->setMessage('error_lastname', L('error_lastname'));
			$pass = false;
    	}

    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->setMessage('error_email', L('error_email'));
			$pass = false;
    	}

    	if (!range_length($this->request->post['telephone'], 3, 32)) {
      		$this->setMessage('error_telephone', L('error_telephone'));
			$pass = false;
    	}

		if (!range_length($this->request->post['product'], 1, 255)) {
			$this->setMessage('error_product', L('error_product'));
			$pass = false;
		}

		if (!range_length($this->request->post['model'], 1, 64)) {
			$this->setMessage('error_model', L('error_model'));
			$pass = false;
		}

		if (empty($this->request->post['return_reason_id'])) {
			$this->setMessage('error_reason', L('error_reason'));
			$pass = false;
		}

		if (!$pass) {
			$this->setMessage('error_warning', L('error_warning'));
		}

		return $pass;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/return')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		return true;
  	}

	public function action() {
		$this->language->load('sale/return');

		$json = array();

		if ($this->request->isPost()) {
			if (!$this->user->hasPermission('modify', 'sale/return')) {
				$json['error'] = L('error_permission');
			}

			if (!$json) {
				M('sale/return');

				$json['success'] = L('text_success');

				$this->model_sale_return->editReturnAction($this->request->get['return_id'], $this->request->post['return_action_id']);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function history() {
    	$this->language->load('sale/return');

		$this->data['error'] = '';
		$this->data['success'] = '';

		M('sale/return');

		if ($this->request->isPost()) {
			if (!$this->user->hasPermission('modify', 'sale/return')) {
				$this->data['error'] = L('error_permission');
			}

			if (!$this->data['error']) {
				$this->model_sale_return->addReturnHistory($this->request->get['return_id'], $this->request->post);

				$this->data['success'] = L('text_success');
			}
		}

		$page = G('page', 1);

		$this->data['histories'] = array();

		$results = $this->model_sale_return->getReturnHistories($this->request->get['return_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? L('text_yes') : L('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
        		'date_added' => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$history_total = $this->model_sale_return->getTotalReturnHistories($this->request->get['return_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/return/history', 'return_id=' . $this->request->get['return_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->display('sale/return_history.tpl');
  	}
}
?>