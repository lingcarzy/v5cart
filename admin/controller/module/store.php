<?php
class ControllerModuleStore extends Controller {

	public function index() {
		$this->language->load('module/store');
		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('store', $this->request->post);

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
			'href'      => UA('module/store'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['store_admin'])) {
			$this->data['store_admin'] = $this->request->post['store_admin'];
		} else {
			$this->data['store_admin'] = C('store_admin');
		}

		$this->data['modules'] = array();

		if (isset($this->request->post['store_module'])) {
			$this->data['modules'] = $this->request->post['store_module'];
		} elseif (C('store_module')) {
			$this->data['modules'] = C('store_module');
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('module/store.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/store')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>