<?php
class ControllerPaymentPaymate extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = L('button_confirm');

		if (!C('paymate_test')) {
			$this->data['action'] = 'https://www.paymate.com/PayMate/ExpressPayment';
		} else {
			$this->data['action'] = 'https://www.paymate.com.au/PayMate/TestExpressPayment';
		}

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['mid'] = C('paymate_username');
		$this->data['amt'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$this->data['currency'] = $order_info['currency_code'];
		$this->data['ref'] = $order_info['order_id'];

		$this->data['pmt_sender_email'] = $order_info['email'];
		$this->data['pmt_contact_firstname'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$this->data['pmt_contact_surname'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['pmt_contact_phone'] = $order_info['telephone'];
		$this->data['pmt_country'] = $order_info['payment_iso_code_2'];

		$this->data['regindi_address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_sub'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_pcode'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');

		$this->data['return'] = U('payment/paymate/callback', 'hash=' . md5($order_info['order_id'] . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . $order_info['currency_code'] . C('paymate_password')));

		$this->render('payment/paymate.tpl');
	}

	public function callback() {
		$this->language->load('payment/paymate');

		if (isset($this->request->post['ref'])) {
			$order_id = $this->request->post['ref'];
		} else {
			$order_id = 0;
		}

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$error = '';

			if (!isset($this->request->post['responseCode']) || !isset($this->request->get['hash'])) {
				$error = L('text_unable');
			} elseif ($this->request->get['hash'] != md5($order_info['order_id'] . $this->currency->format($this->request->post['paymentAmount'], $this->request->post['currency'], 1.0000000, false) . $this->request->post['currency'] . C('paymate_password'))) {
				$error = L('text_unable');
			} elseif ($this->request->post['responseCode'] != 'PA' && $this->request->post['responseCode'] != 'PP') {
				$error = L('text_declined');
			}
		} else {
			$error = L('text_unable');
		}

		if ($error) {
			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'href'      => HTTP_SERVER,
				'text'      => L('text_home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'href'      => U('checkout/cart'),
				'text'      => L('text_basket'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'href'      => U('checkout/checkout', '', 'SSL'),
				'text'      => L('text_checkout'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'href'      => U('checkout/success'),
				'text'      => L('text_failed'),
				'separator' => L('text_separator')
			);

			$this->data['heading_title'] = L('text_failed');

			$this->data['text_message'] = sprintf(L('text_failed_message'), $error, U('information/contact'));

			$this->data['button_continue'] = L('button_continue');

			$this->data['continue'] = HTTP_SERVER;

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->display('common/success.tpl');
		} else {
			$this->model_checkout_order->confirm($order_id, C('paymate_order_status_id'));

			$this->redirect(U('checkout/success'));
		}
	}
}
?>