<?php 
class ControllerCheckoutShippingMethod extends Controller {
  	public function index() {
		$this->language->load('checkout/checkout');
		
		M('account/address');
		
		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}
		
		if (!empty($shipping_address)) {
			// Shipping Methods
			$quote_data = array();
			
			$extensions = C('cache_extension_shipping');
			
			foreach ($extensions as $code) {
				if (C($code . '_status')) {
					M('shipping/' . $code);
					
					$quote = $this->{'model_shipping_' . $code}->getQuote($shipping_address); 
		
					if ($quote) {
						$quote_data[$code] = array( 
							'title'      => $quote['title'],
							'quote'      => $quote['quote'], 
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}
	
			$sort_order = array();
		  
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $quote_data);
			
			$this->session->data['shipping_methods'] = $quote_data;
		}
					
		$this->data['text_shipping_method'] = L('text_shipping_method');
		$this->data['text_comments'] = L('text_comments');
	
		$this->data['button_continue'] = L('button_continue');
		
		if (empty($this->session->data['shipping_methods'])) {
			$this->data['error_warning'] = sprintf(L('error_no_shipping'), U('page/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	
					
		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$this->data['shipping_methods'] = array();
		}
		
		if (isset($this->session->data['shipping_method']['code'])) {
			$this->data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$this->data['code'] = '';
		}
		
		if (isset($this->session->data['comment'])) {
			$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
		$this->display('checkout/shipping_method.tpl');
  	}
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		$json = array();		
		
		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = U('checkout/checkout', '', 'SSL');
		}
		
		// Validate if shipping address has been set.		
		M('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}
		
		if (empty($shipping_address)) {								
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
			if (!isset($this->request->post['shipping_method'])) {
				$json['error']['warning'] = L('error_shipping');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);
					
				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$json['error']['warning'] = L('error_shipping');
				}
			}
			
			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method']);
					
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}							
		}
		
		$this->response->setOutput(json_encode($json));	
	}
}
?>