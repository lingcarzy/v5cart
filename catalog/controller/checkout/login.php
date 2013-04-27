<?php  
class ControllerCheckoutLogin extends Controller { 
	public function index() {
		$this->language->load('checkout/checkout');
		
		$this->data['text_new_customer'] = L('text_new_customer');
		$this->data['text_returning_customer'] = L('text_returning_customer');
		$this->data['text_checkout'] = L('text_checkout');
		$this->data['text_register'] = L('text_register');
		$this->data['text_guest'] = L('text_guest');
		$this->data['text_i_am_returning_customer'] = L('text_i_am_returning_customer');
		$this->data['text_register_account'] = L('text_register_account');
		$this->data['text_forgotten'] = L('text_forgotten');
 
		$this->data['entry_email'] = L('entry_email');
		$this->data['entry_password'] = L('entry_password');
		
		$this->data['button_continue'] = L('button_continue');
		$this->data['button_login'] = L('button_login');
		
		$this->data['guest_checkout'] = (C('config_guest_checkout') && !C('config_customer_price') && !$this->cart->hasDownload());
		
		if (isset($this->session->data['account'])) {
			$this->data['account'] = $this->session->data['account'];
		} else {
			$this->data['account'] = 'register';
		}
		
		$this->data['forgotten'] = U('account/forgotten', '', 'SSL');
		
		$this->display('checkout/login.tpl');
	}
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		if ($this->customer->isLogged()) {
			$json['redirect'] = U('checkout/checkout', '', 'SSL');			
		}
		
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !C('config_stock_checkout'))) {
			$json['redirect'] = U('checkout/cart');
		}	
		
		if (!$json) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$json['error']['warning'] = L('error_login');
			}
		
			M('account/customer');
		
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
			
			if ($customer_info && !$customer_info['approved']) {
				$json['error']['warning'] = L('error_approved');
			}		
		}
		
		if (!$json) {
			unset($this->session->data['guest']);
				
			// Default Addresses
			M('account/address');
				
			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
									
			if ($address_info) {
				if (C('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_country_id'] = $address_info['country_id'];
					$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
					$this->session->data['shipping_postcode'] = $address_info['postcode'];	
				}
				
				if (C('config_tax_customer') == 'payment') {
					$this->session->data['payment_country_id'] = $address_info['country_id'];
					$this->session->data['payment_zone_id'] = $address_info['zone_id'];
				}
			} else {
				unset($this->session->data['shipping_country_id']);	
				unset($this->session->data['shipping_zone_id']);	
				unset($this->session->data['shipping_postcode']);
				unset($this->session->data['payment_country_id']);	
				unset($this->session->data['payment_zone_id']);	
			}					
				
			$json['redirect'] = U('checkout/checkout', '', 'SSL');
		}
					
		$this->response->setOutput(json_encode($json));		
	}
}
?>