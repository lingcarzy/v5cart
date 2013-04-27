<?php
class ControllerPaymentWorldPay extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = L('button_confirm');

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!C('worldpay_test')){
			$this->data['action'] = 'https://secure.worldpay.com/wcc/purchase';
		}else{
			$this->data['action'] = 'https://secure-test.worldpay.com/wcc/purchase';
		}

		$this->data['merchant'] = C('worldpay_merchant');
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['currency'] = $order_info['currency_code'];
		$this->data['description'] = C('config_name') . ' - #' . $order_info['order_id'];
		$this->data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

		if (!$order_info['payment_address_2']) {
			$this->data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		} else {
			$this->data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_address_2'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		}

		$this->data['postcode'] = $order_info['payment_postcode'];
		$this->data['country'] = $order_info['payment_iso_code_2'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['email'] = $order_info['email'];
		$this->data['test'] = C('worldpay_test');

		$this->render('payment/worldpay.tpl');
	}

	public function callback() {
		$this->language->load('payment/worldpay');

		$this->data['title'] = sprintf(L('heading_title'), C('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$this->data['base'] = C('config_url');
		} else {
			$this->data['base'] = C('config_ssl');
		}

		$this->data['language'] = L('code');
		$this->data['direction'] = L('direction');

		$this->data['heading_title'] = sprintf(L('heading_title'), C('config_name'));

		$this->data['text_response'] = L('text_response');
		$this->data['text_success'] = L('text_success');
		$this->data['text_success_wait'] = sprintf(L('text_success_wait'), U('checkout/success'));
		$this->data['text_failure'] = L('text_failure');
		$this->data['text_failure_wait'] = sprintf(L('text_failure_wait'), U('checkout/checkout', '', 'SSL'));

		if (isset($this->request->post['transStatus']) && $this->request->post['transStatus'] == 'Y') {
			M('checkout/order');

			// If returned successful but callbackPW doesn't match, set order to pendind and record reason
			if (isset($this->request->post['callbackPW']) && ($this->request->post['callbackPW'] == C('worldpay_password'))) {
				$this->model_checkout_order->confirm($this->request->post['cartId'], C('worldpay_order_status_id'));
			} else {
				$this->model_checkout_order->confirm($this->request->post['cartId'], C('config_order_status_id'), L('text_pw_mismatch'));
			}

			$message = '';

			if (isset($this->request->post['transId'])) {
				$message .= 'transId: ' . $this->request->post['transId'] . "\n";
			}

			if (isset($this->request->post['transStatus'])) {
				$message .= 'transStatus: ' . $this->request->post['transStatus'] . "\n";
			}

			if (isset($this->request->post['countryMatch'])) {
				$message .= 'countryMatch: ' . $this->request->post['countryMatch'] . "\n";
			}

			if (isset($this->request->post['AVS'])) {
				$message .= 'AVS: ' . $this->request->post['AVS'] . "\n";
			}

			if (isset($this->request->post['rawAuthCode'])) {
				$message .= 'rawAuthCode: ' . $this->request->post['rawAuthCode'] . "\n";
			}

			if (isset($this->request->post['authMode'])) {
				$message .= 'authMode: ' . $this->request->post['authMode'] . "\n";
			}

			if (isset($this->request->post['rawAuthMessage'])) {
				$message .= 'rawAuthMessage: ' . $this->request->post['rawAuthMessage'] . "\n";
			}

			if (isset($this->request->post['wafMerchMessage'])) {
				$message .= 'wafMerchMessage: ' . $this->request->post['wafMerchMessage'] . "\n";
			}

			$this->model_checkout_order->update($this->request->post['cartId'], C('worldpay_order_status_id'), $message, false);

			$this->data['continue'] = U('checkout/success');

			$this->display('payment/worldpay_success.tpl');
		} else {
			$this->data['continue'] = U('checkout/cart');

			$this->display('payment/worldpay_failure.tpl');
		}
	}
}
?>