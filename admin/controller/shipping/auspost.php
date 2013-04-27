<?php
class ControllerShippingAusPost extends Controller {
	
	public function index() {   
		$this->language->load('shipping/auspost');
		
		$this->document->setTitle(L('heading_title'));
		
		M('setting/setting');
		
		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('auspost', $this->request->post);             
			
			$this->session->set_flashdata('success', L('text_success'));			
			$this->redirect(UA('extension/shipping'));
		}
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_shipping'),
			'href'      => UA('extension/shipping'),
      		'separator' => ' :: '
		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('shipping/auspost'),
      		'separator' => ' :: '
   		);
		
		if ($this->request->isPost()) {
			$this->data['auspost_postcode'] = $this->request->post['auspost_postcode'];
			$this->data['auspost_standard'] = $this->request->post['auspost_standard'];
			$this->data['auspost_express'] = $this->request->post['auspost_express'];
			$this->data['auspost_display_time'] = $this->request->post['auspost_display_time'];
			$this->data['auspost_weight_class_id'] = $this->request->post['auspost_weight_class_id'];
			$this->data['auspost_tax_class_id'] = $this->request->post['auspost_tax_class_id'];
			$this->data['auspost_geo_zone_id'] = $this->request->post['auspost_geo_zone_id'];
			$this->data['auspost_status'] = $this->request->post['auspost_status'];
			$this->data['auspost_sort_order'] = $this->request->post['auspost_sort_order'];
		} else {
			$this->data['auspost_postcode'] = C('auspost_postcode');
			$this->data['auspost_standard'] = C('auspost_standard');
			$this->data['auspost_express'] = C('auspost_express');
			$this->data['auspost_display_time'] = C('auspost_display_time');
			$this->data['auspost_weight_class_id'] = C('auspost_weight_class_id');
			$this->data['auspost_tax_class_id'] = C('auspost_tax_class_id');
			$this->data['auspost_geo_zone_id'] = C('auspost_geo_zone_id');
			$this->data['auspost_status'] = C('auspost_status');
			$this->data['auspost_sort_order'] = C('auspost_sort_order');
		}
		
		M('localisation/weight_class');		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
		M('localisation/tax_class');		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		M('localisation/geo_zone');		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->children = array(
			'common/header',        
			'common/footer' 
		);
		
		$this->display('shipping/auspost.tpl');
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/auspost')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		
		if (!preg_match('/^[0-9]{4}$/', $this->request->post['auspost_postcode'])){
			$this->setMessage('error_postcode', L('error_postcode'));
			return false;
		}
	
		return true;
	}
}
?>