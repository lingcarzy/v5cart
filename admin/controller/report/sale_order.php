<?php
class ControllerReportSaleOrder extends Controller {
	public function index() {
		$this->language->load('report/sale_order');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01')));
		$filter_date_end = $qf->get('filter_date_end', date('Y-m-d'));
		$filter_group = $qf->get('filter_group', 'week');
		$filter_order_status_id = $qf->get('filter_order_status_id', 0);
		$page = $qf->get('page', 1);

		M('report/sale');

		$this->data['orders'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * C('config_admin_limit'),
			'limit'                  => C('config_admin_limit')
		);

		$order_total = $this->model_report_sale->getTotalOrders($data);

		$results = $this->model_report_sale->getOrders($data);

		foreach ($results as $result) {
			$this->data['orders'][] = array(
				'date_start' => date(L('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date(L('date_format_short'), strtotime($result['date_end'])),
				'orders'     => $result['orders'],
				'products'   => $result['products'],
				'tax'        => $this->currency->format($result['tax'], C('config_currency')),
				'total'      => $this->currency->format($result['total'], C('config_currency'))
			);
		}


		$this->data['order_statuses'] = C('cache_order_status');

		$this->data['groups'] = array();

		$this->data['groups'][] = array(
			'text'  => L('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => L('text_month'),
			'value' => 'month',
		);

		$this->data['groups'][] = array(
			'text'  => L('text_week'),
			'value' => 'week',
		);

		$this->data['groups'][] = array(
			'text'  => L('text_day'),
			'value' => 'day',
		);


		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/sale_order', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/sale_order.tpl');
	}
}
?>