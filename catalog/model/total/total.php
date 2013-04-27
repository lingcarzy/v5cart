<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/total');
	 
		$total_data[] = array(
			'code'       => 'total',
			'title'      => L('text_total'),
			'text'       => $this->currency->format(max(0, $total)),
			'value'      => max(0, $total),
			'sort_order' => C('total_sort_order')
		);
	}
}
?>