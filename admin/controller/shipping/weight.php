<?php
class ControllerShippingWeight extends Controller {

	public function index() {
		$this->language->load('shipping/weight');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('weight', $this->request->post);

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
			'href'      => UA('shipping/weight'),
      		'separator' => ' :: '
   		);

		M('localisation/geo_zone');
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();

		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = C('weight_' . $geo_zone['geo_zone_id'] . '_rate');
			}

			if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = C('weight_' . $geo_zone['geo_zone_id'] . '_status');
			}
		}

		$this->data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['weight_tax_class_id'])) {
			$this->data['weight_tax_class_id'] = $this->request->post['weight_tax_class_id'];
		} else {
			$this->data['weight_tax_class_id'] = C('weight_tax_class_id');
		}

		if (isset($this->request->post['weight_status'])) {
			$this->data['weight_status'] = $this->request->post['weight_status'];
		} else {
			$this->data['weight_status'] = C('weight_status');
		}

		if (isset($this->request->post['weight_sort_order'])) {
			$this->data['weight_sort_order'] = $this->request->post['weight_sort_order'];
		} else {
			$this->data['weight_sort_order'] = C('weight_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('shipping/weight.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/weight')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>