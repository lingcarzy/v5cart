<?php
class ControllerPaymentPaypoint extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = L('button_confirm');

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['merchant'] = C('paypoint_merchant');
		$this->data['trans_id'] = $this->session->data['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		if (C('paypoint_password')) {
			$this->data['digest'] = md5($this->session->data['order_id'] . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . C('paypoint_password'));
		} else {
			$this->data['digest'] = '';
		}

		$this->data['bill_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['bill_addr_1'] = $order_info['payment_address_1'];
		$this->data['bill_addr_2'] = $order_info['payment_address_2'];
		$this->data['bill_city'] = $order_info['payment_city'];
		$this->data['bill_state'] = $order_info['payment_zone'];
		$this->data['bill_post_code'] = $order_info['payment_postcode'];
		$this->data['bill_country'] = $order_info['payment_country'];
		$this->data['bill_tel'] = $order_info['telephone'];
		$this->data['bill_email'] = $order_info['email'];

		if ($this->cart->hasShipping()) {
			$this->data['ship_name'] = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
			$this->data['ship_addr_1'] = $order_info['shipping_address_1'];
			$this->data['ship_addr_2'] = $order_info['shipping_address_2'];
			$this->data['ship_city'] = $order_info['shipping_city'];
			$this->data['ship_state'] = $order_info['shipping_zone'];
			$this->data['ship_post_code'] = $order_info['shipping_postcode'];
			$this->data['ship_country'] = $order_info['shipping_country'];
		} else {
			$this->data['ship_name'] = '';
			$this->data['ship_addr_1'] = '';
			$this->data['ship_addr_2'] = '';
			$this->data['ship_city'] = '';
			$this->data['ship_state'] = '';
			$this->data['ship_post_code'] = '';
			$this->data['ship_country'] = '';
		}

		$this->data['currency'] = $this->currency->getCode();
		$this->data['callback'] = U('payment/paypoint/callback', '', 'SSL');

		switch (C('paypoint_test')) {
			case 'live':
				$status = 'live';
				break;
			case 'successful':
			default:
				$status = 'true';
				break;
			case 'fail':
				$status = 'false';
				break;
		}

		$this->data['options'] = 'test_status=' . $status . ',dups=false,cb_post=false';

		$this->render('payment/paypoint.tpl');
	}

	public function callback() {
		if (isset($this->request->get['trans_id'])) {
			$order_id = $this->request->get['trans_id'];
		} else {
			$order_id = 0;
		}

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		// Validate the request is from PayPoint
		if (C('paypoint_password')) {
			if (!empty($this->request->get['hash'])) {
					$status = ($this->request->get['hash'] == md5(str_replace('hash=' . $this->request->get['hash'], '', htmlspecialchars_decode($this->request->server['REQUEST_URI'], ENT_COMPAT)) . C('paypoint_password')));
			} else {
				$status = false;
			}
		} else {
			$status = true;
		}

		if ($order_info) {
			$this->language->load('payment/paypoint');

			$this->data['title'] = sprintf(L('heading_title'), C('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}

			$this->data['language'] = L('code');
			$this->data['direction'] = L('direction');

			$this->data['heading_title'] = sprintf(L('heading_title'), C('config_name'));

			$this->data['text_response'] = L('text_response');
			$this->data['text_success'] = L('text_success');
			$this->data['text_success_wait'] = sprintf(L('text_success_wait'), U('checkout/success'));
			$this->data['text_failure'] = L('text_failure');
			$this->data['text_failure_wait'] = sprintf(L('text_failure_wait'), U('checkout/cart'));

			if (isset($this->request->get['code']) && $this->request->get['code'] == 'A' && $status) {
				M('checkout/order');

				$this->model_checkout_order->confirm($this->request->get['trans_id'], C('config_order_status_id'));

				$message = '';

				if (isset($this->request->get['code'])) {
					$message .= 'code: ' . $this->request->get['code'] . "\n";
				}

				if (isset($this->request->get['auth_code'])) {
					$message .= 'auth_code: ' . $this->request->get['auth_code'] . "\n";
				}

				if (isset($this->request->get['ip'])) {
					$message .= 'ip: ' . $this->request->get['ip'] . "\n";
				}

				if (isset($this->request->get['cv2avs'])) {
					$message .= 'cv2avs: ' . $this->request->get['cv2avs'] . "\n";
				}

				if (isset($this->request->get['valid'])) {
					$message .= 'valid: ' . $this->request->get['valid'] . "\n";
				}

				$this->model_checkout_order->update($order_id, C('paypoint_order_status_id'), $message, false);

				$this->data['continue'] = U('checkout/success');

				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);

				$this->display('payment/paypoint_success.tpl');
			} else {
				$this->data['continue'] = U('checkout/cart');

				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);

				$this->display('payment/paypoint_failure.tpl');
			}
		}
	}
}
?>