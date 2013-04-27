<?php 
class ControllerCheckoutGuest extends Controller {
  	public function index() {
    	$this->language->load('checkout/checkout');
		
		$this->data['text_select'] = L('text_select');
		$this->data['text_none'] = L('text_none');
		$this->data['text_your_details'] = L('text_your_details');
		$this->data['text_your_account'] = L('text_your_account');
		$this->data['text_your_address'] = L('text_your_address');
		
		$this->data['entry_firstname'] = L('entry_firstname');
		$this->data['entry_lastname'] = L('entry_lastname');
		$this->data['entry_email'] = L('entry_email');
		$this->data['entry_telephone'] = L('entry_telephone');
		$this->data['entry_fax'] = L('entry_fax');
		$this->data['entry_company'] = L('entry_company');
		$this->data['entry_customer_group'] = L('entry_customer_group');
		$this->data['entry_company_id'] = L('entry_company_id');
		$this->data['entry_tax_id'] = L('entry_tax_id');			
		$this->data['entry_address_1'] = L('entry_address_1');
		$this->data['entry_address_2'] = L('entry_address_2');
		$this->data['entry_postcode'] = L('entry_postcode');
		$this->data['entry_city'] = L('entry_city');
		$this->data['entry_country'] = L('entry_country');
		$this->data['entry_zone'] = L('entry_zone');
		$this->data['entry_shipping'] = L('entry_shipping');
		
		$this->data['button_continue'] = L('button_continue');
		
		if (isset($this->session->data['guest']['firstname'])) {
			$this->data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->session->data['guest']['lastname'])) {
			$this->data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->session->data['guest']['email'])) {
			$this->data['email'] = $this->session->data['guest']['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->session->data['guest']['telephone'])) {
			$this->data['telephone'] = $this->session->data['guest']['telephone'];		
		} else {
			$this->data['telephone'] = '';
		}

		if (isset($this->session->data['guest']['fax'])) {
			$this->data['fax'] = $this->session->data['guest']['fax'];				
		} else {
			$this->data['fax'] = '';
		}

		if (isset($this->session->data['guest']['payment']['company'])) {
			$this->data['company'] = $this->session->data['guest']['payment']['company'];			
		} else {
			$this->data['company'] = '';
		}

		M('account/customer_group');

		$this->data['customer_groups'] = array();
		
		if (is_array(C('config_customer_group_display'))) {
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], C('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		if (isset($this->session->data['guest']['customer_group_id'])) {
    		$this->data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = C('config_customer_group_id');
		}
		
		// Company ID
		if (isset($this->session->data['guest']['payment']['company_id'])) {
			$this->data['company_id'] = $this->session->data['guest']['payment']['company_id'];			
		} else {
			$this->data['company_id'] = '';
		}
		
		// Tax ID
		if (isset($this->session->data['guest']['payment']['tax_id'])) {
			$this->data['tax_id'] = $this->session->data['guest']['payment']['tax_id'];			
		} else {
			$this->data['tax_id'] = '';
		}
								
		if (isset($this->session->data['guest']['payment']['address_1'])) {
			$this->data['address_1'] = $this->session->data['guest']['payment']['address_1'];			
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->session->data['guest']['payment']['address_2'])) {
			$this->data['address_2'] = $this->session->data['guest']['payment']['address_2'];			
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->session->data['guest']['payment']['postcode'])) {
			$this->data['postcode'] = $this->session->data['guest']['payment']['postcode'];							
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];			
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->session->data['guest']['payment']['city'])) {
			$this->data['city'] = $this->session->data['guest']['payment']['city'];			
		} else {
			$this->data['city'] = '';
		}

		if (isset($this->session->data['guest']['payment']['country_id'])) {
			$this->data['country_id'] = $this->session->data['guest']['payment']['country_id'];			  	
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {
			$this->data['country_id'] = C('config_country_id');
		}

		if (isset($this->session->data['guest']['payment']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];	
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];						
		} else {
			$this->data['zone_id'] = '';
		}
		
		$this->data['countries'] = cache_read('country.php');
		
		$this->data['shipping_required'] = $this->cart->hasShipping();
		
		if (isset($this->session->data['guest']['shipping_address'])) {
			$this->data['shipping_address'] = $this->session->data['guest']['shipping_address'];			
		} else {
			$this->data['shipping_address'] = true;
		}
		
		$this->display('checkout/guest.tpl');		
  	}
	
	public function validate() {
    	$this->language->load('checkout/checkout');

		$json = array();
		
		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['redirect'] = U('checkout/checkout', '', 'SSL');
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !C('config_stock_checkout'))) {
			$json['redirect'] = U('checkout/cart');		
		}
		
		// Check if guest checkout is avaliable.			
		if (!C('config_guest_checkout') || C('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = U('checkout/checkout', '', 'SSL');
		} 
					
		if (!$json) {
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = L('error_firstname');
			}
	
			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = L('error_lastname');
			}
	
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$json['error']['email'] = L('error_email');
			}
			
			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = L('error_telephone');
			}

			// Customer Group
			M('account/customer_group');
			
			if (isset($this->request->post['customer_group_id']) && is_array(C('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], C('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = C('config_customer_group_id');
			}
			
			$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
				
			if ($customer_group) {	
				// Company ID
				if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
					$json['error']['company_id'] = L('error_company_id');
				}
				
				// Tax ID
				if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
					$json['error']['tax_id'] = L('error_tax_id');
				}						
			}
						
			if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = L('error_address_1');
			}
	
			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
				$json['error']['city'] = L('error_city');
			}
			
			M('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info) {
				if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
					$json['error']['postcode'] = L('error_postcode');
				}
				
				// VAT Validation
				$this->load->helper('vat');
				
				if (C('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
					$json['error']['tax_id'] = L('error_vat');
				}					
			}
	
			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = L('error_country');
			}
			
			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
				$json['error']['zone'] = L('error_zone');
			}	
		}
			
		if (!$json) {
			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
			$this->session->data['guest']['fax'] = $this->request->post['fax'];
			
			$this->session->data['guest']['payment']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['payment']['lastname'] = $this->request->post['lastname'];				
			$this->session->data['guest']['payment']['company'] = $this->request->post['company'];
			$this->session->data['guest']['payment']['company_id'] = $this->request->post['company_id'];
			$this->session->data['guest']['payment']['tax_id'] = $this->request->post['tax_id'];
			$this->session->data['guest']['payment']['address_1'] = $this->request->post['address_1'];
			$this->session->data['guest']['payment']['address_2'] = $this->request->post['address_2'];
			$this->session->data['guest']['payment']['postcode'] = $this->request->post['postcode'];
			$this->session->data['guest']['payment']['city'] = $this->request->post['city'];
			$this->session->data['guest']['payment']['country_id'] = $this->request->post['country_id'];
			$this->session->data['guest']['payment']['zone_id'] = $this->request->post['zone_id'];
							
			M('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info) {
				$this->session->data['guest']['payment']['country'] = $country_info['name'];	
				$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['payment']['country'] = '';	
				$this->session->data['guest']['payment']['iso_code_2'] = '';
				$this->session->data['guest']['payment']['iso_code_3'] = '';
				$this->session->data['guest']['payment']['address_format'] = '';
			}
						
			M('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			
			if ($zone_info) {
				$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
				$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['payment']['zone'] = '';
				$this->session->data['guest']['payment']['zone_code'] = '';
			}
			
			if (!empty($this->request->post['shipping_address'])) {
				$this->session->data['guest']['shipping_address'] = true;
			} else {
				$this->session->data['guest']['shipping_address'] = false;
			}
			
			// Default Payment Address
			$this->session->data['payment_country_id'] = $this->request->post['country_id'];
			$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
			
			if ($this->session->data['guest']['shipping_address']) {
				$this->session->data['guest']['shipping']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['shipping']['lastname'] = $this->request->post['lastname'];
				$this->session->data['guest']['shipping']['company'] = $this->request->post['company'];
				$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
				$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
				$this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];
				$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
				$this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
				$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];
				
				if ($country_info) {
					$this->session->data['guest']['shipping']['country'] = $country_info['name'];	
					$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
				} else {
					$this->session->data['guest']['shipping']['country'] = '';	
					$this->session->data['guest']['shipping']['iso_code_2'] = '';
					$this->session->data['guest']['shipping']['iso_code_3'] = '';
					$this->session->data['guest']['shipping']['address_format'] = '';
				}
	
				if ($zone_info) {
					$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
					$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
				} else {
					$this->session->data['guest']['shipping']['zone'] = '';
					$this->session->data['guest']['shipping']['zone_code'] = '';
				}
				
				// Default Shipping Address
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
			}
			
			$this->session->data['account'] = 'guest';
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}
					
		$this->response->setOutput(json_encode($json));	
	}
	
  	public function zone() {
		$output = '<option value="">' . L('text_select') . '</option>';
		
		M('localisation/zone');

    	$results = cache_read($this->request->get['country_id'] . '.php', 'zone');
        if ($resutls) {
			$zone_id = $this->request->get('zone_id');
			$output .= form_select_option($results, $zone_id, null, 'zone_id', 'name');
		}
		
		if (!$results) {
		  	$output .= '<option value="0">' . L('text_none') . '</option>';
		}
	
		$this->response->setOutput($output);
  	}
}
?>