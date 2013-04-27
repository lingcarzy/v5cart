<?php  
class ControllerModuleLivechat extends Controller {
	public function index() {
		$setting = C('livechat_setting');
		if (!$setting || !$setting['enabled']) return;
		
		$this->data['setting'] = $setting;
		M('tool/livechat');
		$this->data['livechat_code'] = $this->model_tool_livechat->getCode();
		$this->render('module/livechat.tpl');
	}
}