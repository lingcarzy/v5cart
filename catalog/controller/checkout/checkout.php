<?php  
class ControllerCheckoutCheckout extends Controller { 
	public function index() {
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !C('config_stock_checkout'))) {
	  		$this->redirect(U('checkout/cart'));
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
				$this->redirect(U('checkout/cart'));
			}				
		}
				
		$this->language->load('checkout/checkout');
		
		$this->document->setTitle(L('heading_title')); 
		
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_cart'),
			'href'      => U('checkout/cart'),
        	'separator' => L('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('checkout/checkout', '', 'SSL'),
        	'separator' => L('text_separator')
      	);
					
	    $this->data['heading_title'] = L('heading_title');
		
		$this->data['text_checkout_option'] = L('text_checkout_option');
		$this->data['text_checkout_account'] = L('text_checkout_account');
		$this->data['text_checkout_payment_address'] = L('text_checkout_payment_address');
		$this->data['text_checkout_shipping_address'] = L('text_checkout_shipping_address');
		$this->data['text_checkout_shipping_method'] = L('text_checkout_shipping_method');
		$this->data['text_checkout_payment_method'] = L('text_checkout_payment_method');		
		$this->data['text_checkout_confirm'] = L('text_checkout_confirm');
		$this->data['text_modify'] = L('text_modify');
		
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['shipping_required'] = $this->cart->hasShipping();
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
		
		$this->display('checkout/checkout.tpl');
  	}
}
?>