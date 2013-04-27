<?php

class ControllerSettingSeourl extends Controller {
	public function index() {
		$this->language->load('setting/seo_url');
		$this->document->setTitle(L('heading_title'));
		
		$this->data['success'] = $this->session->flashdata('success');
		
		$this->config->load('seo_url');
		$config_seo_url = C('config_seo_rules');
		
		$this->data['category_seo_rule'] = C('category_seo_rule');
		$this->data['product_seo_rule'] = C('product_seo_rule');
		$this->data['category_seo_rules'] = $config_seo_url['category'];
		$this->data['product_seo_rules'] = $config_seo_url['product'];
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('setting/seo_url.tpl');
	}
	
	public function save() {				
		if ($this->request->isPost()) {
			$this->language->load('setting/seo_url');
			
			M('setting/setting');
			$this->model_setting_setting->editSetting('seo_rule', $this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
		}
		$this->redirect(UA('setting/seo_url'));
	}
}