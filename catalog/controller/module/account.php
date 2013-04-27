<?php  
class ControllerModuleAccount extends Controller {
	protected function index() {
		$this->language->load('module/account');
		
		$this->data['heading_title'] = L('heading_title');
		$this->data['text_register'] = L('text_register');
    	$this->data['text_login'] = L('text_login');
		$this->data['text_logout'] = L('text_logout');
		$this->data['text_forgotten'] = L('text_forgotten');
		$this->data['text_account'] = L('text_account');
		$this->data['text_edit'] = L('text_edit');
		$this->data['text_password'] = L('text_password');
		$this->data['text_address'] = L('text_address');
		$this->data['text_wishlist'] = L('text_wishlist');
		$this->data['text_order'] = L('text_order');
		$this->data['text_download'] = L('text_download');
		$this->data['text_return'] = L('text_return');
		$this->data['text_transaction'] = L('text_transaction');
		$this->data['text_newsletter'] = L('text_newsletter');
		
		$this->data['register'] = U('account/register', '', 'SSL');
    	$this->data['login'] = U('account/login', '', 'SSL');
		$this->data['logout'] = U('account/logout', '', 'SSL');
		$this->data['forgotten'] = U('account/forgotten', '', 'SSL');
		$this->data['account'] = U('account/account', '', 'SSL');
		$this->data['edit'] = U('account/edit', '', 'SSL');
		$this->data['password'] = U('account/password', '', 'SSL');
		$this->data['address'] = U('account/address', '', 'SSL');
		$this->data['wishlist'] = U('account/wishlist');
		$this->data['order'] = U('account/order', '', 'SSL');
		$this->data['download'] = U('account/download', '', 'SSL');
		$this->data['return'] = U('account/return', '', 'SSL');
		$this->data['transaction'] = U('account/transaction', '', 'SSL');
		$this->data['newsletter'] = U('account/newsletter', '', 'SSL');

		$this->data['logged'] = $this->customer->isLogged();
		$this->render('module/account.tpl');
	}
}
?>