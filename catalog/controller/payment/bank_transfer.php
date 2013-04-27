<?php
class ControllerPaymentBankTransfer extends Controller {
	protected function index() {
		$this->language->load('payment/bank_transfer');

		$this->data['text_instruction'] = L('text_instruction');
		$this->data['text_description'] = L('text_description');
		$this->data['text_payment'] = L('text_payment');

		$this->data['button_confirm'] = L('button_confirm');

		$this->data['bank'] = nl2br(C('bank_transfer_bank_' . C('config_language_id')));

		$this->data['continue'] = U('checkout/success');

		$this->render('payment/bank_transfer.tpl');
	}

	public function confirm() {
		$this->language->load('payment/bank_transfer');

		M('checkout/order');

		$comment  = L('text_instruction') . "\n\n";
		$comment .= C('bank_transfer_bank_' . C('config_language_id')) . "\n\n";
		$comment .= L('text_payment');

		$this->model_checkout_order->confirm($this->session->data['order_id'], C('bank_transfer_order_status_id'), $comment, true);
	}
}
?>