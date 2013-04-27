<?php
class ModelShippingFlat extends Model {
	function getQuote($address) {
		$this->language->load('shipping/flat');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!C('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
      		$quote_data['flat'] = array(
        		'code'         => 'flat.flat',
        		'title'        => L('text_description'),
        		'cost'         => C('flat_cost'),
        		'tax_class_id' => C('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate(C('flat_cost'), C('flat_tax_class_id'), C('config_tax')))
      		);

      		$method_data = array(
        		'code'       => 'flat',
        		'title'      => L('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => C('flat_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}
}
?>