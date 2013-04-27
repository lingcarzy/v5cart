<?php
class ControllerModuleSlideshow extends Controller {

	public function index() {
		$this->language->load('module/slideshow');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('slideshow', $this->request->post);

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
			'href'      => UA('module/slideshow'),
      		'separator' => ' :: '
   		);

		$this->data['modules'] = array();

		if (isset($this->request->post['slideshow_module'])) {
			$this->data['modules'] = $this->request->post['slideshow_module'];
		} elseif (C('slideshow_module')) {
			$this->data['modules'] = C('slideshow_module');
		}

		$this->data['templates'] = glob(DIR_CATALOG . 'view/theme/' . C('config_template') . '/template/module/slideshow_*.tpl');
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
		$this->display('module/slideshow.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/slideshow')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (isset($this->request->post['slideshow_module'])) {
			$error = array();
			foreach ($this->request->post['slideshow_module'] as $key => $value) {
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