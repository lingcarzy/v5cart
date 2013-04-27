<?php
class ControllerShippingUsps extends Controller {

	public function index() {
		$this->language->load('shipping/usps');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('usps', $this->request->post);

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
			'href'      => UA('shipping/usps'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['usps_user_id'])) {
			$this->data['usps_user_id'] = $this->request->post['usps_user_id'];
		} else {
			$this->data['usps_user_id'] = C('usps_user_id');
		}

		if (isset($this->request->post['usps_postcode'])) {
			$this->data['usps_postcode'] = $this->request->post['usps_postcode'];
		} else {
			$this->data['usps_postcode'] = C('usps_postcode');
		}

		if (isset($this->request->post['usps_domestic_00'])) {
			$this->data['usps_domestic_00'] = $this->request->post['usps_domestic_00'];
		} else {
			$this->data['usps_domestic_00'] = C('usps_domestic_00');
		}

		if (isset($this->request->post['usps_domestic_01'])) {
			$this->data['usps_domestic_01'] = $this->request->post['usps_domestic_01'];
		} else {
			$this->data['usps_domestic_01'] = C('usps_domestic_01');
		}

		if (isset($this->request->post['usps_domestic_02'])) {
			$this->data['usps_domestic_02'] = $this->request->post['usps_domestic_02'];
		} else {
			$this->data['usps_domestic_02'] = C('usps_domestic_02');
		}

		if (isset($this->request->post['usps_domestic_03'])) {
			$this->data['usps_domestic_03'] = $this->request->post['usps_domestic_03'];
		} else {
			$this->data['usps_domestic_03'] = C('usps_domestic_03');
		}

		if (isset($this->request->post['usps_domestic_1'])) {
			$this->data['usps_domestic_1'] = $this->request->post['usps_domestic_1'];
		} else {
			$this->data['usps_domestic_1'] = C('usps_domestic_1');
		}

		if (isset($this->request->post['usps_domestic_2'])) {
			$this->data['usps_domestic_2'] = $this->request->post['usps_domestic_2'];
		} else {
			$this->data['usps_domestic_2'] = C('usps_domestic_2');
		}

		if (isset($this->request->post['usps_domestic_3'])) {
			$this->data['usps_domestic_3'] = $this->request->post['usps_domestic_3'];
		} else {
			$this->data['usps_domestic_3'] = C('usps_domestic_3');
		}

		if (isset($this->request->post['usps_domestic_4'])) {
			$this->data['usps_domestic_4'] = $this->request->post['usps_domestic_4'];
		} else {
			$this->data['usps_domestic_4'] = C('usps_domestic_4');
		}

		if (isset($this->request->post['usps_domestic_5'])) {
			$this->data['usps_domestic_5'] = $this->request->post['usps_domestic_5'];
		} else {
			$this->data['usps_domestic_5'] = C('usps_domestic_5');
		}

		if (isset($this->request->post['usps_domestic_6'])) {
			$this->data['usps_domestic_6'] = $this->request->post['usps_domestic_6'];
		} else {
			$this->data['usps_domestic_6'] = C('usps_domestic_6');
		}

		if (isset($this->request->post['usps_domestic_7'])) {
			$this->data['usps_domestic_7'] = $this->request->post['usps_domestic_7'];
		} else {
			$this->data['usps_domestic_7'] = C('usps_domestic_7');
		}

		if (isset($this->request->post['usps_domestic_12'])) {
			$this->data['usps_domestic_12'] = $this->request->post['usps_domestic_12'];
		} else {
			$this->data['usps_domestic_12'] = C('usps_domestic_12');
		}

		if (isset($this->request->post['usps_domestic_13'])) {
			$this->data['usps_domestic_13'] = $this->request->post['usps_domestic_13'];
		} else {
			$this->data['usps_domestic_13'] = C('usps_domestic_13');
		}

		if (isset($this->request->post['usps_domestic_16'])) {
			$this->data['usps_domestic_16'] = $this->request->post['usps_domestic_16'];
		} else {
			$this->data['usps_domestic_16'] = C('usps_domestic_16');
		}

		if (isset($this->request->post['usps_domestic_17'])) {
			$this->data['usps_domestic_17'] = $this->request->post['usps_domestic_17'];
		} else {
			$this->data['usps_domestic_17'] = C('usps_domestic_17');
		}

		if (isset($this->request->post['usps_domestic_18'])) {
			$this->data['usps_domestic_18'] = $this->request->post['usps_domestic_18'];
		} else {
			$this->data['usps_domestic_18'] = C('usps_domestic_18');
		}

		if (isset($this->request->post['usps_domestic_19'])) {
			$this->data['usps_domestic_19'] = $this->request->post['usps_domestic_19'];
		} else {
			$this->data['usps_domestic_19'] = C('usps_domestic_19');
		}

		if (isset($this->request->post['usps_domestic_22'])) {
			$this->data['usps_domestic_22'] = $this->request->post['usps_domestic_22'];
		} else {
			$this->data['usps_domestic_22'] = C('usps_domestic_22');
		}

		if (isset($this->request->post['usps_domestic_23'])) {
			$this->data['usps_domestic_23'] = $this->request->post['usps_domestic_23'];
		} else {
			$this->data['usps_domestic_23'] = C('usps_domestic_23');
		}

		if (isset($this->request->post['usps_domestic_25'])) {
			$this->data['usps_domestic_25'] = $this->request->post['usps_domestic_25'];
		} else {
			$this->data['usps_domestic_25'] = C('usps_domestic_25');
		}

		if (isset($this->request->post['usps_domestic_27'])) {
			$this->data['usps_domestic_27'] = $this->request->post['usps_domestic_27'];
		} else {
			$this->data['usps_domestic_27'] = C('usps_domestic_27');
		}

		if (isset($this->request->post['usps_domestic_28'])) {
			$this->data['usps_domestic_28'] = $this->request->post['usps_domestic_28'];
		} else {
			$this->data['usps_domestic_28'] = C('usps_domestic_28');
		}

		if (isset($this->request->post['usps_international_1'])) {
			$this->data['usps_international_1'] = $this->request->post['usps_international_1'];
		} else {
			$this->data['usps_international_1'] = C('usps_international_1');
		}

		if (isset($this->request->post['usps_international_2'])) {
			$this->data['usps_international_2'] = $this->request->post['usps_international_2'];
		} else {
			$this->data['usps_international_2'] = C('usps_international_2');
		}

		if (isset($this->request->post['usps_international_4'])) {
			$this->data['usps_international_4'] = $this->request->post['usps_international_4'];
		} else {
			$this->data['usps_international_4'] = C('usps_international_4');
		}

		if (isset($this->request->post['usps_international_5'])) {
			$this->data['usps_international_5'] = $this->request->post['usps_international_5'];
		} else {
			$this->data['usps_international_5'] = C('usps_international_5');
		}

		if (isset($this->request->post['usps_international_6'])) {
			$this->data['usps_international_6'] = $this->request->post['usps_international_6'];
		} else {
			$this->data['usps_international_6'] = C('usps_international_6');
		}

		if (isset($this->request->post['usps_international_7'])) {
			$this->data['usps_international_7'] = $this->request->post['usps_international_7'];
		} else {
			$this->data['usps_international_7'] = C('usps_international_7');
		}

		if (isset($this->request->post['usps_international_8'])) {
			$this->data['usps_international_8'] = $this->request->post['usps_international_8'];
		} else {
			$this->data['usps_international_8'] = C('usps_international_8');
		}

		if (isset($this->request->post['usps_international_9'])) {
			$this->data['usps_international_9'] = $this->request->post['usps_international_9'];
		} else {
			$this->data['usps_international_9'] = C('usps_international_9');
		}

		if (isset($this->request->post['usps_international_10'])) {
			$this->data['usps_international_10'] = $this->request->post['usps_international_10'];
		} else {
			$this->data['usps_international_10'] = C('usps_international_10');
		}

		if (isset($this->request->post['usps_international_11'])) {
			$this->data['usps_international_11'] = $this->request->post['usps_international_11'];
		} else {
			$this->data['usps_international_11'] = C('usps_international_11');
		}

		if (isset($this->request->post['usps_international_12'])) {
			$this->data['usps_international_12'] = $this->request->post['usps_international_12'];
		} else {
			$this->data['usps_international_12'] = C('usps_international_12');
		}

		if (isset($this->request->post['usps_international_13'])) {
			$this->data['usps_international_13'] = $this->request->post['usps_international_13'];
		} else {
			$this->data['usps_international_13'] = C('usps_international_13');
		}

		if (isset($this->request->post['usps_international_14'])) {
			$this->data['usps_international_14'] = $this->request->post['usps_international_14'];
		} else {
			$this->data['usps_international_14'] = C('usps_international_14');
		}

		if (isset($this->request->post['usps_international_15'])) {
			$this->data['usps_international_15'] = $this->request->post['usps_international_15'];
		} else {
			$this->data['usps_international_15'] = C('usps_international_15');
		}

		if (isset($this->request->post['usps_international_16'])) {
			$this->data['usps_international_16'] = $this->request->post['usps_international_16'];
		} else {
			$this->data['usps_international_16'] = C('usps_international_16');
		}

		if (isset($this->request->post['usps_international_21'])) {
			$this->data['usps_international_21'] = $this->request->post['usps_international_21'];
		} else {
			$this->data['usps_international_21'] = C('usps_international_21');
		}

		if (isset($this->request->post['usps_size'])) {
			$this->data['usps_size'] = $this->request->post['usps_size'];
		} else {
			$this->data['usps_size'] = C('usps_size');
		}

		$this->data['sizes'] = array();

		$this->data['sizes'][] = array(
			'text'  => L('text_regular'),
			'value' => 'REGULAR'
		);

		$this->data['sizes'][] = array(
			'text'  => L('text_large'),
			'value' => 'LARGE'
		);

		if (isset($this->request->post['usps_container'])) {
			$this->data['usps_container'] = $this->request->post['usps_container'];
		} else {
			$this->data['usps_container'] = C('usps_container');
		}

		$this->data['containers'] = array();

		$this->data['containers'][] = array(
			'text'  => L('text_rectangular'),
			'value' => 'RECTANGULAR'
		);

		$this->data['containers'][] = array(
			'text'  => L('text_non_rectangular'),
			'value' => 'NONRECTANGULAR'
		);

		$this->data['containers'][] = array(
			'text'  => L('text_variable'),
			'value' => 'VARIABLE'
		);

		if (isset($this->request->post['usps_machinable'])) {
			$this->data['usps_machinable'] = $this->request->post['usps_machinable'];
		} else {
			$this->data['usps_machinable'] = C('usps_machinable');
		}

		if (isset($this->request->post['usps_length'])) {
			$this->data['usps_length'] = $this->request->post['usps_length'];
		} else {
			$this->data['usps_length'] = C('usps_length');
		}

		if (isset($this->request->post['usps_width'])) {
			$this->data['usps_width'] = $this->request->post['usps_width'];
		} else {
			$this->data['usps_width'] = C('usps_width');
		}

		if (isset($this->request->post['usps_height'])) {
			$this->data['usps_height'] = $this->request->post['usps_height'];
		} else {
			$this->data['usps_height'] = C('usps_height');
		}

		if (isset($this->request->post['usps_length'])) {
			$this->data['usps_length'] = $this->request->post['usps_length'];
		} else {
			$this->data['usps_length'] = C('usps_length');
		}

		if (isset($this->request->post['usps_display_time'])) {
			$this->data['usps_display_time'] = $this->request->post['usps_display_time'];
		} else {
			$this->data['usps_display_time'] = C('usps_display_time');
		}

		if (isset($this->request->post['usps_display_weight'])) {
			$this->data['usps_display_weight'] = $this->request->post['usps_display_weight'];
		} else {
			$this->data['usps_display_weight'] = C('usps_display_weight');
		}

		if (isset($this->request->post['usps_weight_class_id'])) {
			$this->data['usps_weight_class_id'] = $this->request->post['usps_weight_class_id'];
		} else {
			$this->data['usps_weight_class_id'] = C('usps_weight_class_id');
		}

		M('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['usps_tax_class_id'])) {
			$this->data['usps_tax_class_id'] = $this->request->post['usps_tax_class_id'];
		} else {
			$this->data['usps_tax_class_id'] = C('usps_tax_class_id');
		}

		if (isset($this->request->post['usps_geo_zone_id'])) {
			$this->data['usps_geo_zone_id'] = $this->request->post['usps_geo_zone_id'];
		} else {
			$this->data['usps_geo_zone_id'] = C('usps_geo_zone_id');
		}

		if (isset($this->request->post['usps_debug'])) {
			$this->data['usps_debug'] = $this->request->post['usps_debug'];
		} else {
			$this->data['usps_debug'] = C('usps_debug');
		}

		if (isset($this->request->post['usps_status'])) {
			$this->data['usps_status'] = $this->request->post['usps_status'];
		} else {
			$this->data['usps_status'] = C('usps_status');
		}

		if (isset($this->request->post['usps_sort_order'])) {
			$this->data['usps_sort_order'] = $this->request->post['usps_sort_order'];
		} else {
			$this->data['usps_sort_order'] = C('usps_sort_order');
		}

		M('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		M('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('shipping/usps.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/usps')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		
		$this->load->library('form_validation', true);
		
		$this->form_validation->set_rules('usps_user_id', '', 'required', L('error_user_id'));		
		$this->form_validation->set_rules('usps_postcode', '', 'required', L('error_postcode'));		
		$this->form_validation->set_rules('usps_width', '', 'required', L('error_width'));		
		$this->form_validation->set_rules('usps_height', '', 'required', L('error_height'));		
		$this->form_validation->set_rules('usps_length', '', 'required', L('error_length'));

		return $this->form_validation->run();
	}
}
?>