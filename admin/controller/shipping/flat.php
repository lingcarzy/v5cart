<?php
class ControllerShippingFlat extends Controller {

	public function index() {
		$this->language->load('shipping/flat');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('flat', $this->request->post);

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
			'href'      => UA('shipping/flat'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['flat_cost'])) {
			$this->data['flat_cost'] = $this->request->post['flat_cost'];
		} else {
			$this->data['flat_cost'] = C('flat_cost');
		}

		if (isset($this->request->post['flat_tax_class_id'])) {
			$this->data['flat_tax_class_id'] = $this->request->post['flat_tax_class_id'];
		} else {
			$this->data['flat_tax_class_id'] = C('flat_tax_class_id');
		}

		if (isset($this->request->post['flat_geo_zone_id'])) {
			$this->data['flat_geo_zone_id'] = $this->request->post['flat_geo_zone_id'];
		} else {
			$this->data['flat_geo_zone_id'] = C('flat_geo_zone_id');
		}

		if (isset($this->request->post['flat_status'])) {
			$this->data['flat_status'] = $this->request->post['flat_status'];
		} else {
			$this->data['flat_status'] = C('flat_status');
		}

		if (isset($this->request->post['flat_sort_order'])) {
			$this->data['flat_sort_order'] = $this->request->post['flat_sort_order'];
		} else {
			$this->data['flat_sort_order'] = C('flat_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('shipping/flat.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/flat')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		return true;
	}
}
?>