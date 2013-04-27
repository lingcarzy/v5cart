<?php
class ControllerSettingCache extends Controller {
	
	public function index() {
		$this->language->load('setting/cache');
		$this->document->setTitle(L('heading_title'));
		
		$this->data['success'] = $this->session->flashdata('success');
		
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('setting/cache.tpl');
	}
	
	public function update() {
		$this->language->load('setting/cache');
		
		M('localisation/country');
		$this->model_localisation_country->cache();
		
		M('localisation/zone');
		$this->model_localisation_zone->cache();
		
		M('catalog/category');
		//call twice
		$this->model_catalog_category->cache();
		$this->model_catalog_category->cache();
		
		$this->load->helper('cache');
		cache_all();
		
		//table cache
		$files = glob(DIR_CACHE . 'table/*');		
		if ($files) {
			foreach ($files as $file) {
				unlink($file);
			}
		}
		
		$this->session->set_flashdata('success', L('success_sys'));		
		$this->redirect(UA('setting/cache'));
	}
	
	public function clear() {
		$this->language->load('setting/cache');
		
		$files = glob(DIR_CACHE . 'module/*');		
		if ($files) {
			foreach ($files as $file) {
				unlink($file);
			}
		}
		$files = glob(DIR_CACHE . 'data/*');		
		if ($files) {
			foreach ($files as $file) {
				unlink($file);
			}
		}
		
		$this->session->set_flashdata('success', L('success_data'));	
		$this->redirect(UA('setting/cache'));
	}
	
	public function url() {
		$this->language->load('setting/cache');
		
		M('catalog/product', 'product_model');
		$offset = $this->request->get('offset', 0);
		$data = array(
			'start' => $offset,
			'limit' => 100,
			'sort' => 'p.product_id',
		);		
		$products = $this->product_model->getProducts($data, $total);
		if ($products) {
			foreach ($products as $product) {
				$this->product_model->updateLink($product['product_id'], $product['cate_id'], $product['seo_url']);
			}
			$this->document->setTitle(L('heading_title'));
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$offset += 100;
			$this->data['forward'] = UA('setting/cache/url', "offset=$offset");
			$this->display('setting/msg.tpl');
		}
		else {
			$this->session->set_flashdata('success', L('success_url'));
			$this->redirect(UA('setting/cache'));
		}
	}
}