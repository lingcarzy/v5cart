<?php
class ControllerAccountLogout extends Controller {
	public function index() {
    	if ($this->customer->isLogged()) {
      		$this->customer->logout();
	  		$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_country_id']);
			unset($this->session->data['shipping_zone_id']);
			unset($this->session->data['shipping_postcode']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address_id']);
			unset($this->session->data['payment_country_id']);
			unset($this->session->data['payment_zone_id']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

      		$this->redirect(U('account/logout', '', 'SSL'));
    	}

    	$this->language->load('account/logout');

		$this->document->setTitle(L('heading_title'));

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

		$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_account'),
			'href'      => U('account/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_logout'),
			'href'      => U('account/logout', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');
    	$this->data['text_message'] = L('text_message');
    	$this->data['button_continue'] = L('button_continue');
    	$this->data['continue'] = HTTP_SERVER;
		$this->data['button_continue'] = L('button_continue');

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