<?php

class ControllerSaleOrderBasket extends Controller {

	public function index() {
		$this->language->load('sale/order_basket');
		$this->document->setTitle(L('heading_title'));
		M('sale/order_basket');

    	$this->data['success'] = $this->session->flashdata('success');

		$page = $this->request->get('page', 1);
		$basket_id = $this->request->get('basket_id', '');
		$order_id = $this->request->get('order_id', '');

		$this->data['basket_id'] = $basket_id;
		$this->data['order_id'] = $order_id;

		//query
		$data = array(
			'basket_id' => $basket_id,
			'order_id' => $order_id,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$this->data['baskets'] = $this->model_sale_order_basket->getBaskets($data, $total);

 		//pagination
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/order_audit', 'page={page}');
		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('sale/order_basket_list.tpl');
  	}

	public function insert() {
		if (!$this->user->hasPermission('modify', 'sale/order_basket')) {
			$this->redirect(UA('sale/order_basket'));
		}

		if ($this->request->isPost()) {
			$data['order_status_id'] = P('filter_order_status_id');
			//$data['order_id'] = P('filter_order_id');
			$data['start_date'] = P('filter_date_start');
			$data['end_date'] = P('filter_date_end');
			$data['remark'] = '';
			M('sale/order_basket');
			$this->model_sale_order_basket->addBasket($data);
			$this->redirect(UA('sale/order_basket'));
		}

		$this->language->load('sale/order_basket');
		$this->document->setTitle(L('heading_title'));
		M('sale/order');

		$page = $this->request->get('page', 1);
		//$filter_order_id = $this->request->get('filter_order_id');
		$filter_date_start = $this->request->get('filter_date_start');
		$filter_date_end = $this->request->get('filter_date_end');
		$filter_order_status_id = $this->request->get('filter_order_status_id');
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		//$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_order_status_id'] = $filter_order_status_id;

		$data = array(
			'filter_order_status_id' => $filter_order_status_id,
			//'filter_order_id' => $filter_order_id,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'filter_basket_id' => 0,
			'sort' => 'o.order_id',
			'order' => 'DESC',
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		M('sale/order');
		$order_total = $this->model_sale_order->getTotalOrders($data);

		$this->data['order_statuses'] = C('cache_order_status');

		$this->data['orders'] = array();
		$results = $this->model_sale_order->getOrders($data);

    	foreach ($results as $result) {
			$this->data['orders'][] = array(
			'order_id'      => $result['order_id'],
			'customer'      => $result['customer'],
			'status'        => $result['status'],
			'payment_method'        => $result['payment_method'],
			'shipping_method'        => $result['shipping_method'],
			'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
			'date_added'    => date('d/m/Y H:i', strtotime($result['date_added'])),
			'date_modified' => date('d/m/Y H:i', strtotime($result['date_modified']))
			);
		}

		$query_params = $this->request->query('filter_date_start, filter_date_end, filter_order_id,filter_order_status_id');
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/purchase/insert', $query_params . '&page={page}');
		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('sale/order_basket_insert.tpl');
	}

	public function orders() {
		$this->language->load('sale/order_basket');
		$this->document->setTitle(L('heading_title'));

		$basket_id = $this->request->get('basket_id');

		if ($this->request->isPost()) {
			M('sale/order_basket');
			$order_ids = P('selected');
			if (!empty($order_ids)) {
				$this->model_sale_order_basket->deleteOrder($basket_id, $order_ids);
			}
			$order_id = P('order_id');
			if ($order_id) {
				$this->model_sale_order_basket->addOrder($basket_id, $order_id);
			}
			$this->data['success'] = L('text_success');
		}


		M('sale/order');

		//order status
		$this->data['order_statuses'] = C('cache_order_status');

		$this->load->helper('query_filter');
		$qf = new Query_filter('order_basket_filter');
		$filter_order_id = $qf->get('filter_order_id');
		$filter_customer = $qf->get('filter_customer');
		$filter_order_status_id = $qf->get('filter_order_status_id');
		$filter_date_start = $qf->get('filter_date_start');
		$filter_date_end = $qf->get('filter_date_end');
		$filter_model = $qf->get('filter_model');
		$page = $qf->get('page', 1);

		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_model'] = $filter_model;
		$this->data['basket_id'] = $basket_id;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_order_status_id'] = $filter_order_status_id;

		$data = array(
			'filter_order_id' => $filter_order_id,
			'filter_date_start' => $filter_date_start,
			'filter_date_end' => $filter_date_end,
			'filter_basket_id' => $basket_id,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_model' =>  $filter_model,
			'filter_customer' =>  $filter_customer,
			'sort' => 'o.order_id',
			'order' => 'DESC',
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		M('sale/order');
		$order_total = $this->model_sale_order->getTotalOrders($data);

		$this->data['orders'] = array();
		$results = $this->model_sale_order->getOrders($data);

    	foreach ($results as $result) {
			$this->data['orders'][] = array(
			'order_id'      => $result['order_id'],
			'customer'      => $result['customer'],
			'status'        => $result['status'],
			'payment_method'        => $result['payment_method'],
			'shipping_method'        => $result['shipping_method'],
			'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
			'date_added'    => date('d/m/Y H:i', strtotime($result['date_added'])),
			'date_modified' => date('d/m/Y H:i', strtotime($result['date_modified']))
			);
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/order_basket/orders', "basket_id={$basket_id}&page={page}");
		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('sale/order_basket_orders.tpl');
	}

	public function skus() {

		$this->document->setTitle('SKU');

		$basket_id = $this->request->get('basket_id');

		M('catalog/supplier');
		$suppliers = $this->model_catalog_supplier->getSupplierOptions();

		$this->data['skus'] = array();

		M('tool/image');

		M('sale/order_basket');
		$results = $this->model_sale_order_basket->getSKUs($basket_id);

		foreach($results as $r) {
			if ($r['image'] && file_exists(DIR_IMAGE . $r['image'])) {
				$r['image'] = $this->model_tool_image->resize($r['image'], 100, 100);
			} else {
				$r['image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			if ($r['supplier_id'] && isset($suppliers[$r['supplier_id']])) {
				$r['supplier'] = $suppliers[$r['supplier_id']];
			}
			else {
				$r['supplier'] = '';
			}
			$this->data['skus'][] = $r;
		}
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('sale/order_basket_skus.tpl');
	}

	public function edit() {
		$basket_id = $this->request->get('basket_id');
		M('sale/order_basket');
		if ($this->request->isPost()) {
			$data = array(
				'status' => P('status'),
				'remark' => P('remark')
			);
			$this->db->update('order_basket', $data, array('basket_id' => $basket_id));
			$this->redirect(UA('sale/order_basket'));
		}
		$this->language->load('sale/order_basket');
		$this->document->setTitle(L('heading_title'));

		$this->data['basket'] = $this->model_sale_order_basket->getBasket($basket_id);

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('sale/order_basket_edit.tpl');
	}
}