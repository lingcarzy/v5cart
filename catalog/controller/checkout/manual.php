<?php 
class ControllerCheckoutManual extends Controller {
	public function index() {
		$this->language->load('checkout/manual');
		
		$json = array();
			
		$this->load->library('user');
		
		$this->user = new User($this->registry);
				
		if ($this->user->isLogged() && $this->user->hasPermission('modify', 'sale/order')) {	
			// Reset everything
			$this->cart->clear();
			$this->customer->logout();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);			
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			// Settings
			M('setting/setting');
			
			$settings = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);
			
			foreach ($settings as $key => $value) {
				$this->config->set($key, $value);
			}
			
    		// Customer
			if ($this->request->post['customer_id']) {
				M('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

				if ($customer_info) {
					$this->customer->login($customer_info['email'], '', true);
					$this->cart->clear();
				} else {
					$json['error']['customer'] = L('error_customer');
				}
			} else {
				// Customer Group
				$this->config->set('config_customer_group_id', $this->request->post['customer_group_id']);
			}
				
			// Product
			M('catalog/product');
			
			if (isset($this->request->post['order_product'])) {
				foreach ($this->request->post['order_product'] as $order_product) {
					$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);
				
					if ($product_info) {	
						$option_data = array();
						
						if (isset($order_product['order_option'])) {
							foreach ($order_product['order_option'] as $option) {
								if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { 
									$option_data[$option['product_option_id']] = $option['product_option_value_id'];
								} elseif ($option['type'] == 'checkbox') {
									$option_data[$option['product_option_id']][] = $option['product_option_value_id'];
								} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
									$option_data[$option['product_option_id']] = $option['value'];						
								}
							}
						}
															
						$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
					}
				}
			}
			
			if (isset($this->request->post['product_id'])) {
				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
				
				if ($product_info) {
					if (isset($this->request->post['quantity'])) {
						$quantity = $this->request->post['quantity'];
					} else {
						$quantity = 1;
					}
																
					if (isset($this->request->post['option'])) {
						$option = array_filter($this->request->post['option']);
					} else {
						$option = array();	
					}
					
					$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
					
					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['product']['option'][$product_option['product_option_id']] = sprintf(L('error_required'), $product_option['name']);
						}
					}
					
					if (!isset($json['error']['product']['option'])) {
						$this->cart->add($this->request->post['product_id'], $quantity, $option);
					}
				}
			}
			
			if (!$this->cart->hasStock() && (!C('config_stock_checkout') || C('config_stock_warning'))) {
				$json['error']['product']['stock'] = L('error_stock');
			}
		
			// Tax
			if ($this->cart->hasShipping()) {
				$this->tax->setShippingAddress($this->request->post['shipping_country_id'], $this->request->post['shipping_zone_id']);
			} else {
				$this->tax->setShippingAddress(C('config_country_id'), C('config_zone_id'));
			}
			
			$this->tax->setPaymentAddress($this->request->post['payment_country_id'], $this->request->post['payment_zone_id']);				
			$this->tax->setStoreAddress(C('config_country_id'), C('config_zone_id'));	
			
			// Products
			$json['order_product'] = array();
			
			$products = $this->cart->getProducts();
			
			foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}	
								
				if ($product['minimum'] > $product_total) {
					$json['error']['product']['minimum'][] = sprintf(L('error_minimum'), $product['name'], $product['minimum']);
				}	
								
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['option_value'],
						'type'                    => $option['type']
					);
				}
		
				$download_data = array();
				
				foreach ($product['download'] as $download) {
					$download_data[] = array(
						'name'      => $download['name'],
						'filename'  => $download['filename'],
						'mask'      => $download['mask'],
						'remaining' => $download['remaining']
					);
				}
								
				$json['order_product'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'], 
					'option'     => $option_data,
					'download'   => $download_data,
					'quantity'   => $product['quantity'],
					'stock'      => $product['stock'],
					'price'      => $product['price'],	
					'total'      => $product['total'],	
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']				
				);
			}

			// Voucher
			$this->session->data['vouchers'] = array();
			
			if (isset($this->request->post['order_voucher'])) {
				foreach ($this->request->post['order_voucher'] as $voucher) {
					$this->session->data['vouchers'][] = array(
						'voucher_id'       => $voucher['voucher_id'],
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'], 
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']    
					);
				}
			}

			// Add a new voucher if set
			if (isset($this->request->post['from_name']) && isset($this->request->post['from_email']) && isset($this->request->post['to_name']) && isset($this->request->post['to_email']) && isset($this->request->post['amount'])) {
				if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
					$json['error']['vouchers']['from_name'] = L('error_from_name');
				}  
			
				if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['from_email'])) {
					$json['error']['vouchers']['from_email'] = L('error_email');
				}
			
				if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
					$json['error']['vouchers']['to_name'] = L('error_to_name');
				}       
			
				if ((utf8_strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['to_email'])) {
					$json['error']['vouchers']['to_email'] = L('error_email');
				}
			
				if (($this->request->post['amount'] < 1) || ($this->request->post['amount'] > 1000)) {
					$json['error']['vouchers']['amount'] = sprintf(L('error_amount'), $this->currency->format(1, false, 1), $this->currency->format(1000, false, 1) . ' ' . C('config_currency'));
				}
			
				if (!isset($json['error']['vouchers'])) { 
					$voucher_data = array(
						'order_id'         => 0,
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'], 
						'message'          => $this->request->post['message'],
						'amount'           => $this->request->post['amount'],
						'status'           => true             
					); 
					
					M('checkout/voucher');
					
					$voucher_id = $this->model_checkout_voucher->addVoucher(0, $voucher_data);  
									
					$this->session->data['vouchers'][] = array(
						'voucher_id'       => $voucher_id,
						'description'      => sprintf(L('text_for'), $this->currency->format($this->request->post['amount'], C('config_currency')), $this->request->post['to_name']),
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'], 
						'message'          => $this->request->post['message'],
						'amount'           => $this->request->post['amount']            
					); 
				}
			}
			
			$json['order_voucher'] = array();
					
			foreach ($this->session->data['vouchers'] as $voucher) {
				$json['order_voucher'][] = array(
					'voucher_id'       => $voucher['voucher_id'],
					'description'      => $voucher['description'],
					'code'             => $voucher['code'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'], 
					'message'          => $voucher['message'],
					'amount'           => $voucher['amount']    
				);
			}
						
			M('setting/extension');
			
			M('localisation/country');
		
			M('localisation/zone');
			
			// Shipping
			$json['shipping_method'] = array();
			
			if ($this->cart->hasShipping()) {		
				M('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2) || (utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
					$json['error']['shipping']['postcode'] = L('error_postcode');
				}
		
				if ($this->request->post['shipping_country_id'] == '') {
					$json['error']['shipping']['country'] = L('error_country');
				}
				
				if (!isset($this->request->post['shipping_zone_id']) || $this->request->post['shipping_zone_id'] == '') {
					$json['error']['shipping']['zone'] = L('error_zone');
				}
							
				M('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2) || (utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
					$json['error']['shipping']['postcode'] = L('error_postcode');
				}

				if (!isset($json['error']['shipping'])) {
					if ($country_info) {
						$country = $country_info['name'];
						$iso_code_2 = $country_info['iso_code_2'];
						$iso_code_3 = $country_info['iso_code_3'];
						$address_format = $country_info['address_format'];
					} else {
						$country = '';
						$iso_code_2 = '';
						$iso_code_3 = '';	
						$address_format = '';
					}
				
					$zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);
					
					if ($zone_info) {
						$zone = $zone_info['name'];
						$zone_code = $zone_info['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}					
	
					$address_data = array(
						'firstname'      => $this->request->post['shipping_firstname'],
						'lastname'       => $this->request->post['shipping_lastname'],
						'company'        => $this->request->post['shipping_company'],
						'address_1'      => $this->request->post['shipping_address_1'],
						'address_2'      => $this->request->post['shipping_address_2'],
						'postcode'       => $this->request->post['shipping_postcode'],
						'city'           => $this->request->post['shipping_city'],
						'zone_id'        => $this->request->post['shipping_zone_id'],
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $this->request->post['shipping_country_id'],
						'country'        => $country,	
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format
					);
					
					$extensions = C('cache_extension_shipping');
					
					foreach ($extensions as $extension) {
						if (C($extension . '_status')) {
							M('shipping/' . $extension);
							
							$quote = $this->{'model_shipping_' . $extension}->getQuote($address_data); 
				
							if ($quote) {
								$json['shipping_method'][$extension] = array( 
									'title'      => $quote['title'],
									'quote'      => $quote['quote'], 
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error']
								);
							}
						}
					}
			
					$sort_order = array();
				  
					foreach ($json['shipping_method'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
			
					array_multisort($sort_order, SORT_ASC, $json['shipping_method']);

					if (!$json['shipping_method']) {
						$json['error']['shipping_method'] = L('error_no_shipping');
					} elseif ($this->request->post['shipping_code']) {
						$shipping = explode('.', $this->request->post['shipping_code']);
						
						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($json['shipping_method'][$shipping[0]]['quote'][$shipping[1]])) {		
							$json['error']['shipping_method'] = L('error_shipping');
						} else {
							$this->session->data['shipping_method'] = $json['shipping_method'][$shipping[0]]['quote'][$shipping[1]];
						}				
					}					
				}
			}
			
			// Coupon
			if (!empty($this->request->post['coupon'])) {
				M('checkout/coupon');
			
				$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);			
			
				if ($coupon_info) {					
					$this->session->data['coupon'] = $this->request->post['coupon'];
				} else {
					$json['error']['coupon'] = L('error_coupon');
				}
			}
			
			// Voucher
			if (!empty($this->request->post['voucher'])) {
				M('checkout/voucher');
			
				$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);			
			
				if ($voucher_info) {					
					$this->session->data['voucher'] = $this->request->post['voucher'];
				} else {
					$json['error']['voucher'] = L('error_voucher');
				}
			}
						
			// Reward Points
			if (!empty($this->request->post['reward'])) {
				$points = $this->customer->getRewardPoints();
				
				if ($this->request->post['reward'] > $points) {
					$json['error']['reward'] = sprintf(L('error_points'), $this->request->post['reward']);
				}
				
				if (!isset($json['error']['reward'])) {
					$points_total = 0;
					
					foreach ($this->cart->getProducts() as $product) {
						if ($product['points']) {
							$points_total += $product['points'];
						}
					}				
					
					if ($this->request->post['reward'] > $points_total) {
						$json['error']['reward'] = sprintf(L('error_maximum'), $points_total);
					}
					
					if (!isset($json['error']['reward'])) {		
						$this->session->data['reward'] = $this->request->post['reward'];
					}
				}
			}

			// Totals
			$json['order_total'] = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = C($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if (C($result['code'] . '_status')) {
					M('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($json['order_total'], $total, $taxes);
				}
				
				$sort_order = array(); 
			  
				foreach ($json['order_total'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
	
				array_multisort($sort_order, SORT_ASC, $json['order_total']);				
			}
		
			// Payment
			if ($this->request->post['payment_country_id'] == '') {
				$json['error']['payment']['country'] = L('error_country');
			}
			
			if (!isset($this->request->post['payment_zone_id']) || $this->request->post['payment_zone_id'] == '') {
				$json['error']['payment']['zone'] = L('error_zone');
			}		
			
			if (!isset($json['error']['payment'])) {
				$json['payment_methods'] = array();
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);
				
				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';	
					$address_format = '';
				}
				
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['payment_zone_id']);
				
				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}					
				
				$address_data = array(
					'firstname'      => $this->request->post['payment_firstname'],
					'lastname'       => $this->request->post['payment_lastname'],
					'company'        => $this->request->post['payment_company'],
					'address_1'      => $this->request->post['payment_address_1'],
					'address_2'      => $this->request->post['payment_address_2'],
					'postcode'       => $this->request->post['payment_postcode'],
					'city'           => $this->request->post['payment_city'],
					'zone_id'        => $this->request->post['payment_zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $this->request->post['payment_country_id'],
					'country'        => $country,	
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format
				);
				
				$json['payment_method'] = array();
								
				$extensions = C('cache_extension_payment');
		
				foreach ($extensions as $extension) {
					if (C($extension . '_status')) {
						M('payment/' . $extension);
						
						$method = $this->{'model_payment_' . $extension}->getMethod($address_data, $total); 
						
						if ($method) {
							$json['payment_method'][$extension] = $method;
						}
					}
				}
							 
				$sort_order = array(); 
			  
				foreach ($json['payment_method'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
		
				array_multisort($sort_order, SORT_ASC, $json['payment_method']);	
				
				if (!$json['payment_method']) {
					$json['error']['payment_method'] = L('error_no_payment');
				} elseif ($this->request->post['payment_code']) {			
					if (!isset($json['payment_method'][$this->request->post['payment_code']])) {
						$json['error']['payment_method'] = L('error_payment');
					}
				}
			}
			
			if (!isset($json['error'])) { 
				$json['success'] = L('text_success');
			} else {
				$json['error']['warning'] = L('error_warning');
			}
			
			// Reset everything
			$this->cart->clear();
			$this->customer->logout();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
		} else {
      		$json['error']['warning'] = L('error_permission');
		}
	
		$this->response->setOutput(json_encode($json));	
	}
}
?>