<?php
class ControllerPaymentLiqPay extends Controller {
	protected function index() {
		$this->data['button_confirm'] = L('button_confirm');

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['action'] = 'https://liqpay.com/?do=clickNbuy';

		$xml  = '<request>';
		$xml .= '	<version>1.2</version>';
		$xml .= '	<result_url>' . U('checkout/success', '', 'SSL') . '</result_url>';
		$xml .= '	<server_url>' . U('payment/liqpay/callback', '', 'SSL') . '</server_url>';
		$xml .= '	<merchant_id>' . C('liqpay_merchant') . '</merchant_id>';
		$xml .= '	<order_id>' . $this->session->data['order_id'] . '</order_id>';
		$xml .= '	<amount>' . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . '</amount>';
		$xml .= '	<currency>' . $order_info['currency_code'] . '</currency>';
		$xml .= '	<description>' . C('config_name') . ' ' . $order_info['payment_firstname'] . ' ' . $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'] . ' ' . $order_info['payment_city'] . ' ' . $order_info['email'] . '</description>';
		$xml .= '	<default_phone></default_phone>';
		$xml .= '	<pay_way>' . C('liqpay_type') . '</pay_way>';
		$xml .= '</request>';

		$this->data['xml'] = base64_encode($xml);
		$this->data['signature'] = base64_encode(sha1(C('liqpay_signature') . $xml . C('liqpay_signature'), true));

		$this->render('payment/liqpay.tpl');
	}

	public function callback() {
		$xml = base64_decode($this->request->post['operation_xml']);
		$signature = base64_encode(sha1(C('liqpay_signature') . $xml . C('liqpay_signature'), true));

		$posleft = strpos($xml, 'order_id');
		$posright = strpos($xml, '/order_id');

		$order_id = substr($xml, $posleft + 9, $posright - $posleft - 10);

		if ($signature == $this->request->post['signature']) {
			M('checkout/order');

			$this->model_checkout_order->confirm($order_id, C('config_order_status_id'));
		}
	}
}
?>