<?php
class ControllerModuleCarousel extends Controller {

	public function index() {
		$this->language->load('module/carousel');

		$this->document->setTitle(L('module_name'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('carousel', $this->request->post);

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
			'href'      => UA('module/carousel'),
      		'separator' => ' :: '
   		);

		$this->data['modules'] = array();

		if (isset($this->request->post['carousel_module'])) {
			$this->data['modules'] = $this->request->post['carousel_module'];
		} elseif (C('carousel_module')) {
			$this->data['modules'] = C('carousel_module');
		}

		$this->data['templates'] = glob(DIR_CATALOG . 'view/theme/' . C('config_template') . '/template/module/carousel_*.tpl');
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

		$this->display('module/carousel.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/carousel')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (isset($this->request->post['carousel_module'])) {
			$error = array();
			foreach ($this->request->post['carousel_module'] as $key => $value) {
				if (!$value['width'] || !$value['height']) {
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