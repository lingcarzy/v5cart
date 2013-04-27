<?php 
class ModelPaymentFreeCheckout extends Model {
  	public function getMethod($address, $total) {
		$this->language->load('payment/free_checkout');
		
		if ($total <= 0) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
			
		if ($status) {  
			$method_data = array( 
				'code'       => 'free_checkout',
				'title'      => L('text_title'),
				'sort_order' => C('free_checkout_sort_order')
			);
		}
		
    	return $method_data;
  	}
}
?>