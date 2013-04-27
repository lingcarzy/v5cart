<?php
class ControllerSaleVoucher extends Controller {

  	public function index() {
		$this->language->load('sale/voucher');

		M('sale/voucher');

		$this->getList();
  	}

  	public function insert() {
    	$this->language->load('sale/voucher');
		M('sale/voucher');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_voucher->addVoucher($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/voucher'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->language->load('sale/voucher');

		M('sale/voucher');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_voucher->editVoucher($this->request->get['voucher_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/voucher'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->language->load('sale/voucher');

		M('sale/voucher');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $voucher_id) {
				$this->model_sale_voucher->deleteVoucher($voucher_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/voucher'));
    	}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'v.date_added');
		$order = $qf->get('order', 'DESC');
		$page = $qf->get('page', 1);

		$this->data['vouchers'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$voucher_total = $this->model_sale_voucher->getTotalVouchers();

		$results = $this->model_sale_voucher->getVouchers($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/voucher/update', 'voucher_id=' . $result['voucher_id'])
			);

			$this->data['vouchers'][] = array(
				'voucher_id' => $result['voucher_id'],
				'code'       => $result['code'],
				'from'       => $result['from_name'],
				'to'         => $result['to_name'],
				'theme'      => $result['theme'],
				'amount'     => $this->currency->format($result['amount'], C('config_currency')),
				'status'     => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['voucher_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

 		$this->data['success'] = $this->session->flashdata('success');


		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_code'] = UA('sale/voucher', 'sort=v.code&order=' . $url);
		$this->data['sort_from'] = UA('sale/voucher', 'sort=v.from_name&order=' . $url);
		$this->data['sort_to'] = UA('sale/voucher', 'sort=v.to_name&order=' . $url);
		$this->data['sort_theme'] = UA('sale/voucher', 'sort=theme&order=' . $url);
		$this->data['sort_amount'] = UA('sale/voucher', 'sort=v.amount&order=' . $url);
		$this->data['sort_status'] = UA('sale/voucher', 'sort=v.status&order=' . $url);
		$this->data['sort_date_added'] = UA('sale/voucher', 'sort=v.date_added&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $voucher_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/voucher', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/voucher_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['voucher_id'])) {
			$this->data['voucher_id'] = $this->request->get['voucher_id'];
			$this->data['action'] = UA('sale/voucher/update', 'voucher_id=' . $this->request->get['voucher_id']);
		} else {
			$this->data['voucher_id'] = 0;
			$this->data['action'] = UA('sale/voucher/insert');
		}

		if (isset($this->request->get['voucher_id']) && !$this->request->isPost()) {
      		$voucher_info = $this->model_sale_voucher->getVoucher($this->request->get['voucher_id']);
    	}

    	if (!empty($voucher_info)) {
			$this->data['code'] = $voucher_info['code'];
			$this->data['from_name'] = $voucher_info['from_name'];
			$this->data['from_email'] = $voucher_info['from_email'];
			$this->data['to_name'] = $voucher_info['to_name'];
			$this->data['to_email'] = $voucher_info['to_email'];
			$this->data['voucher_theme_id'] = $voucher_info['voucher_theme_id'];
			$this->data['message'] = $voucher_info['message'];
			$this->data['amount'] = $voucher_info['amount'];
			$this->data['status'] = $voucher_info['status'];
		} else {
      		$this->data['code'] = P('code');
			$this->data['from_name'] = P('from_name');
			$this->data['from_email'] = P('from_email');
			$this->data['to_name'] = P('to_name');
			$this->data['to_email'] = P('to_email');
			$this->data['voucher_theme_id'] = P('voucher_theme_id');
			$this->data['message'] = P('message');
			$this->data['amount'] = P('amount');
			$this->data['status'] = P('status', 1);
    	}

 		M('sale/voucher_theme');
		$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/voucher_form.tpl');
  	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/voucher')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$pass = true;
    	if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 10)) {
      		$this->setMessage('error_code', L('error_code'));
			$pass = false;
    	}

		$voucher_info = $this->model_sale_voucher->getVoucherByCode($this->request->post['code']);

		if ($voucher_info) {
			if (!isset($this->request->get['voucher_id']) || $voucher_info['voucher_id'] != $this->request->get['voucher_id']) {
				$this->setMessage('error_warning', L('error_exists'));
				$pass = false;
			}
		}

    	if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
      		$this->setMessage('error_to_name', L('error_to_name'));
			$pass = false;
    	}

		if ((utf8_strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['to_email'])) {
      		$this->setMessage('error_to_email', L('error_email'));
			$pass = false;
    	}

    	if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
      		$this->setMessage('error_from_name', L('error_from_name'));
			$pass = false;
    	}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['from_email'])) {
      		$this->setMessage('error_from_email', L('error_email'));
			$pass = false;
    	}

		if ($this->request->post['amount'] < 1) {
      		$this->setMessage('error_amount', L('error_amount'));
			$pass = false;
    	}

    	return $pass;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/voucher')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('sale/order');

		foreach ($this->request->post['selected'] as $voucher_id) {
			$order_voucher_info = $this->model_sale_order->getOrderVoucherByVoucherId($voucher_id);

			if ($order_voucher_info) {
				$this->setMessage('error_warning', sprintf(L('error_order'), UA('sale/order/info', 'order_id=' . $order_voucher_info['order_id'])));
				return false;
			}
		}

		return true;
  	}

	public function history() {
    	$this->language->load('sale/voucher');

		M('sale/voucher');

		$page = G('page', 1);

		$this->data['histories'] = array();

		$results = $this->model_sale_voucher->getVoucherHistories($this->request->get['voucher_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $this->currency->format($result['amount'], C('config_currency')),
        		'date_added' => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$history_total = $this->model_sale_voucher->getTotalVoucherHistories($this->request->get['voucher_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = UA('sale/voucher/history', 'voucher_id=' . $this->request->get['voucher_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->display('sale/voucher_history.tpl');
  	}

	public function send() {
    	$this->language->load('sale/voucher');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/voucher')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['voucher_id'])) {
			M('sale/voucher');

			$this->model_sale_voucher->sendVoucher($this->request->get['voucher_id']);

			$json['success'] = L('text_sent');
		}

		$this->response->setOutput(json_encode($json));
  	}
}
?>