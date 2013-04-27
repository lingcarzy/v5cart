<?php
class ControllerModuleLatest extends Controller {

	public function index() {
		$this->language->load('module/latest');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('latest', $this->request->post);

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
			'href'      => UA('module/latest'),
      		'separator' => ' :: '
   		);

		$this->data['modules'] = array();

		if (isset($this->request->post['latest_module'])) {
			$this->data['modules'] = $this->request->post['latest_module'];
		} elseif (C('latest_module')) {
			$this->data['modules'] = C('latest_module');
		}

		$this->data['templates'] = glob(DIR_CATALOG . 'view/theme/' . C('config_template') . '/template/module/list_*.tpl');
		foreach ($this->data['templates'] as $i => $tpl) {
			$this->data['templates'][$i] = basename($tpl);
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('module/latest.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/latest')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (isset($this->request->post['latest_module'])) {
			$error = array();
			foreach ($this->request->post['latest_module'] as $key => $value) {
				if (!$value['image_width'] || !$value['image_height']) {
					$error[$key] = L('error_image');
				}
			}
			if ($error) {
				$this->setMessage('error_image', $error);
				return false;
			}
		}
		return true;
	}
}
?>