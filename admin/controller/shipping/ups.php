<?php
class ControllerShippingUPS extends Controller {

	public function index() {
		$this->language->load('shipping/ups');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('ups', $this->request->post);

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
			'href'      => UA('shipping/ups'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['ups_key'])) {
			$this->data['ups_key'] = $this->request->post['ups_key'];
		} else {
			$this->data['ups_key'] = C('ups_key');
		}

		if (isset($this->request->post['ups_username'])) {
			$this->data['ups_username'] = $this->request->post['ups_username'];
		} else {
			$this->data['ups_username'] = C('ups_username');
		}

		if (isset($this->request->post['ups_password'])) {
			$this->data['ups_password'] = $this->request->post['ups_password'];
		} else {
			$this->data['ups_password'] = C('ups_password');
		}

		if (isset($this->request->post['ups_pickup'])) {
			$this->data['ups_pickup'] = $this->request->post['ups_pickup'];
		} else {
			$this->data['ups_pickup'] = C('ups_pickup');
		}

		$this->data['pickups'] = array();

		$this->data['pickups'][] = array(
			'value' => '01',
			'text'  => L('text_daily_pickup')
		);

		$this->data['pickups'][] = array(
			'value' => '03',
			'text'  => L('text_customer_counter')
		);

		$this->data['pickups'][] = array(
			'value' => '06',
			'text'  => L('text_one_time_pickup')
		);

		$this->data['pickups'][] = array(
			'value' => '07',
			'text'  => L('text_on_call_air_pickup')
		);

		$this->data['pickups'][] = array(
			'value' => '19',
			'text'  => L('text_letter_center')
		);

		$this->data['pickups'][] = array(
			'value' => '20',
			'text'  => L('text_air_service_center')
		);

		$this->data['pickups'][] = array(
			'value' => '11',
			'text'  => L('text_suggested_retail_rates')
		);

		if (isset($this->request->post['ups_packaging'])) {
			$this->data['ups_packaging'] = $this->request->post['ups_packaging'];
		} else {
			$this->data['ups_packaging'] = C('ups_packaging');
		}

		$this->data['packages'] = array();

		$this->data['packages'][] = array(
			'value' => '02',
			'text'  => L('text_package')
		);

		$this->data['packages'][] = array(
			'value' => '01',
			'text'  => L('text_ups_letter')
		);

		$this->data['packages'][] = array(
			'value' => '03',
			'text'  => L('text_ups_tube')
		);

		$this->data['packages'][] = array(
			'value' => '04',
			'text'  => L('text_ups_pak')
		);

		$this->data['packages'][] = array(
			'value' => '21',
			'text'  => L('text_ups_express_box')
		);

		$this->data['packages'][] = array(
			'value' => '24',
			'text'  => L('text_ups_25kg_box')
		);

		$this->data['packages'][] = array(
			'value' => '25',
			'text'  => L('text_ups_10kg_box')
		);

		if (isset($this->request->post['ups_classification'])) {
			$this->data['ups_classification'] = $this->request->post['ups_classification'];
		} else {
			$this->data['ups_classification'] = C('ups_classification');
		}

		$this->data['classifications'][] = array(
			'value' => '01',
			'text'  => '01'
		);

		$this->data['classifications'][] = array(
			'value' => '03',
			'text'  => '03'
		);

		$this->data['classifications'][] = array(
			'value' => '04',
			'text'  => '04'
		);

		if (isset($this->request->post['ups_origin'])) {
			$this->data['ups_origin'] = $this->request->post['ups_origin'];
		} else {
			$this->data['ups_origin'] = C('ups_origin');
		}

		$this->data['origins'] = array();

		$this->data['origins'][] = array(
			'value' => 'US',
			'text'  => L('text_us')
		);

		$this->data['origins'][] = array(
			'value' => 'CA',
			'text'  => L('text_ca')
		);

		$this->data['origins'][] = array(
			'value' => 'EU',
			'text'  => L('text_eu')
		);

		$this->data['origins'][] = array(
			'value' => 'PR',
			'text'  => L('text_pr')
		);

		$this->data['origins'][] = array(
			'value' => 'MX',
			'text'  => L('text_mx')
		);

		$this->data['origins'][] = array(
			'value' => 'other',
			'text'  => L('text_other')
		);

		if (isset($this->request->post['ups_city'])) {
			$this->data['ups_city'] = $this->request->post['ups_city'];
		} else {
			$this->data['ups_city'] = C('ups_city');
		}

		if (isset($this->request->post['ups_state'])) {
			$this->data['ups_state'] = $this->request->post['ups_state'];
		} else {
			$this->data['ups_state'] = C('ups_state');
		}

		if (isset($this->request->post['ups_country'])) {
			$this->data['ups_country'] = $this->request->post['ups_country'];
		} else {
			$this->data['ups_country'] = C('ups_country');
		}

		if (isset($this->request->post['ups_postcode'])) {
			$this->data['ups_postcode'] = $this->request->post['ups_postcode'];
		} else {
			$this->data['ups_postcode'] = C('ups_postcode');
		}

		if (isset($this->request->post['ups_test'])) {
			$this->data['ups_test'] = $this->request->post['ups_test'];
		} else {
			$this->data['ups_test'] = C('ups_test');
		}

		if (isset($this->request->post['ups_quote_type'])) {
			$this->data['ups_quote_type'] = $this->request->post['ups_quote_type'];
		} else {
			$this->data['ups_quote_type'] = C('ups_quote_type');
		}

		$this->data['quote_types'] = array();

		$this->data['quote_types'][] = array(
			'value' => 'residential',
			'text'  => L('text_residential')
		);

		$this->data['quote_types'][] = array(
			'value' => 'commercial',
			'text'  => L('text_commercial')
		);

		// US
		if (isset($this->request->post['ups_us_01'])) {
			$this->data['ups_us_01'] = $this->request->post['ups_us_01'];
		} else {
			$this->data['ups_us_01'] = C('ups_us_01');
		}

		if (isset($this->request->post['ups_us_02'])) {
			$this->data['ups_us_02'] = $this->request->post['ups_us_02'];
		} else {
			$this->data['ups_us_02'] = C('ups_us_02');
		}

		if (isset($this->request->post['ups_us_03'])) {
			$this->data['ups_us_03'] = $this->request->post['ups_us_03'];
		} else {
			$this->data['ups_us_03'] = C('ups_us_03');
		}

		if (isset($this->request->post['ups_us_07'])) {
			$this->data['ups_us_07'] = $this->request->post['ups_us_07'];
		} else {
			$this->data['ups_us_07'] = C('ups_us_07');
		}

		if (isset($this->request->post['ups_us_08'])) {
			$this->data['ups_us_08'] = $this->request->post['ups_us_08'];
		} else {
			$this->data['ups_us_08'] = C('ups_us_08');
		}

		if (isset($this->request->post['ups_us_11'])) {
			$this->data['ups_us_11'] = $this->request->post['ups_us_11'];
		} else {
			$this->data['ups_us_11'] = C('ups_us_11');
		}

		if (isset($this->request->post['ups_us_12'])) {
			$this->data['ups_us_12'] = $this->request->post['ups_us_12'];
		} else {
			$this->data['ups_us_12'] = C('ups_us_12');
		}

		if (isset($this->request->post['ups_us_13'])) {
			$this->data['ups_us_13'] = $this->request->post['ups_us_13'];
		} else {
			$this->data['ups_us_13'] = C('ups_us_13');
		}

		if (isset($this->request->post['ups_us_14'])) {
			$this->data['ups_us_14'] = $this->request->post['ups_us_14'];
		} else {
			$this->data['ups_us_14'] = C('ups_us_14');
		}

		if (isset($this->request->post['ups_us_54'])) {
			$this->data['ups_us_54'] = $this->request->post['ups_us_54'];
		} else {
			$this->data['ups_us_54'] = C('ups_us_54');
		}

		if (isset($this->request->post['ups_us_59'])) {
			$this->data['ups_us_59'] = $this->request->post['ups_us_59'];
		} else {
			$this->data['ups_us_59'] = C('ups_us_59');
		}

		if (isset($this->request->post['ups_us_65'])) {
			$this->data['ups_us_65'] = $this->request->post['ups_us_65'];
		} else {
			$this->data['ups_us_65'] = C('ups_us_65');
		}

		// Puerto Rico
		if (isset($this->request->post['ups_pr_01'])) {
			$this->data['ups_pr_01'] = $this->request->post['ups_pr_01'];
		} else {
			$this->data['ups_pr_01'] = C('ups_pr_01');
		}

		if (isset($this->request->post['ups_pr_02'])) {
			$this->data['ups_pr_02'] = $this->request->post['ups_pr_02'];
		} else {
			$this->data['ups_pr_02'] = C('ups_pr_02');
		}

		if (isset($this->request->post['ups_pr_03'])) {
			$this->data['ups_pr_03'] = $this->request->post['ups_pr_03'];
		} else {
			$this->data['ups_pr_03'] = C('ups_pr_03');
		}

		if (isset($this->request->post['ups_pr_07'])) {
			$this->data['ups_pr_07'] = $this->request->post['ups_pr_07'];
		} else {
			$this->data['ups_pr_07'] = C('ups_pr_07');
		}

		if (isset($this->request->post['ups_pr_08'])) {
			$this->data['ups_pr_08'] = $this->request->post['ups_pr_08'];
		} else {
			$this->data['ups_pr_08'] = C('ups_pr_08');
		}

		if (isset($this->request->post['ups_pr_14'])) {
			$this->data['ups_pr_14'] = $this->request->post['ups_pr_14'];
		} else {
			$this->data['ups_pr_14'] = C('ups_pr_14');
		}

		if (isset($this->request->post['ups_pr_54'])) {
			$this->data['ups_pr_54'] = $this->request->post['ups_pr_54'];
		} else {
			$this->data['ups_pr_54'] = C('ups_pr_54');
		}

		if (isset($this->request->post['ups_pr_65'])) {
			$this->data['ups_pr_65'] = $this->request->post['ups_pr_65'];
		} else {
			$this->data['ups_pr_65'] = C('ups_pr_65');
		}

		// Canada
		if (isset($this->request->post['ups_ca_01'])) {
			$this->data['ups_ca_01'] = $this->request->post['ups_ca_01'];
		} else {
			$this->data['ups_ca_01'] = C('ups_ca_01');
		}

		if (isset($this->request->post['ups_ca_02'])) {
			$this->data['ups_ca_02'] = $this->request->post['ups_ca_02'];
		} else {
			$this->data['ups_ca_02'] = C('ups_ca_02');
		}

		if (isset($this->request->post['ups_ca_07'])) {
			$this->data['ups_ca_07'] = $this->request->post['ups_ca_07'];
		} else {
			$this->data['ups_ca_07'] = C('ups_ca_07');
		}

		if (isset($this->request->post['ups_ca_08'])) {
			$this->data['ups_ca_08'] = $this->request->post['ups_ca_08'];
		} else {
			$this->data['ups_ca_08'] = C('ups_ca_08');
		}

		if (isset($this->request->post['ups_ca_11'])) {
			$this->data['ups_ca_11'] = $this->request->post['ups_ca_11'];
		} else {
			$this->data['ups_ca_11'] = C('ups_ca_11');
		}

		if (isset($this->request->post['ups_ca_12'])) {
			$this->data['ups_ca_12'] = $this->request->post['ups_ca_12'];
		} else {
			$this->data['ups_ca_12'] = C('ups_ca_12');
		}

		if (isset($this->request->post['ups_ca_13'])) {
			$this->data['ups_ca_13'] = $this->request->post['ups_ca_13'];
		} else {
			$this->data['ups_ca_13'] = C('ups_ca_13');
		}

		if (isset($this->request->post['ups_ca_14'])) {
			$this->data['ups_ca_14'] = $this->request->post['ups_ca_14'];
		} else {
			$this->data['ups_ca_14'] = C('ups_ca_14');
		}

		if (isset($this->request->post['ups_ca_54'])) {
			$this->data['ups_ca_54'] = $this->request->post['ups_ca_54'];
		} else {
			$this->data['ups_ca_54'] = C('ups_ca_54');
		}

		if (isset($this->request->post['ups_ca_65'])) {
			$this->data['ups_ca_65'] = $this->request->post['ups_ca_65'];
		} else {
			$this->data['ups_ca_65'] = C('ups_ca_65');
		}

		// Mexico
		if (isset($this->request->post['ups_mx_07'])) {
			$this->data['ups_mx_07'] = $this->request->post['ups_mx_07'];
		} else {
			$this->data['ups_mx_07'] = C('ups_mx_07');
		}

		if (isset($this->request->post['ups_mx_08'])) {
			$this->data['ups_mx_08'] = $this->request->post['ups_mx_08'];
		} else {
			$this->data['ups_mx_08'] = C('ups_mx_08');
		}

		if (isset($this->request->post['ups_mx_54'])) {
			$this->data['ups_mx_54'] = $this->request->post['ups_mx_54'];
		} else {
			$this->data['ups_mx_54'] = C('ups_mx_54');
		}

		if (isset($this->request->post['ups_mx_65'])) {
			$this->data['ups_mx_65'] = $this->request->post['ups_mx_65'];
		} else {
			$this->data['ups_mx_65'] = C('ups_mx_65');
		}

		// EU
		if (isset($this->request->post['ups_eu_07'])) {
			$this->data['ups_eu_07'] = $this->request->post['ups_eu_07'];
		} else {
			$this->data['ups_eu_07'] = C('ups_eu_07');
		}

		if (isset($this->request->post['ups_eu_08'])) {
			$this->data['ups_eu_08'] = $this->request->post['ups_eu_08'];
		} else {
			$this->data['ups_eu_08'] = C('ups_eu_08');
		}

		if (isset($this->request->post['ups_eu_11'])) {
			$this->data['ups_eu_11'] = $this->request->post['ups_eu_11'];
		} else {
			$this->data['ups_eu_11'] = C('ups_eu_11');
		}

		if (isset($this->request->post['ups_eu_54'])) {
			$this->data['ups_eu_54'] = $this->request->post['ups_eu_54'];
		} else {
			$this->data['ups_eu_54'] = C('ups_eu_54');
		}

		if (isset($this->request->post['ups_eu_65'])) {
			$this->data['ups_eu_65'] = $this->request->post['ups_eu_65'];
		} else {
			$this->data['ups_eu_65'] = C('ups_eu_65');
		}

		if (isset($this->request->post['ups_eu_82'])) {
			$this->data['ups_eu_82'] = $this->request->post['ups_eu_82'];
		} else {
			$this->data['ups_eu_82'] = C('ups_eu_82');
		}

		if (isset($this->request->post['ups_eu_83'])) {
			$this->data['ups_eu_83'] = $this->request->post['ups_eu_83'];
		} else {
			$this->data['ups_eu_83'] = C('ups_eu_83');
		}

		if (isset($this->request->post['ups_eu_84'])) {
			$this->data['ups_eu_84'] = $this->request->post['ups_eu_84'];
		} else {
			$this->data['ups_eu_84'] = C('ups_eu_84');
		}

		if (isset($this->request->post['ups_eu_85'])) {
			$this->data['ups_eu_85'] = $this->request->post['ups_eu_85'];
		} else {
			$this->data['ups_eu_85'] = C('ups_eu_85');
		}

		if (isset($this->request->post['ups_eu_86'])) {
			$this->data['ups_eu_86'] = $this->request->post['ups_eu_86'];
		} else {
			$this->data['ups_eu_86'] = C('ups_eu_86');
		}

		// Other
		if (isset($this->request->post['ups_other_07'])) {
			$this->data['ups_other_07'] = $this->request->post['ups_other_07'];
		} else {
			$this->data['ups_other_07'] = C('ups_other_07');
		}

		if (isset($this->request->post['ups_other_08'])) {
			$this->data['ups_other_08'] = $this->request->post['ups_other_08'];
		} else {
			$this->data['ups_other_08'] = C('ups_other_08');
		}

		if (isset($this->request->post['ups_other_11'])) {
			$this->data['ups_other_11'] = $this->request->post['ups_other_11'];
		} else {
			$this->data['ups_other_11'] = C('ups_other_11');
		}

		if (isset($this->request->post['ups_other_54'])) {
			$this->data['ups_other_54'] = $this->request->post['ups_other_54'];
		} else {
			$this->data['ups_other_54'] = C('ups_other_54');
		}

		if (isset($this->request->post['ups_other_65'])) {
			$this->data['ups_other_65'] = $this->request->post['ups_other_65'];
		} else {
			$this->data['ups_other_65'] = C('ups_other_65');
		}

		if (isset($this->request->post['ups_display_weight'])) {
			$this->data['ups_display_weight'] = $this->request->post['ups_display_weight'];
		} else {
			$this->data['ups_display_weight'] = C('ups_display_weight');
		}

		if (isset($this->request->post['ups_insurance'])) {
			$this->data['ups_insurance'] = $this->request->post['ups_insurance'];
		} else {
			$this->data['ups_insurance'] = C('ups_insurance');
		}

		if (isset($this->request->post['ups_weight_class_id'])) {
			$this->data['ups_weight_class_id'] = $this->request->post['ups_weight_class_id'];
		} else {
			$this->data['ups_weight_class_id'] = C('ups_weight_class_id');
		}

		M('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['ups_length_code'])) {
			$this->data['ups_length_code'] = $this->request->post['ups_length_code'];
		} else {
			$this->data['ups_length_code'] = C('ups_length_code');
		}

		if (isset($this->request->post['ups_length_class_id'])) {
			$this->data['ups_length_class_id'] = $this->request->post['ups_length_class_id'];
		} else {
			$this->data['ups_length_class_id'] = C('ups_length_class_id');
		}

		M('localisation/length_class');

		$this->data['length_classes'] = C('cache_length_class');

		if (isset($this->request->post['ups_length'])) {
			$this->data['ups_length'] = $this->request->post['ups_length'];
		} else {
			$this->data['ups_length'] = C('ups_length');
		}

		if (isset($this->request->post['ups_width'])) {
			$this->data['ups_width'] = $this->request->post['ups_width'];
		} else {
			$this->data['ups_width'] = C('ups_width');
		}

		if (isset($this->request->post['ups_height'])) {
			$this->data['ups_height'] = $this->request->post['ups_height'];
		} else {
			$this->data['ups_height'] = C('ups_height');
		}

		if (isset($this->request->post['ups_tax_class_id'])) {
			$this->data['ups_tax_class_id'] = $this->request->post['ups_tax_class_id'];
		} else {
			$this->data['ups_tax_class_id'] = C('ups_tax_class_id');
		}

		M('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['ups_geo_zone_id'])) {
			$this->data['ups_geo_zone_id'] = $this->request->post['ups_geo_zone_id'];
		} else {
			$this->data['ups_geo_zone_id'] = C('ups_geo_zone_id');
		}

		M('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['ups_status'])) {
			$this->data['ups_status'] = $this->request->post['ups_status'];
		} else {
			$this->data['ups_status'] = C('ups_status');
		}

		if (isset($this->request->post['ups_sort_order'])) {
			$this->data['ups_sort_order'] = $this->request->post['ups_sort_order'];
		} else {
			$this->data['ups_sort_order'] = C('ups_sort_order');
		}

		if (isset($this->request->post['ups_debug'])) {
			$this->data['ups_debug'] = $this->request->post['ups_debug'];
		} else {
			$this->data['ups_debug'] = C('ups_debug');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

 		$this->display('shipping/ups.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/ups')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$this->load->library('form_validation', true);

		$this->form_validation->set_rules('ups_key', '', 'required', L('error_key'));

		$this->form_validation->set_rules('ups_username', '', 'required', L('error_username'));

		$this->form_validation->set_rules('ups_password', '', 'required', L('error_password'));

		$this->form_validation->set_rules('ups_city', '', 'required', L('error_city'));

		$this->form_validation->set_rules('ups_state', '', 'required', L('error_state'));

		$this->form_validation->set_rules('ups_country', '', 'required', L('error_country'));

		$this->form_validation->set_rules('ups_length', '', 'required', L('error_dimension'));

		$this->form_validation->set_rules('ups_width', '', 'required', L('error_dimension'));

		$this->form_validation->set_rules('ups_height', '', 'required', L('error_dimension'));

		return $this->form_validation->run();
	}
}
?>