<?php 
class ControllerPageContact extends Controller {
		
  	public function index() {
		$this->language->load('page/contact');

    	$this->document->setTitle(L('heading_title'));  
	 
    	if ($this->request->isPost() && $this->validate()) {
			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');				
			$mail->setTo(C('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(html_entity_decode(sprintf(L('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
	  		$mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
      		$mail->send();

	  		$this->redirect(U('page/contact/success'));
    	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('page/contact'),
        	'separator' => L('text_separator')
      	);
    	
		$this->data['heading_title'] = L('heading_title');

    	$this->data['text_location'] = L('text_location');
		$this->data['text_contact'] = L('text_contact');
		$this->data['text_address'] = L('text_address');
    	$this->data['text_telephone'] = L('text_telephone');
    	$this->data['text_fax'] = L('text_fax');

    	$this->data['entry_name'] = L('entry_name');
    	$this->data['entry_email'] = L('entry_email');
    	$this->data['entry_enquiry'] = L('entry_enquiry');
		$this->data['entry_captcha'] = L('entry_captcha');
		
		$this->data['button_continue'] = L('button_continue');
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = $this->customer->getEmail();
		}
		
		$this->data['enquiry'] = P('enquiry');
		$this->data['captcha'] = P('captcha');
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		
 		$this->display('page/contact.tpl');		
  	}

  	public function success() {
		$this->language->load('page/contact');

		$this->document->setTitle(L('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('page/contact'),
        	'separator' => L('text_separator')
      	);	
		
    	$this->data['heading_title'] = L('heading_title');
    	$this->data['text_message'] = L('text_message');
		
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
	
  	protected function validate() {
		$error = false;
    	if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
      		$this->setMessage('error_name', L('error_name'));
			$error = true;
    	}

    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->setMessage('error_email', L('error_email'));
			$error = true;
    	}

    	if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
      		$this->setMessage('error_enquiry', L('error_enquiry'));
			$error = true;
    	}

    	if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
      		$this->setMessage('captcha', L('error_captcha'));
			$error = true;
    	}
		return !$error;
  	}

	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}	
}
?>
