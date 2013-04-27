<?php
class ModelShippingItem extends Model {
	function getQuote($address) {
		$this->language->load('shipping/item');
		
		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('item_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!C('item_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
			$items = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) $items += $product['quantity'];
			}			
			
			$quote_data = array();
			
      		$quote_data['item'] = array(
        		'code'         => 'item.item',
        		'title'        => L('text_description'),
        		'cost'         => C('item_cost') * $items,
         		'tax_class_id' => C('item_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate(C('item_cost') * $items, C('item_tax_class_id'), C('config_tax')))
      		);

      		$method_data = array(
        		'code'       => 'item',
        		'title'      => L('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => C('item_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}
}
?>