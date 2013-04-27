<?php
class ControllerReportProductPurchased extends Controller {
	public function index() {
		$this->language->load('report/product_purchased');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', '');
		$filter_date_end = $qf->get('filter_date_end', '');
		$filter_order_status_id = $qf->get('filter_order_status_id', 0);
		$page = $qf->get('page', 1);

		M('report/product');

		$this->data['products'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * C('config_admin_limit'),
			'limit'                  => C('config_admin_limit')
		);

		$product_total = $this->model_report_product->getTotalPurchased($data);

		$results = $this->model_report_product->getPurchased($data);

		foreach ($results as $result) {
			$this->data['products'][] = array(
				'name'       => $result['name'],
				'model'      => $result['model'],
				'quantity'   => $result['quantity'],
				'total'      => $this->currency->format($result['total'], C('config_currency'))
			);
		}


		$this->data['order_statuses'] = C('cache_order_status');

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/product_purchased', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_order_status_id'] = $filter_order_status_id;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/product_purchased.tpl');
	}
}
?>