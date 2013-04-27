<?php
class ControllerPaymentPayza extends Controller {
	protected function index() {
		$this->data['button_confirm'] = L('button_confirm');

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['action'] = 'https://www.payza.com/PayProcess.aspx';

		$this->data['ap_merchant'] = C('payza_merchant');
		$this->data['ap_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['ap_currency'] = $order_info['currency_code'];
		$this->data['ap_purchasetype'] = 'Item';
		$this->data['ap_itemname'] = C('config_name') . ' - #' . $this->session->data['order_id'];
		$this->data['ap_itemcode'] = $this->session->data['order_id'];
		$this->data['ap_returnurl'] = U('checkout/success');
		$this->data['ap_cancelurl'] = U('checkout/checkout', '', 'SSL');

		$this->render('payment/payza.tpl');
	}

	public function callback() {
		if (isset($this->request->post['ap_securitycode']) && ($this->request->post['ap_securitycode'] == C('payza_security'))) {
			M('checkout/order');

			$this->model_checkout_order->confirm($this->request->post['ap_itemcode'], C('payza_order_status_id'));
		}
	}
}
?>