<?php
class ControllerReportCustomerOrder extends Controller {
	public function index() {
		$this->language->load('report/customer_order');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', '');
		$filter_date_end = $qf->get('filter_date_end', '');
		$filter_order_status_id = $qf->get('filter_order_status_id', 0);
		$page = $qf->get('page', 1);

		M('report/customer');

		$this->data['customers'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * C('config_admin_limit'),
			'limit'                  => C('config_admin_limit')
		);

		$customer_total = $this->model_report_customer->getTotalOrders($data);

		$results = $this->model_report_customer->getOrders($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/customer/update', 'customer_id=' . $result['customer_id'])
			);

			$this->data['customers'][] = array(
				'customer'       => $result['customer'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'orders'         => $result['orders'],
				'products'       => $result['products'],
				'total'          => $this->currency->format($result['total'], C('config_currency')),
				'action'         => $action
			);
		}

		$this->data['order_statuses'] = C('cache_order_status');

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/customer_order', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_order_status_id'] = $filter_order_status_id;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/customer_order.tpl');
	}
}
?>