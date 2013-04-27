<?php
class ControllerAffiliateSuccess extends Controller {
	public function index() {
    	$this->language->load('affiliate/success');

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
        	'text'      => L('text_success'),
			'href'      => U('affiliate/success'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_message'] = sprintf(L('text_approval'), C('config_name'), U('page/contact'));

    	$this->data['button_continue'] = L('button_continue');

		$this->data['continue'] = U('affiliate/account', '', 'SSL');

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