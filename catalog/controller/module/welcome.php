<?php  
class ControllerModuleWelcome extends Controller {
	protected function index($setting) {
		$this->language->load('module/welcome');
		
    	$this->data['heading_title'] = sprintf(L('welcome_heading_title'), C('config_name'));
    	
		$this->data['message'] = html_entity_decode($setting['description'][C('config_language_id')], ENT_QUOTES, 'UTF-8');
		
		$this->render('module/' . (!empty($setting['template']) ? $setting['template'] : 'welcome.tpl'));
	}
}
?>