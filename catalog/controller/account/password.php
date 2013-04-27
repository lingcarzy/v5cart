<?php
class ControllerAccountPassword extends Controller {
	     
  	public function index() {	
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = U('account/password', '', 'SSL');
      		$this->redirect(U('account/login', '', 'SSL'));
    	}

		$this->language->load('account/password');

    	$this->document->setTitle(L('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
    	if ($this->request->isPost() && $this->validate()) {
			M('account/customer');
			
			$this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['password']);
 
      		$this->session->set_flashdata('success', L('text_success'));	  
	  		$this->redirect(U('account/account', '', 'SSL'));
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
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('account/password', '', 'SSL'),
        	'separator' => L('text_separator')
      	);		
	
		$this->data['heading_title'] = L('heading_title');

    	$this->data['text_password'] = L('text_password');

    	$this->data['entry_password'] = L('entry_password');
    	$this->data['entry_confirm'] = L('entry_confirm');

    	$this->data['button_continue'] = L('button_continue');
    	$this->data['button_back'] = L('button_back');
		
    	$this->data['action'] = U('account/password', '', 'SSL');
		
		$this->data['password'] = P('password');
		$this->data['confirm'] = P('confirm');

    	$this->data['back'] = U('account/account', '', 'SSL');
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->display('account/password.tpl');
  	}
  
  	protected function validate() {
		$p = true;
		
    	if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
      		$this->setMessage('error_password', L('error_password'));
			$p = false;
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->setMessage('error_confirm', L('error_confirm'));
			$p = false;
    	}  
		return $p;
  	}
}
?>
