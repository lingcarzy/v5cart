<?php
class ControllerShippingCitylink extends Controller {

	public function index() {
		$this->language->load('shipping/citylink');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('citylink', $this->request->post);

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
			'href'      => UA('shipping/citylink'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['citylink_rate'])) {
			$this->data['citylink_rate'] = $this->request->post['citylink_rate'];
		} elseif (C('citylink_rate')) {
			$this->data['citylink_rate'] = C('citylink_rate');
		} else {
			$this->data['citylink_rate'] = '10:11.6,15:14.1,20:16.60,25:19.1,30:21.6,35:24.1,40:26.6,45:29.1,50:31.6,55:34.1,60:36.6,65:39.1,70:41.6,75:44.1,80:46.6,100:56.6,125:69.1,150:81.6,200:106.6';
		}

		if (isset($this->request->post['citylink_tax_class_id'])) {
			$this->data['citylink_tax_class_id'] = $this->request->post['citylink_tax_class_id'];
		} else {
			$this->data['citylink_tax_class_id'] = C('citylink_tax_class_id');
		}

		if (isset($this->request->post['citylink_geo_zone_id'])) {
			$this->data['citylink_geo_zone_id'] = $this->request->post['citylink_geo_zone_id'];
		} else {
			$this->data['citylink_geo_zone_id'] = C('citylink_geo_zone_id');
		}

		if (isset($this->request->post['citylink_status'])) {
			$this->data['citylink_status'] = $this->request->post['citylink_status'];
		} else {
			$this->data['citylink_status'] = C('citylink_status');
		}

		if (isset($this->request->post['citylink_sort_order'])) {
			$this->data['citylink_sort_order'] = $this->request->post['citylink_sort_order'];
		} else {
			$this->data['citylink_sort_order'] = C('citylink_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('shipping/citylink.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/citylink')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>