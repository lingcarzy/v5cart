<?php
class ControllerReportCustomerReward extends Controller {
	public function index() {
		$this->language->load('report/customer_reward');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', '');
		$filter_date_end = $qf->get('filter_date_end', '');
		$page = $qf->get('page', 1);

		M('report/customer');

		$this->data['customers'] = array();

		$data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * C('config_admin_limit'),
			'limit'             => C('config_admin_limit')
		);

		$customer_total = $this->model_report_customer->getTotalRewardPoints($data);

		$results = $this->model_report_customer->getRewardPoints($data);

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
				'points'         => $result['points'],
				'orders'         => $result['orders'],
				'total'          => $this->currency->format($result['total'], C('config_currency')),
				'action'         => $action
			);
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/customer_reward', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/customer_reward.tpl');
	}
}
?>