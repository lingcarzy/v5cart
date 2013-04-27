<?php
class ControllerCommonForgotten extends Controller {
	
	public function index() {
		if ($this->user->isLogged()) {
			$this->redirect(UA('common/home'));
		}
		
		if (!C('config_password')) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		
		$this->language->load('common/forgotten');

		$this->document->setTitle(L('heading_title'));
		
		M('user/user');
		
		if ($this->request->isPost() && $this->validate()) {
			$this->language->load('mail/forgotten');
			
			$code = sha1(uniqid(mt_rand(), true));
			
			$this->model_user_user->editCode($this->request->post['email'], $code);
			
			$subject = sprintf(L('text_subject'), C('config_name'));
			
			$message  = sprintf(L('text_greeting'), C('config_name')) . "\n\n";
			$message .= sprintf(L('text_change'), C('config_name')) . "\n\n";
			$message .= $this->url->link('common/reset', 'code=' . $code, 'SSL') . "\n\n";
			$message .= sprintf(L('text_ip'), $this->request->server['REMOTE_ADDR']) . "\n\n";

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');				
			$mail->setTo($this->request->post['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender(C('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
			$this->session->data['success'] = L('text_success');

			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	); 
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_forgotten'),
			'href'      => $this->url->link('common/forgotten', '', 'SSL'),       	
        	'separator' => L('text_separator')
      	);
		
		$this->data['action'] = $this->url->link('common/forgotten', '', 'SSL'); 
		$this->data['cancel'] = $this->url->link('common/login', '', 'SSL');
    	
      	$this->data['email'] = P('email');
		
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->display('common/forgotten.tpl');
	}

	protected function validate() {
		if (!isset($this->request->post['email'])) {
			$this->setMessage('error_warning', L('error_email'));
			return false;
		} elseif (!$this->model_user_user->getTotalUsersByEmail($this->request->post['email'])) {
			$this->setMessage('error_warning', L('error_email'));
			return false;
		}
		return true;
	}
}
?>