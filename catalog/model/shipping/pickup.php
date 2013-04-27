<?php
class ModelShippingPickup extends Model {
	function getQuote($address) {
		$this->language->load('shipping/pickup');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('pickup_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!C('pickup_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
      		$quote_data['pickup'] = array(
        		'code'         => 'pickup.pickup',
        		'title'        => L('text_description'),
        		'cost'         => 0.00,
        		'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00)
      		);

      		$method_data = array(
        		'code'       => 'pickup',
        		'title'      => L('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => C('pickup_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}
}
?>