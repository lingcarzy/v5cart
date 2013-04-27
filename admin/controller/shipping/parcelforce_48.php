<?php
class ControllerShippingParcelforce48 extends Controller {

	public function index() {
		$this->language->load('shipping/parcelforce_48');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('parcelforce_48', $this->request->post);

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
			'href'      => UA('shipping/parcelforce_48'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['parcelforce_48_rate'])) {
			$this->data['parcelforce_48_rate'] = $this->request->post['parcelforce_48_rate'];
		} elseif (C('parcelforce_48_rate')) {
			$this->data['parcelforce_48_rate'] = C('parcelforce_48_rate');
		} else {
			$this->data['parcelforce_48_rate'] = '10:15.99,12:19.99,14:20.99,16:21.99,18:21.99,20:21.99,22:26.99,24:30.99,26:34.99,28:38.99,30:42.99,35:52.99,40:62.99,45:72.99,50:82.99,55:92.99,60:102.99,65:112.99,70:122.99,75:132.99,80:142.99,85:152.99,90:162.99,95:172.99,100:182.99';
		}

		if (isset($this->request->post['parcelforce_48_insurance'])) {
			$this->data['parcelforce_48_insurance'] = $this->request->post['parcelforce_48_insurance'];
		} elseif (C('parcelforce_48_insurance')) {
			$this->data['parcelforce_48_insurance'] = C('parcelforce_48_insurance');
		} else {
			$this->data['parcelforce_48_insurance'] = '150:0,500:12,1000:24,1500:36,2000:48,2500:60';
		}

		if (isset($this->request->post['parcelforce_48_display_weight'])) {
			$this->data['parcelforce_48_display_weight'] = $this->request->post['parcelforce_48_display_weight'];
		} else {
			$this->data['parcelforce_48_display_weight'] = C('parcelforce_48_display_weight');
		}

		if (isset($this->request->post['parcelforce_48_display_insurance'])) {
			$this->data['parcelforce_48_display_insurance'] = $this->request->post['parcelforce_48_display_insurance'];
		} else {
			$this->data['parcelforce_48_display_insurance'] = C('parcelforce_48_display_insurance');
		}

		if (isset($this->request->post['parcelforce_48_display_time'])) {
			$this->data['parcelforce_48_display_time'] = $this->request->post['parcelforce_48_display_time'];
		} else {
			$this->data['parcelforce_48_display_time'] = C('parcelforce_48_display_time');
		}

		if (isset($this->request->post['parcelforce_48_tax_class_id'])) {
			$this->data['parcelforce_48_tax_class_id'] = $this->request->post['parcelforce_48_tax_class_id'];
		} else {
			$this->data['parcelforce_48_tax_class_id'] = C('parcelforce_48_tax_class_id');
		}

		if (isset($this->request->post['parcelforce_48_geo_zone_id'])) {
			$this->data['parcelforce_48_geo_zone_id'] = $this->request->post['parcelforce_48_geo_zone_id'];
		} else {
			$this->data['parcelforce_48_geo_zone_id'] = C('parcelforce_48_geo_zone_id');
		}

		if (isset($this->request->post['parcelforce_48_status'])) {
			$this->data['parcelforce_48_status'] = $this->request->post['parcelforce_48_status'];
		} else {
			$this->data['parcelforce_48_status'] = C('parcelforce_48_status');
		}

		if (isset($this->request->post['parcelforce_48_sort_order'])) {
			$this->data['parcelforce_48_sort_order'] = $this->request->post['parcelforce_48_sort_order'];
		} else {
			$this->data['parcelforce_48_sort_order'] = C('parcelforce_48_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('shipping/parcelforce_48.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/parcelforce_48')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		return true;
	}
}
?>