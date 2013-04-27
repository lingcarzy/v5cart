<?php
class ModelTotalHandling extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (($this->cart->getSubTotal() < C('handling_total')) && ($this->cart->getSubTotal() > 0)) {
			$this->language->load('total/handling');
		 	
			$total_data[] = array( 
				'code'       => 'handling',
        		'title'      => L('text_handling'),
        		'text'       => $this->currency->format(C('handling_fee')),
        		'value'      => C('handling_fee'),
				'sort_order' => C('handling_sort_order')
			);

			if (C('handling_tax_class_id')) {
				$tax_rates = $this->tax->getRates(C('handling_fee'), C('handling_tax_class_id'));
				
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
			
			$total += C('handling_fee');
		}
	}
}
?>