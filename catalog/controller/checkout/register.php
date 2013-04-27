<?php 
class ControllerCheckoutRegister extends Controller {
  	public function index() {
		$this->language->load('checkout/checkout');
		
		$this->data['text_your_details'] = L('text_your_details');
		$this->data['text_your_address'] = L('text_your_address');
		$this->data['text_your_password'] = L('text_your_password');
		$this->data['text_select'] = L('text_select');
		$this->data['text_none'] = L('text_none');
						
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
		$this->data['entry_newsletter'] = sprintf(L('entry_newsletter'), C('config_name'));
		$this->data['entry_password'] = L('entry_password');
		$this->data['entry_confirm'] = L('entry_confirm');
		$this->data['entry_shipping'] = L('entry_shipping');

		$this->data['button_continue'] = L('button_continue');

		$this->data['customer_groups'] = array();
		
		if (is_array(C('config_customer_group_display'))) {
			M('account/customer_group');
			
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups  as $customer_group) {
				if (in_array($customer_group['customer_group_id'], C('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		$this->data['customer_group_id'] = C('config_customer_group_id');
		
		if (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];		
		} else {
			$this->data['postcode'] = '';
		}
		
    	if (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {	
      		$this->data['country_id'] = C('config_country_id');
    	}
		
    	if (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];			
		} else {
      		$this->data['zone_id'] = '';
    	}
		
		$this->data['countries'] = cache_read('country.php');

		if (C('config_account_id')) {
			M('catalog/page');
			
			$page_info = $this->model_catalog_page->getPage(C('config_account_id'));
			
			if ($page_info) {
				$this->data['text_agree'] = sprintf(L('text_agree'), U('page/index/info', 'page_id=' . C('config_account_id'), 'SSL'), $page_info['title'], $page_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		$this->data['shipping_required'] = $this->cart->hasShipping();
		
		$this->display('checkout/register.tpl');
  	}
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		M('account/customer');
		
		$json = array();
		
		// Validate if customer is already logged out.
		if ($this->customer->isLogged()) {
			$json['redirect'] = U('checkout/checkout', '', 'SSL');			
		}
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !C('config_stock_checkout'))) {
			$json['redirect'] = U('checkout/cart');
		}
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = U('checkout/cart');

				break;
			}				
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
	
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$json['error']['warning'] = L('error_exists');
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
	
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$json['error']['password'] = L('error_password');
			}
	
			if ($this->request->post['confirm'] != $this->request->post['password']) {
				$json['error']['confirm'] = L('error_confirm');
			}
			
			if (C('config_account_id')) {
				M('catalog/page');
				
				$page_info = $this->model_catalog_page->getPage(C('config_account_id'));
				
				if ($page_info && !isset($this->request->post['agree'])) {
					$json['error']['warning'] = sprintf(L('error_agree'), $page_info['title']);
				}
			}
		}
		
		if (!$json) {
			$this->model_account_customer->addCustomer($this->request->post);
			
			$this->session->data['account'] = 'register';
			
			if ($customer_group && !$customer_group['approval']) {
				$this->customer->login($this->request->post['email'], $this->request->post['password']);
				
				$this->session->data['payment_address_id'] = $this->customer->getAddressId();
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
									
				if (!empty($this->request->post['shipping_address'])) {
					$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
					$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
					$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
					$this->session->data['shipping_postcode'] = $this->request->post['postcode'];					
				}
			} else {
				$json['redirect'] = U('account/success');
			}
			
			unset($this->session->data['guest']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);	
			unset($this->session->data['payment_methods']);
		}	
		
		$this->response->setOutput(json_encode($json));	
	} 
}
?>