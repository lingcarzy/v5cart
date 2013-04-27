<?php
class ControllerAccountNewsletter extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = U('account/newsletter', '', 'SSL');

	  		$this->redirect(U('account/login', '', 'SSL'));
    	}

		$this->language->load('account/newsletter');

		$this->document->setTitle(L('heading_title'));

		if ($this->request->isPost()) {
			M('account/customer');

			$this->model_account_customer->editNewsletter($this->request->post['newsletter']);

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
        	'text'      => L('text_newsletter'),
			'href'      => U('account/newsletter', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

    	$this->data['text_yes'] = L('text_yes');
		$this->data['text_no'] = L('text_no');

		$this->data['entry_newsletter'] = L('entry_newsletter');

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_back'] = L('button_back');

    	$this->data['action'] = U('account/newsletter', '', 'SSL');

		$this->data['newsletter'] = $this->customer->getNewsletter();

		$this->data['back'] = U('account/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/newsletter.tpl');
  	}
}
?>