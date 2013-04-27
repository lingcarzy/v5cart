<?php
class ControllerModuleBanner extends Controller {

	public function index() {
		$this->language->load('module/banner');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('banner', $this->request->post);

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
			'href'      => UA('module/banner'),
      		'separator' => ' :: '
   		);

		$this->data['modules'] = array();

		if (isset($this->request->post['banner_module'])) {
			$this->data['modules'] = $this->request->post['banner_module'];
		} elseif (C('banner_module')) {
			$this->data['modules'] = C('banner_module');
		}

		$this->data['templates'] = glob(DIR_CATALOG . 'view/theme/' . C('config_template') . '/template/module/banner_*.tpl');
		foreach ($this->data['templates'] as $i => $tpl) {
			$this->data['templates'][$i] = basename($tpl);
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		M('design/banner');
		$this->data['banners'] = $this->model_design_banner->getBanners();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('module/banner.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/banner')) {
			$this->setMessage('error_warning', L('error_permission'));
		}

		if (isset($this->request->post['banner_module'])) {
			$error = array();
			foreach ($this->request->post['banner_module'] as $key => $value) {
				if (!$value['width'] || !$value['height']) {
					$error[$key] = L('error_dimension');
				}
			}
			if ($error) {
				$this->setMessage('error_dimension', $error);
				return false;
			}
		}
		return true;
	}
}
?>