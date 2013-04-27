<?php 
class ModelPaymentCOD extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/cod');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('cod_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (C('cod_total') > 0 && C('cod_total') > $total) {
			$status = false;
		} elseif (!C('cod_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'cod',
        		'title'      => L('text_title'),
				'sort_order' => C('cod_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>