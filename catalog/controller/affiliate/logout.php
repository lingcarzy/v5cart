<?php
class ControllerAffiliateLogout extends Controller {
	public function index() {
    	if ($this->affiliate->isLogged()) {
      		$this->affiliate->logout();
      		$this->redirect(U('affiliate/logout', '', 'SSL'));
    	}

    	$this->language->load('affiliate/logout');

		$this->document->setTitle(L('heading_title'));

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
        	'text'      => L('text_logout'),
			'href'      => U('affiliate/logout', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

    	$this->data['text_message'] = L('text_message');

    	$this->data['button_continue'] = L('button_continue');

    	$this->data['continue'] = HTTP_SERVER;

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