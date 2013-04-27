<?php
class ControllerPaymentCod extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = L('button_confirm');

		$this->data['continue'] = U('checkout/success');

		$this->render('payment/cod.tpl');
	}

	public function confirm() {
		M('checkout/order');

		$this->model_checkout_order->confirm($this->session->data['order_id'], C('cod_order_status_id'));
	}
}
?>