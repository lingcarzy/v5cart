<?php
class ControllerReportSaleReturn extends Controller {
	public function index() {
		$this->language->load('report/sale_return');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', '');
		$filter_date_end = $qf->get('filter_date_end', '');
		$filter_group = $qf->get('filter_group', 'week');
		$filter_return_status_id = $qf->get('filter_return_status_id', 0);
		$page = $qf->get('page', 1);

		M('report/return');

		$this->data['returns'] = array();

		$data = array(
			'filter_date_start'	      => $filter_date_start,
			'filter_date_end'	      => $filter_date_end,
			'filter_group'            => $filter_group,
			'filter_return_status_id' => $filter_return_status_id,
			'start'                   => ($page - 1) * C('config_admin_limit'),
			'limit'                   => C('config_admin_limit')
		);

		$return_total = $this->model_report_return->getTotalReturns($data);

		$results = $this->model_report_return->getReturns($data);

		foreach ($results as $result) {
			$this->data['returns'][] = array(
				'date_start' => date(L('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date(L('date_format_short'), strtotime($result['date_end'])),
				'returns'    => $result['returns']
			);
		}

		M('localisation/return_status');
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

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
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/sale_return', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_return_status_id'] = $filter_return_status_id;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/sale_return.tpl');
	}
}
?>