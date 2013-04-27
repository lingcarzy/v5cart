<?php
class ControllerReportCustomerOnline extends Controller {
  	public function index() {
		$this->language->load('report/customer_online');

    	$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$filter_ip = $qf->get('filter_ip');
		$filter_customer = $qf->get('filter_customer');
		$page = $qf->get('page', 1);

		M('report/online');
    	M('sale/customer');

		$this->data['customers'] = array();

		$data = array(
			'filter_ip'       => $filter_ip,
			'filter_customer' => $filter_customer,
			'start'           => ($page - 1) * 20,
			'limit'           => 20
		);

		$customer_total = $this->model_report_online->getTotalCustomersOnline($data);

		$results = $this->model_report_online->getCustomersOnline($data);

		foreach ($results as $result) {
			$action = array();

			if ($result['customer_id']) {
				$action[] = array(
					'text' => 'Edit',
					'href' => UA('sale/customer/update', 'customer_id=' . $result['customer_id'])
				);
			}

			$customer_info = $this->model_sale_customer->getCustomer($result['customer_id']);

			if ($customer_info) {
				$customer = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
			} else {
				$customer = L('text_guest');
			}

      		$this->data['customers'][] = array(
				'ip'         => $result['ip'],
				'customer'   => $customer,
				'url'        => $result['url'],
				'referer'    => $result['referer'],
				'date_added' => date('d/m/Y H:i:s', strtotime($result['date_added'])),
				'action'     => $action
			);
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = 20;
		$pagination->url = UA('report/customer_online', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_ip'] = $filter_ip;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/customer_online.tpl');
  	}
}
?>