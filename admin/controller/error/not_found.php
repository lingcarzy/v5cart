<?php
class ControllerErrorNotFound extends Controller {
	public function index() {
    	$this->language->load('error/not_found');

    	$this->document->setTitle(L('heading_title'));

    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_not_found'] = L('text_not_found');

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('error/not_found.tpl');
  	}
}
?>