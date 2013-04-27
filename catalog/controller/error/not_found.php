<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {		
		$this->language->load('error/not_found');
		
		$this->document->setTitle(L('heading_title'));
		
		$this->data['breadcrumbs'] = array();
 
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);		
		
		$this->data['heading_title'] = L('heading_title');		
		$this->data['text_error'] = L('text_error');
		$this->data['continue'] = HTTP_SERVER;
		$this->data['button_continue'] = L('button_continue');
		
		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');		
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('error/not_found.tpl');
  	}
}
?>