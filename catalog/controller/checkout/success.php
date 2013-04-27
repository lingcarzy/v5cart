<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {

		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
		}

		$this->language->load('checkout/success');

		$this->document->setTitle(L('heading_title'));

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
        	'text'      => L('text_success'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

		if ($this->customer->isLogged()) {
    		$this->data['text_message'] = sprintf(L('text_customer'), U('account/account', '', 'SSL'), U('account/order', '', 'SSL'), U('account/download', '', 'SSL'), U('page/contact'));
		} else {
    		$this->data['text_message'] = sprintf(L('text_guest'), U('page/contact'));
		}

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
  	}
}
?>