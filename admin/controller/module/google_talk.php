<?php
class ControllerModuleGoogleTalk extends Controller {

	public function index() {
		$this->language->load('module/google_talk');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('google_talk', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/module'));
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_module'),
			'href'      => UA('extension/module'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('module_name'),
			'href'      => UA('module/google_talk'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['google_talk_code'])) {
			$this->data['google_talk_code'] = $this->request->post['google_talk_code'];
		} else {
			$this->data['google_talk_code'] = C('google_talk_code');
		}

		$this->data['modules'] = array();
		if (isset($this->request->post['google_talk_module'])) {
			$this->data['modules'] = $this->request->post['google_talk_module'];
		} elseif (C('google_talk_module')) {
			$this->data['modules'] = C('google_talk_module');
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('module/google_talk.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_talk')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!$this->request->post['google_talk_code']) {
			$this->setMessage('error_code', L('error_code'));
			return false;
		}
		else return true;
	}
}
?>