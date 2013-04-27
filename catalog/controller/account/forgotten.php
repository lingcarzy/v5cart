<?php
class ControllerAccountForgotten extends Controller {

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect(U('account/account', '', 'SSL'));
		}

		$this->language->load('account/forgotten');

		$this->document->setTitle(L('heading_title'));

		M('account/customer');

		if ($this->request->isPost() && $this->validate()) {
			$this->language->load('mail/forgotten');

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

			$this->model_account_customer->editPassword($this->request->post['email'], $password);

			$subject = sprintf(L('text_subject'), C('config_name'));

			$message  = sprintf(L('text_greeting'), C('config_name')) . "\n\n";
			$message .= L('text_password') . "\n\n";
			$message .= $password;

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

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(U('account/login', '', 'SSL'));
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
        	'text'      => L('text_forgotten'),
			'href'      => U('account/forgotten', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_your_email'] = L('text_your_email');
		$this->data['text_email'] = L('text_email');

		$this->data['entry_email'] = L('entry_email');

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_back'] = L('button_back');

		$this->data['action'] = U('account/forgotten', '', 'SSL');
		$this->data['back'] = U('account/login', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/forgotten.tpl');
	}

	protected function validate() {
		if (!isset($this->request->post['email']) || !$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->setMessage('error_warning', L('error_email'));
			return false;
		}
		return true;
	}
}
?>