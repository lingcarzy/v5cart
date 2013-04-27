<?php 
class ModelPaymentTwoCheckout extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/twocheckout');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('twocheckout_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (C('twocheckout_total') > 0 && C('twocheckout_total') > $total) {
			$status = false;
		} elseif (!C('twocheckout_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'twocheckout',
        		'title'      => L('text_title'),
				'sort_order' => C('twocheckout_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>