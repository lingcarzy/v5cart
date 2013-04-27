<?php
class ModelTotalLowOrderFee extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < C('low_order_fee_total'))) {
			$this->language->load('total/low_order_fee');
		 	
			$total_data[] = array( 
				'code'       => 'low_order_fee',
        		'title'      => L('text_low_order_fee'),
        		'text'       => $this->currency->format(C('low_order_fee_fee')),
        		'value'      => C('low_order_fee_fee'),
				'sort_order' => C('low_order_fee_sort_order')
			);
			
			if (C('low_order_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates(C('low_order_fee_fee'), C('low_order_fee_tax_class_id'));
				
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
			
			$total += C('low_order_fee_fee');
		}
	}
}
?>