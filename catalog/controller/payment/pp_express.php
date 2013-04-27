<?php
class ControllerPaymentPPExpress extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = L('button_confirm');

		if (!C('pp_express_test')) {
    		$this->data['action'] = 'https://www.pp_express.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.pp_express.com/cgi-bin/webscr';
		}

		M('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!C('pp_direct_test')) {
			$api_endpoint = 'https://api-3t.pp.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.sandbox.pp.com/nvp';
		}

		$this->render('payment/pp_express.tpl');
	}
}
?>