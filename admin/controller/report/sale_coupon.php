<?php
class ControllerReportSaleCoupon extends Controller {
	public function index() {
		$this->language->load('report/sale_coupon');

		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_date_start = $qf->get('filter_date_start', '');
		$filter_date_end = $qf->get('filter_date_end', '');
		$page = $qf->get('page', 1);

		M('report/coupon');

		$this->data['coupons'] = array();

		$data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * C('config_admin_limit'),
			'limit'             => C('config_admin_limit')
		);

		$coupon_total = $this->model_report_coupon->getTotalCoupons($data);

		$results = $this->model_report_coupon->getCoupons($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/coupon/update', 'coupon_id=' . $result['coupon_id'])
			);

			$this->data['coupons'][] = array(
				'name'   => $result['name'],
				'code'   => $result['code'],
				'orders' => $result['orders'],
				'total'  => $this->currency->format($result['total'], C('config_currency')),
				'action' => $action
			);
		}


		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/sale_coupon', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/sale_coupon.tpl');
	}
}
?>