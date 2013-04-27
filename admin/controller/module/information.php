<?php
class ControllerModuleInformation extends Controller {

	public function index() {
		$this->language->load('module/information');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('information', $this->request->post);

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
			'href'      => UA('module/information'),
      		'separator' => ' :: '
   		);

		$this->data['modules'] = array();

		if (isset($this->request->post['information_module'])) {
			$this->data['modules'] = $this->request->post['information_module'];
		} elseif (C('information_module')) {
			$this->data['modules'] = C('information_module');
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('module/information.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/information')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>