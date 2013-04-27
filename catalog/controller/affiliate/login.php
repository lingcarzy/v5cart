<?php
class ControllerAffiliateLogin extends Controller {
	
	public function index() {
		if ($this->affiliate->isLogged()) {
      		$this->redirect(U('affiliate/account', '', 'SSL'));
    	}

    	$this->language->load('affiliate/login');

    	$this->document->setTitle(L('heading_title'));

		M('affiliate/affiliate');

		if ($this->request->isPost() && isset($this->request->post['email']) && isset($this->request->post['password']) && $this->validate()) {
			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], C('config_url')) !== false || strpos($this->request->post['redirect'], C('config_ssl')) !== false)) {
				$this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->redirect(U('affiliate/account', '', 'SSL'));
			}
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_account'),
			'href'      => U('affiliate/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_login'),
			'href'      => U('affiliate/login', '', 'SSL'),
        	'separator' => L('text_separator')
      	);
		
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_description'] = sprintf(L('text_description'), C('config_name'), C('config_name'), C('config_commission') . '%');
		$this->data['text_new_affiliate'] = L('text_new_affiliate');
    	$this->data['text_register_account'] = L('text_register_account');
		$this->data['text_returning_affiliate'] = L('text_returning_affiliate');
		$this->data['text_i_am_returning_affiliate'] = L('text_i_am_returning_affiliate');
    	$this->data['text_forgotten'] = L('text_forgotten');

    	$this->data['entry_email'] = L('entry_email');
    	$this->data['entry_password'] = L('entry_password');

    	$this->data['button_continue'] = L('button_continue');
		$this->data['button_login'] = L('button_login');

		$this->data['action'] = U('affiliate/login', '', 'SSL');
		$this->data['register'] = U('affiliate/register', '', 'SSL');
		$this->data['forgotten'] = U('affiliate/forgotten', '', 'SSL');

		if (isset($this->request->post['redirect'])) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
      		$this->data['redirect'] = $this->session->data['redirect'];
			unset($this->session->data['redirect']);
    	} else {
			$this->data['redirect'] = '';
		}

   		$this->data['success'] = $this->session->flashdata('success');

		$this->data['email'] = P('email');
		$this->data['password'] = P('password');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('affiliate/login.tpl');
  	}

  	protected function validate() {
		if (!range_length($this->request->post['email'], 5, 96) 
			|| !range_length($this->request->post['password'], 4, 20)
			|| !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->request->post['email'])
		) {
			$this->setMessage('error_warning', L('error_login'));
			return false;
		}
		
    	if (!$this->affiliate->login($this->request->post['email'], $this->request->post['password'])) {
      		$this->setMessage('error_warning', L('error_login'));
			return false;
    	}

		$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($this->request->post['email']);

    	if ($affiliate_info && !$affiliate_info['approved']) {
      		$this->setMessage('error_warning', L('error_approved'));
			return false;
    	}
		
   		return true;
  	}
}
?>