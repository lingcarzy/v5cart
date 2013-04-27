<?php
class ControllerModuleWelcome extends Controller {

	public function index() {
		$this->language->load('module/welcome');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('welcome', $this->request->post);

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
			'href'      => UA('module/welcome'),
      		'separator' => ' :: '
   		);

		$this->data['modules'] = array();

		if (isset($this->request->post['welcome_module'])) {
			$this->data['modules'] = $this->request->post['welcome_module'];
		} elseif (C('welcome_module')) {
			$this->data['modules'] = C('welcome_module');
		}

		$this->data['templates'] = glob(DIR_CATALOG . 'view/theme/' . C('config_template') . '/template/module/welcome_*.tpl');
		foreach ($this->data['templates'] as $i => $tpl) {
			$this->data['templates'][$i] = basename($tpl);
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->data['languages'] = C('cache_language');

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('module/welcome.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/welcome')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>