<?php
class ControllerShippingFedex extends Controller {

	public function index() {
		$this->language->load('shipping/fedex');

		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('fedex', $this->request->post);

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
			'href'      => UA('shipping/fedex'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['fedex_key'])) {
			$this->data['fedex_key'] = $this->request->post['fedex_key'];
		} else {
			$this->data['fedex_key'] = C('fedex_key');
		}

		if (isset($this->request->post['fedex_password'])) {
			$this->data['fedex_password'] = $this->request->post['fedex_password'];
		} else {
			$this->data['fedex_password'] = C('fedex_password');
		}

		if (isset($this->request->post['fedex_account'])) {
			$this->data['fedex_account'] = $this->request->post['fedex_account'];
		} else {
			$this->data['fedex_account'] = C('fedex_account');
		}

		if (isset($this->request->post['fedex_meter'])) {
			$this->data['fedex_meter'] = $this->request->post['fedex_meter'];
		} else {
			$this->data['fedex_meter'] = C('fedex_meter');
		}

		if (isset($this->request->post['fedex_postcode'])) {
			$this->data['fedex_postcode'] = $this->request->post['fedex_postcode'];
		} else {
			$this->data['fedex_postcode'] = C('fedex_postcode');
		}

		if (isset($this->request->post['fedex_test'])) {
			$this->data['fedex_test'] = $this->request->post['fedex_test'];
		} else {
			$this->data['fedex_test'] = C('fedex_test');
		}

		if (isset($this->request->post['fedex_service'])) {
			$this->data['fedex_service'] = $this->request->post['fedex_service'];
		} elseif ($this->config->has('fedex_service')) {
			$this->data['fedex_service'] = C('fedex_service');
		} else {
			$this->data['fedex_service'] = array();
		}

		$this->data['services'] = array();

		$this->data['services'][] = array(
			'text'  => L('text_europe_first_international_priority'),
			'value' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_1_day_freight'),
			'value' => 'FEDEX_1_DAY_FREIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_2_day'),
			'value' => 'FEDEX_2_DAY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_2_day_am'),
			'value' => 'FEDEX_2_DAY_AM'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_2_day_freight'),
			'value' => 'FEDEX_2_DAY_FREIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_3_day_freight'),
			'value' => 'FEDEX_3_DAY_FREIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_express_saver'),
			'value' => 'FEDEX_EXPRESS_SAVER'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_first_freight'),
			'value' => 'FEDEX_FIRST_FREIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_freight_economy'),
			'value' => 'FEDEX_FREIGHT_ECONOMY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_freight_priority'),
			'value' => 'FEDEX_FREIGHT_PRIORITY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_fedex_ground'),
			'value' => 'FEDEX_GROUND'
		);

		$this->data['services'][] = array(
			'text'  => L('text_first_overnight'),
			'value' => 'FIRST_OVERNIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_ground_home_delivery'),
			'value' => 'GROUND_HOME_DELIVERY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_international_economy'),
			'value' => 'INTERNATIONAL_ECONOMY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_international_economy_freight'),
			'value' => 'INTERNATIONAL_ECONOMY_FREIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_international_first'),
			'value' => 'INTERNATIONAL_FIRST'
		);

		$this->data['services'][] = array(
			'text'  => L('text_international_priority'),
			'value' => 'INTERNATIONAL_PRIORITY'
		);

		$this->data['services'][] = array(
			'text'  => L('text_international_priority_freight'),
			'value' => 'INTERNATIONAL_PRIORITY_FREIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_priority_overnight'),
			'value' => 'PRIORITY_OVERNIGHT'
		);

		$this->data['services'][] = array(
			'text'  => L('text_smart_post'),
			'value' => 'SMART_POST'
		);

		$this->data['services'][] = array(
			'text'  => L('text_standard_overnight'),
			'value' => 'STANDARD_OVERNIGHT'
		);

		if (isset($this->request->post['fedex_dropoff_type'])) {
			$this->data['fedex_dropoff_type'] = $this->request->post['fedex_dropoff_type'];
		} else {
			$this->data['fedex_dropoff_type'] = C('fedex_dropoff_type');
		}

		if (isset($this->request->post['fedex_packaging_type'])) {
			$this->data['fedex_packaging_type'] = $this->request->post['fedex_packaging_type'];
		} else {
			$this->data['fedex_packaging_type'] = C('fedex_packaging_type');
		}

		if (isset($this->request->post['fedex_rate_type'])) {
			$this->data['fedex_rate_type'] = $this->request->post['fedex_rate_type'];
		} else {
			$this->data['fedex_rate_type'] = C('fedex_rate_type');
		}

		if (isset($this->request->post['fedex_destination_type'])) {
			$this->data['fedex_destination_type'] = $this->request->post['fedex_destination_type'];
		} else {
			$this->data['fedex_destination_type'] = C('fedex_destination_type');
		}

		if (isset($this->request->post['fedex_display_time'])) {
			$this->data['fedex_display_time'] = $this->request->post['fedex_display_time'];
		} else {
			$this->data['fedex_display_time'] = C('fedex_display_time');
		}

		if (isset($this->request->post['fedex_display_weight'])) {
			$this->data['fedex_display_weight'] = $this->request->post['fedex_display_weight'];
		} else {
			$this->data['fedex_display_weight'] = C('fedex_display_weight');
		}

		if (isset($this->request->post['fedex_weight_class_id'])) {
			$this->data['fedex_weight_class_id'] = $this->request->post['fedex_weight_class_id'];
		} else {
			$this->data['fedex_weight_class_id'] = C('fedex_weight_class_id');
		}

		M('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['fedex_tax_class_id'])) {
			$this->data['fedex_tax_class_id'] = $this->request->post['fedex_tax_class_id'];
		} else {
			$this->data['fedex_tax_class_id'] = C('fedex_tax_class_id');
		}

		if (isset($this->request->post['fedex_geo_zone_id'])) {
			$this->data['fedex_geo_zone_id'] = $this->request->post['fedex_geo_zone_id'];
		} else {
			$this->data['fedex_geo_zone_id'] = C('fedex_geo_zone_id');
		}

		if (isset($this->request->post['fedex_status'])) {
			$this->data['fedex_status'] = $this->request->post['fedex_status'];
		} else {
			$this->data['fedex_status'] = C('fedex_status');
		}

		if (isset($this->request->post['fedex_sort_order'])) {
			$this->data['fedex_sort_order'] = $this->request->post['fedex_sort_order'];
		} else {
			$this->data['fedex_sort_order'] = C('fedex_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

 		$this->display('shipping/fedex.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/fedex')) {
				$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$this->load->library('form_validation', true);

		$this->form_validation->set_rules('fedex_key', '', 'required', L('error_key'));
		$this->form_validation->set_rules('fedex_password', '', 'required', L('error_password'));
		$this->form_validation->set_rules('fedex_account', '', 'required', L('error_account'));
		$this->form_validation->set_rules('fedex_meter', '', 'required', L('error_meter'));
		$this->form_validation->set_rules('fedex_postcode', '', 'required', L('error_postcode'));

		return $this->form_validation->run();
	}
}
?>