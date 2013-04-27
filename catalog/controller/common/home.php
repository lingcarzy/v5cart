<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setDescription(C('config_meta_description'));
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('common/home.tpl');
	}
}
?>