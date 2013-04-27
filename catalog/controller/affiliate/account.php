<?php
class ControllerAffiliateAccount extends Controller {
	public function index() {
		if (!$this->affiliate->isLogged()) {
	  		$this->session->data['redirect'] = U('affiliate/account', '', 'SSL');
	  		$this->redirect(U('affiliate/login', '', 'SSL'));
    	}

		$this->language->load('affiliate/account');

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

		$this->document->setTitle(L('heading_title'));

    	$this->data['heading_title'] = L('heading_title');

    	$this->data['text_my_account'] = L('text_my_account');
    	$this->data['text_my_tracking'] = L('text_my_tracking');
		$this->data['text_my_transactions'] = L('text_my_transactions');
		$this->data['text_edit'] = L('text_edit');
		$this->data['text_password'] = L('text_password');
		$this->data['text_payment'] = L('text_payment');
		$this->data['text_tracking'] = L('text_tracking');
		$this->data['text_transaction'] = L('text_transaction');

		$this->data['success'] = $this->session->flashdata('success');

    	$this->data['edit'] = U('affiliate/edit', '', 'SSL');
		$this->data['password'] = U('affiliate/password', '', 'SSL');
		$this->data['payment'] = U('affiliate/payment', '', 'SSL');
		$this->data['tracking'] = U('affiliate/tracking', '', 'SSL');
    	$this->data['transaction'] = U('affiliate/transaction', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('affiliate/account.tpl');
  	}
}
?>