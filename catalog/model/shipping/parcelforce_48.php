<?php
class ModelShippingParcelforce48 extends Model {
	function getQuote($address) {
		$this->language->load('shipping/parcelforce_48');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('parcelforce_48_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!C('parcelforce_48_geo_zone_id')) {
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
			$sub_total = $this->cart->getSubTotal();
			
			$rates = explode(',', C('parcelforce_48_rate'));
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  					
				if ($data[0] >= $weight) {
					if (isset($data[1])) {
    					$cost = $data[1];
					}
					
   					break;
  				}
			}

			$rates = explode(',', C('parcelforce_48_insurance'));
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  				
				if ($data[0] >= $sub_total) {
					if (isset($data[1])) {
    					$insurance = $data[1];
					}
					
   					break; 
  				}
			}
			
			$quote_data = array();
			
			if ((float)$cost) {
				$text = L('text_description');
			
				if (C('parcelforce_48_display_weight')) {
					$text .= ' (' . L('text_weight') . ' ' . $this->weight->format($weight, C('config_weight_class_id')) . ')';
				}
			
				if (C('parcelforce_48_display_insurance') && (float)$insurance) {
					$text .= ' (' . L('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
				}		

				if (C('parcelforce_48_display_time')) {
					$text .= ' (' . L('text_time') . ')';
				}	
				
      			$quote_data['parcelforce_48'] = array(
        			'code'         => 'parcelforce_48.parcelforce_48',
        			'title'        => $text,
        			'cost'         => $cost,
        			'tax_class_id' => C('parcelforce_48_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, C('parcelforce_48_tax_class_id'), C('config_tax')))
      			);

      			$method_data = array(
        			'code'       => 'parcelforce_48',
        			'title'      => L('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => C('parcelforce_48_sort_order'),
        			'error'      => false
      			);
			}
		}
	
		return $method_data;
	}
}
?>