<?php
class ControllerReportAffiliateCommission extends Controller {
	public function index() {
		$this->language->load('report/affiliate_commission');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', '');
		$filter_date_end = $qf->get('filter_date_end', '');
		$page = $qf->get('page', 1);

		M('report/affiliate');

		$this->data['affiliates'] = array();

		$data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * C('config_admin_limit'),
			'limit'             => C('config_admin_limit')
		);

		$affiliate_total = $this->model_report_affiliate->getTotalCommission($data);

		$results = $this->model_report_affiliate->getCommission($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/affiliate/update', 'affiliate_id=' . $result['affiliate_id'])
			);

			$this->data['affiliates'][] = array(
				'affiliate'  => $result['affiliate'],
				'email'      => $result['email'],
				'status'     => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'commission' => $this->currency->format($result['commission'], C('config_currency')),
				'orders'     => $result['orders'],
				'total'      => $this->currency->format($result['total'], C('config_currency')),
				'action'     => $action
			);
		}

		$pagination = new Pagination();
		$pagination->total = $affiliate_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/affiliate_commission', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/affiliate_commission.tpl');
	}
}
?>