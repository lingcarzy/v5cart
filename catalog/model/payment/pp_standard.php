<?php 
class ModelPaymentPPStandard extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/pp_standard');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('pp_standard_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (C('pp_standard_total') > 0 && C('pp_standard_total') > $total) {
			$status = false;
		} elseif (!C('pp_standard_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	

		$currencies = array(
			'AUD',
			'CAD',
			'EUR',
			'GBP',
			'JPY',
			'USD',
			'NZD',
			'CHF',
			'HKD',
			'SGD',
			'SEK',
			'DKK',
			'PLN',
			'NOK',
			'HUF',
			'CZK',
			'ILS',
			'MXN',
			'MYR',
			'BRL',
			'PHP',
			'TWD',
			'THB',
			'TRY'
		);
		
		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}			
					
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'pp_standard',
        		'title'      => L('text_title'),
				'sort_order' => C('pp_standard_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>