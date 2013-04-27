<?php 
class ModelPaymentPPProUK extends Model {
  	public function getMethod($address, $total) { 
		$this->language->load('payment/pp_pro_uk');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('pp_pro_uk_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (C('pp_pro_uk_total') > 0 && C('pp_pro_uk_total') > $total) {
			$status = false;
		} elseif (!C('pp_pro_uk_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'pp_pro_uk',
        		'title'      => L('text_title'),
				'sort_order' => C('pp_pro_uk_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>