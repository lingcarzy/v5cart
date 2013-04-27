<?php  
class ControllerModuleAffiliate extends Controller {
	protected function index() {
		$this->language->load('module/affiliate');		
		
		$this->data['heading_title'] = L('heading_title');
    	
		$this->data['text_register'] = L('text_register');
    	$this->data['text_login'] = L('text_login');
		$this->data['text_logout'] = L('text_logout');
		$this->data['text_forgotten'] = L('text_forgotten');	
		$this->data['text_account'] = L('text_account');
		$this->data['text_edit'] = L('text_edit');
		$this->data['text_password'] = L('text_password');
		$this->data['text_payment'] = L('text_payment');
		$this->data['text_tracking'] = L('text_tracking');
		$this->data['text_transaction'] = L('text_transaction');
		
    	$this->data['logged'] = $this->affiliate->isLogged();
			
		$this->data['register'] = U('affiliate/register', '', 'SSL');
    	$this->data['login'] = U('affiliate/login', '', 'SSL');
		$this->data['logout'] = U('affiliate/logout', '', 'SSL');
		$this->data['forgotten'] = U('affiliate/forgotten', '', 'SSL');
		$this->data['account'] = U('affiliate/account', '', 'SSL');
		$this->data['edit'] = U('affiliate/edit', '', 'SSL');
		$this->data['password'] = U('affiliate/password', '', 'SSL');
		$this->data['payment'] = U('affiliate/payment', '', 'SSL');
		$this->data['tracking'] = U('affiliate/tracking', '', 'SSL');
		$this->data['transaction'] = U('affiliate/transaction', '', 'SSL');
		
		$this->render('module/affiliate.tpl');
	}
}
?>