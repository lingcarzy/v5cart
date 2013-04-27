<?php 
class ModelPaymentPerpetualPayments extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/perpetual_payments');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('perpetual_payments_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (C('perpetual_payments_total') > 0 && C('perpetual_payments_total') > $total) {
			$status = false;
		} elseif (!C('perpetual_payments_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'perpetual_payments',
        		'title'      => L('text_title'),
				'sort_order' => C('perpetual_payments_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>