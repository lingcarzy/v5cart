<?php
class ModelShippingAusPost extends Model {
	public function getQuote($address) {
		$this->language->load('shipping/auspost');

		$query = $this->db->query("SELECT * FROM @@zone_to_geo_zone WHERE geo_zone_id = '" . (int)C('auspost_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!C('auspost_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$error = '';
		
		$quote_data = array();
		
		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), C('config_weight_class_id'), C('auspost_weight_class_id'));
		
			if (C('auspost_standard') && $address['iso_code_2'] == 'AU') {
				$curl = curl_init();
		
				curl_setopt($curl, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . urlencode(C('auspost_postcode')) . '&destination_postcode=' . urlencode($address['postcode']) . '&height=70&width=70&length=70&country=AU&service_type=standard&quantity=1&weight=' . urlencode($weight));
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($curl);
				
				curl_close($curl);
				
				if ($response) {
					$response_info = array();
					
					$parts = explode("\n", trim($response));
					
					foreach ($parts as $part) {
						list($key, $value) = explode('=', $part);
						
						$response_info[$key] = $value;
					}
					
					if ($response_info['err_msg'] != 'OK') {
						$error = $response_info['err_msg'];
					} else {
						$title = L('text_standard');
					
						if (C('auspost_display_time')) {
							$title .= ' (' . $response_info['days'] . ' ' . L('text_eta') . ')';
						}	
			
						$quote_data['standard'] = array(
							'code'         => 'auspost.standard',
							'title'        => $title,
							'cost'         => $this->currency->convert($response_info['charge'], 'AUD', C('config_currency')),
							'tax_class_id' => C('auspost_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($response_info['charge'], 'AUD', $this->currency->getCode()), C('auspost_tax_class_id'), C('config_tax')), $this->currency->getCode(), 1.0000000)
						);
					}
				}
			}
	
			if (C('auspost_express') && $address['iso_code_2'] == 'AU') {
				$curl = curl_init();
				
				curl_setopt($curl, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . urlencode(C('auspost_postcode')) . '&destination_postcode=' . urlencode($address['postcode']) . '&height=70&width=70&length=70&country=AU&service_type=express&quantity=1&weight=' . urlencode($weight));
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($curl);
				
				curl_close($curl); 
				
				if ($response) {
					$response_info = array();
					
					$parts = explode("\n", trim($response));
					
					foreach ($parts as $part) {
						list($key, $value) = explode('=', $part);
						
						$response_info[$key] = $value;
					}
								
					if ($response_info['err_msg'] != 'OK') {
						$error = $response_info['err_msg'];
					} else {
						$title = L('text_express');
						
						if (C('auspost_display_time')) {
							$title .= ' (' . $response_info['days'] . ' ' . L('text_eta') . ')';
						}	
		
						$quote_data['express'] = array(
							'code'         => 'auspost.express',
							'title'        => $title,
							'cost'         => $this->currency->convert($response_info['charge'], 'AUD', C('config_currency')),
							'tax_class_id' => C('auspost_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($response_info['charge'], 'AUD', $this->currency->getCode()), C('auspost_tax_class_id'), C('config_tax')), $this->currency->getCode(), 1.0000000)
						);
					}
				}
			}
		}
		
		$method_data = array();
		
		if ($quote_data) {
			$method_data = array(
				'code'       => 'auspost',
				'title'      => L('text_title'),
				'quote'      => $quote_data,
				'sort_order' => C('auspost_sort_order'),
				'error'      => $error 
			);
		}
		
		return $method_data;
	}
}
?>