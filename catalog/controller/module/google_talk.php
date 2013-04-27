<?php  
class ControllerModuleGoogleTalk extends Controller {
	protected function index() {
		$this->language->load('module/google_talk');
		$this->data['heading_title'] = L('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode(C('google_talk_code')));
		} else {
			$this->data['code'] = html_entity_decode(C('google_talk_code'));
		}
		
		$this->render('module/google_talk.tpl');
	}
}
?>