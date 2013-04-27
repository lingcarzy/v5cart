<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = U('account/account', '', 'SSL');
			
	  		$this->redirect(U('account/login', '', 'SSL'));
    	} 
	
		$this->language->load('account/account');

		$this->document->setTitle(L('heading_title'));
		
		$this->data['heading_title'] = L('heading_title');

    	$this->data['text_my_account'] = L('text_my_account');
		$this->data['text_my_orders'] = L('text_my_orders');
		$this->data['text_my_newsletter'] = L('text_my_newsletter');
    	$this->data['text_edit'] = L('text_edit');
    	$this->data['text_password'] = L('text_password');
    	$this->data['text_address'] = L('text_address');
		$this->data['text_wishlist'] = L('text_wishlist');
    	$this->data['text_order'] = L('text_order');
    	$this->data['text_download'] = L('text_download');
		$this->data['text_reward'] = L('text_reward');
		$this->data['text_return'] = L('text_return');
		$this->data['text_transaction'] = L('text_transaction');
		$this->data['text_newsletter'] = L('text_newsletter');

		$this->data['edit'] = U('account/edit', '', 'SSL');
    	$this->data['password'] = U('account/password', '', 'SSL');
		$this->data['address'] = U('account/address', '', 'SSL');
		$this->data['wishlist'] = U('account/wishlist');
    	$this->data['order'] = U('account/order', '', 'SSL');
    	$this->data['download'] = U('account/download', '', 'SSL');
		$this->data['return'] = U('account/return', '', 'SSL');
		$this->data['transaction'] = U('account/transaction', '', 'SSL');
		$this->data['newsletter'] = U('account/newsletter', '', 'SSL');
		if (C('reward_status')) {
			$this->data['reward'] = U('account/reward', '', 'SSL');
		} else {
			$this->data['reward'] = '';
		}
		
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
		
		$this->data['success'] = $this->session->flashdata('success');
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'		
		);

		$this->display('account/account.tpl');
  	}
}
?>