<?php

class ControllerCommonAjax extends Controller {	
	
	public function country() {
		$json = array();
		
		M('localisation/country');
		$country_id = $this->request->get['country_id'];
    	$country_info = $this->model_localisation_country->getCountry($country_id);
		
		if ($country_info) {
			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => cache_read($country_id.'.php', 'zone'),
				'status'            => $country_info['status']		
			);
		}
		$this->response->setOutput(json_encode($json));
	}
}