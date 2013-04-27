<?php
class ControllerPaymentCheque extends Controller {
	protected function index() {
		$this->language->load('payment/cheque');

		$this->data['text_instruction'] = L('text_instruction');
    	$this->data['text_payable'] = L('text_payable');
		$this->data['text_address'] = L('text_address');
		$this->data['text_payment'] = L('text_payment');

		$this->data['button_confirm'] = L('button_confirm');

		$this->data['payable'] = C('cheque_payable');
		$this->data['address'] = nl2br(C('config_address'));

		$this->data['continue'] = U('checkout/success');

		$this->render('payment/cheque.tpl');
	}

	public function confirm() {
		$this->language->load('payment/cheque');

		M('checkout/order');

		$comment  = L('text_payable') . "\n";
		$comment .= C('cheque_payable') . "\n\n";
		$comment .= L('text_address') . "\n";
		$comment .= C('config_address') . "\n\n";
		$comment .= L('text_payment') . "\n";

		$this->model_checkout_order->confirm($this->session->data['order_id'], C('cheque_order_status_id'), $comment, true);
	}
}
?>