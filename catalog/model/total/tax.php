<?php
class ModelTotalTax extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		foreach ($taxes as $key => $value) {
			if ($value > 0) {
				$total_data[] = array(
					'code'       => 'tax',
					'title'      => $this->tax->getRateName($key), 
					'text'       => $this->currency->format($value),
					'value'      => $value,
					'sort_order' => C('tax_sort_order')
				);

				$total += $value;
			}
		}
	}
}
?>