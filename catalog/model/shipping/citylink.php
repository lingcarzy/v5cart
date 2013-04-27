<?php
class ModelShippingCitylink extends Model {
	function getQuote($address) {
		$this->language->load('shipping/citylink');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('citylink_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!C('citylink_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();
	
		if ($status) {
			$cost = 0;
			$weight = $this->cart->getWeight();
			
			$rates = explode(',', C('citylink_rate'));
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  					
				if ($data[0] >= $weight) {
					if (isset($data[1])) {
    					$cost = $data[1];
					}
					
   					break;
  				}
			}
			
			$quote_data = array();
			
			if ((float)$cost) {
				$quote_data['citylink'] = array(
        			'code'         => 'citylink.citylink',
        			'title'        => L('text_title') . '  (' . L('text_weight') . ' ' . $this->weight->format($weight, C('config_weight_class_id')) . ')',
        			'cost'         => $cost,
        			'tax_class_id' => C('citylink_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, C('citylink_tax_class_id'), C('config_tax')))
      			);
				
      			$method_data = array(
        			'code'       => 'citylink',
        			'title'      => L('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => C('citylink_sort_order'),
        			'error'      => false
      			);
			}
		}
	
		return $method_data;
	}
}
?>