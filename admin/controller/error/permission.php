<?php    
class ControllerErrorPermission extends Controller {    
	public function index() { 
    	$this->language->load('error/permission');  
    	$this->document->setTitle(L('heading_title'));
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('error/permission.tpl');
  	}
}
?>