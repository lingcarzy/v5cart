<?php 
class ModelPaymentPaypalExpress extends Model {

  	public function getMethod($address, $total) {
	
		$this->language->load('payment/paypal_express');

		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('paypal_express_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!C('paypal_express_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if (C('paypal_express_status')) {  
      		$method_data = array( 
        		'code'       => 'paypal_express',
        		'title'      => L('text_title'),
				'sort_order' => C('paypal_express_sort_order')
      		);
    	}
    	return $method_data;
  	}
}
?>