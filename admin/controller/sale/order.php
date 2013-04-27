<?php
class ControllerSaleOrder extends Controller {

  	public function index() {
		$this->language->load('sale/order');
		M('sale/order');
    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('sale/order');
		M('sale/order');

		if ($this->request->isPost() && $this->validateForm()) {
      	  	$this->model_sale_order->addOrder($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/order'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('sale/order');
		M('sale/order');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/order'));
		}

    	$this->getForm();
  	}

  	public function delete() {

		$this->language->load('sale/order');
		M('sale/order');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/order'));
    	}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter('order_query_filter');
		$filter_order_id = $qf->get('filter_order_id');
		$filter_customer = $qf->get('filter_customer');
		$filter_order_status_id = $qf->get('filter_order_status_id');
		$filter_total = $qf->get('filter_total');
		$filter_date_start = $qf->get('filter_date_start');
		$filter_date_end = $qf->get('filter_date_end');
		$filter_model = $qf->get('filter_model');
		$sort = $qf->get('sort', 'o.order_id');
		$order = $qf->get('order', 'DESC');
		$page = $qf->get('page', 1);

		$this->data['orders'] = array();

		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'	     => $filter_customer,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
			'filter_model'           => $filter_model,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * C('config_admin_limit'),
			'limit'                  => C('config_admin_limit')
		);

		$order_total = $this->model_sale_order->getTotalOrders($data);

		$results = $this->model_sale_order->getOrders($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_view'),
				'href' => UA('sale/order/info', 'order_id=' . $result['order_id'])
			);

			if (strtotime($result['date_added']) > strtotime('-' . (int)C('config_order_edit') . ' day')) {
				$action[] = array(
					'text' => L('text_edit'),
					'href' => UA('sale/order/update', 'order_id=' . $result['order_id'])
				);
			}

			$this->data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'payment_method'        => $result['payment_method'],
				'shipping_method'        => $result['shipping_method'],
				'email'         => $result['email'],
				'city'          => $result['shipping_city'],
				'country'       => $result['shipping_country'],
				'invoice_no'    => $result['invoice_no'],
				'invoice_prefix' => $result['invoice_prefix'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date('d/m/Y H:i', strtotime($result['date_added'])),
				'date_modified' => date('d/m/Y H:i', strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		$this->data['sort_order'] = UA('sale/order', 'sort=o.order_id' . $url);
		$this->data['sort_customer'] = UA('sale/order', 'sort=customer' . $url);
		$this->data['sort_status'] = UA('sale/order', 'sort=status' . $url);
		$this->data['sort_total'] = UA('sale/order', 'sort=o.total' . $url);
		$this->data['sort_date_added'] = UA('sale/order',  'sort=o.date_added' . $url);
		$this->data['sort_date_modified'] = UA('sale/order', 'sort=o.date_modified' . $url);

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/order', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_model'] = $filter_model;
		$this->data['order_statuses'] = C('cache_order_status');

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/order_list.tpl');
  	}

  	public function getForm() {
		$this->document->setTitle(L('heading_title'));

		M('sale/customer');

		$order_id = G('order_id', 0);
		if ($order_id && !$this->request->isPost()) {
      		$order_info = $this->model_sale_order->getOrder($order_id);
    	}
		$this->data['order_id'] = $order_id;

		if ($order_id) {
			$this->data['action'] = UA('sale/order/update', 'order_id=' . $order_id);
		} else {
			$this->data['action'] = UA('sale/order/insert');
		}

		M('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		M('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->data['order_statuses'] = C('cache_order_status');

    	if (!empty($order_info)) {
			$this->data['store_id'] = $order_info['store_id'];
			$this->data['customer'] = $order_info['customer'];
			$this->data['customer_id'] = $order_info['customer_id'];
			$this->data['customer_group_id'] = $order_info['customer_group_id'];
			$this->data['firstname'] = $order_info['firstname'];
			$this->data['lastname'] = $order_info['lastname'];
			$this->data['email'] = $order_info['email'];
			$this->data['telephone'] = $order_info['telephone'];
			$this->data['fax'] = $order_info['fax'];
			$this->data['remark'] = $order_info['remark'];
			$this->data['affiliate_id'] = $order_info['affiliate_id'];
			$this->data['affiliate'] = ($order_info['affiliate_id'] ? $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'] : '');
			$this->data['order_status_id'] = $order_info['order_status_id'];
			$this->data['comment'] = $order_info['comment'];
			$this->data['payment_firstname'] = $order_info['payment_firstname'];
			$this->data['payment_lastname'] = $order_info['payment_lastname'];
			$this->data['payment_company'] = $order_info['payment_company'];
			$this->data['payment_company_id'] = $order_info['payment_company_id'];
			$this->data['payment_tax_id'] = $order_info['payment_tax_id'];
			$this->data['payment_address_1'] = $order_info['payment_address_1'];
			$this->data['payment_address_2'] = $order_info['payment_address_2'];
			$this->data['payment_city'] = $order_info['payment_city'];
			$this->data['payment_postcode'] = $order_info['payment_postcode'];
			$this->data['payment_country_id'] = $order_info['payment_country_id'];
			$this->data['payment_zone_id'] = $order_info['payment_zone_id'];
			$this->data['payment_method'] = $order_info['payment_method'];
			$this->data['payment_code'] = $order_info['payment_code'];
			$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
			$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
			$this->data['shipping_company'] = $order_info['shipping_company'];
			$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
			$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
			$this->data['shipping_city'] = $order_info['shipping_city'];
			$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
			$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
			$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$this->data['shipping_method'] = $order_info['shipping_method'];
			$this->data['shipping_code'] = $order_info['shipping_code'];

		} else {
      		$this->data['store_id'] = P('store_id');
			$this->data['customer'] = P('customer');
			$this->data['customer_id'] = P('customer_id');
			$this->data['customer_group_id'] = P('customer_group_id');
			$this->data['firstname'] = P('firstname');
			$this->data['lastname'] = P('lastname');
			$this->data['email'] = P('email');
			$this->data['telephone'] = P('telephone');
			$this->data['fax'] = P('fax');
			$this->data['remark'] = P('remark');
			$this->data['affiliate_id'] = P('affiliate_id');
			$this->data['affiliate'] = P('affiliate');
			$this->data['order_status_id'] = P('order_status_id');
			$this->data['comment'] = P('comment');
			$this->data['payment_firstname'] = P('payment_firstname');
			$this->data['payment_lastname'] = P('payment_lastname');
			$this->data['payment_company'] = P('payment_company');
			$this->data['payment_company_id'] = P('payment_company_id');
			$this->data['payment_tax_id'] = P('payment_tax_id');
			$this->data['payment_address_1'] = P('payment_address_1');
			$this->data['payment_address_2'] = P('payment_address_2');
			$this->data['payment_city'] = P('payment_city');
			$this->data['payment_postcode'] = P('payment_postcode');
			$this->data['payment_country_id'] = P('payment_country_id');
			$this->data['payment_zone_id'] = P('payment_zone_id');
			$this->data['payment_method'] = P('payment_method');
			$this->data['payment_code'] = P('payment_code');
			$this->data['shipping_firstname'] = P('shipping_firstname');
			$this->data['shipping_lastname'] = P('shipping_lastname');
			$this->data['shipping_company'] = P('shipping_company');
			$this->data['shipping_address_1'] = P('shipping_address_1');
			$this->data['shipping_address_2'] = P('shipping_address_2');
			$this->data['shipping_city'] = P('shipping_city');
			$this->data['shipping_postcode'] = P('shipping_postcode');
			$this->data['shipping_country_id'] = P('shipping_country_id');
			$this->data['shipping_zone_id'] = P('shipping_zone_id');
			$this->data['shipping_method'] = P('shipping_method');
			$this->data['shipping_code'] = P('shipping_code');
    	}

		M('sale/customer');

		if (isset($this->request->post['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
		} elseif (!empty($order_info)) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($order_info['customer_id']);
		} else {
			$this->data['addresses'] = array();
		}

		$this->data['countries'] = cache_read('country.php');

		if (isset($this->request->post['order_product'])) {
			$order_products = $this->request->post['order_product'];
		} elseif ($order_id) {
			$order_products = $this->model_sale_order->getOrderProducts($order_id);
		} else {
			$order_products = array();
		}

		M('catalog/product');

		$this->document->addScript('view/javascript/jquery/ajaxupload.js');

		$this->data['order_products'] = array();

		foreach ($order_products as $order_product) {
			if (isset($order_product['order_option'])) {
				$order_option = $order_product['order_option'];
			} elseif ($order_id) {
				$order_option = $this->model_sale_order->getOrderOptions($order_id, $order_product['order_product_id']);
			} else {
				$order_option = array();
			}

			if (isset($order_product['order_download'])) {
				$order_download = $order_product['order_download'];
			} elseif ($order_id) {
				$order_download = $this->model_sale_order->getOrderDownloads($order_id, $order_product['order_product_id']);
			} else {
				$order_download = array();
			}
			$this->data['order_products'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'name'             => $order_product['name'],
				'model'            => $order_product['model'],
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'price'            => $order_product['price'],
				'total'            => $order_product['total'],
				'tax'              => $order_product['tax'],
				'reward'           => $order_product['reward']
			);
		}

		if (isset($this->request->post['order_voucher'])) {
			$this->data['order_vouchers'] = $this->request->post['order_voucher'];
		} elseif ($order_id) {
			$this->data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($order_id);
		} else {
			$this->data['order_vouchers'] = array();
		}

		M('sale/voucher_theme');
		$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		if (isset($this->request->post['order_total'])) {
      		$this->data['order_totals'] = $this->request->post['order_total'];
    	} elseif ($order_id) {
			$this->data['order_totals'] = $this->model_sale_order->getOrderTotals($order_id);
		} else {
      		$this->data['order_totals'] = array();
    	}

		$this->template = '';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/order_form.tpl');
  	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));
		$this->form_validation->set_rules('email', '', 'required|email|max_length[96]', L('error_email'));
		$this->form_validation->set_rules('telephone', '', 'required|range_length[3,32]', L('error_telephone'));
		$this->form_validation->set_rules('payment_firstname', '', 'required|range_length[1,32]', L('error_firstname'));
		$this->form_validation->set_rules('payment_lastname', '', 'required|range_length[1,32]', L('error_lastname'));
		$this->form_validation->set_rules('payment_address_1', '', 'required|range_length[3,128]', L('error_address_1'));
		$this->form_validation->set_rules('payment_city', '', 'required|range_length[3,128]', L('error_city'));

		M('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);

		if ($country_info) {
			if ($country_info['postcode_required']) {
				$this->form_validation->set_rules('payment_postcode', '', 'required|range_length[2,10]', L('error_postcode'));
			}

			// VAT Validation
			$this->load->helper('vat');

			if (C('config_vat') && $this->request->post['payment_tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['payment_tax_id']) == 'invalid')) {
				$this->setMessage('error_payment_tax_id', L('error_vat'));
			}
		}
		$this->form_validation->set_rules('payment_country_id', '', 'required', L('error_country'));
		$this->form_validation->set_rules('payment_zone_id', '', 'required', L('error_zone'));
		$this->form_validation->set_rules('payment_method', '', 'required', L('error_payment'));

		// Check if any products require shipping
		$shipping = false;

		if (isset($this->request->post['order_product'])) {
			M('catalog/product');

			foreach ($this->request->post['order_product'] as $order_product) {
				$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);

				if ($product_info && $product_info['shipping']) {
					$shipping = true;
				}
			}
		}

		if ($shipping) {
			$this->form_validation->set_rules('shipping_firstname', '', 'required|range_length[1,32]', L('error_firstname'));
			$this->form_validation->set_rules('shipping_lastname', '', 'required|range_length[1,32]', L('error_lastname'));
			$this->form_validation->set_rules('shipping_address_1', '', 'required|range_length[3,128]', L('error_address_1'));
			$this->form_validation->set_rules('shipping_city', '', 'required|range_length[3,128]', L('error_city'));

			M('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

			if ($country_info && $country_info['postcode_required']) {
				$this->form_validation->set_rules('shipping_postcode', '', 'required|range_length[2,10]', L('error_postcode'));
			}
			$this->form_validation->set_rules('shipping_country_id', '', 'required', L('error_country'));
			$this->form_validation->set_rules('shipping_zone_id', '', 'required', L('error_zone'));
			$this->form_validation->set_rules('shipping_method', '', 'required', L('error_shipping'));
		}
		if ($this->form_validation->run()) {
			return true;
		}
		else {
			$this->setMessage('error_warning', L('error_warning'));
			return false;
		}
  	}

   	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		return true;
  	}

	public function info() {
		M('sale/order');

		$order_id = $this->request->get('order_id', 0);
		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) $this->redirect(UA('error/not_found'));

		$this->language->load('sale/order');

		$this->document->setTitle(L('heading_title'));

		$this->data['invoice'] = UA('sale/order/invoice', 'order_id=' . $order_id);

		$this->data['order_id'] = $order_id;
		
		//previous order
		$previous = $this->db->queryOne("SELECT order_id FROM @@order WHERE order_id < $order_id AND order_status_id > 0 ORDER BY order_id DESC");
		if ($previous) {
			$this->data['previous'] = UA('sale/order/info', 'order_id=' . $previous);
		}
		//next order
		$next = $this->db->queryOne("SELECT order_id FROM @@order WHERE order_id > $order_id AND order_status_id > 0 ORDER BY order_id");
		if ($next) {
			$this->data['next'] = UA('sale/order/info', 'order_id=' . $next);
		}
		
		M('localisation/carrier');
		$this->data['carriers'] = $this->model_localisation_carrier->getCarriers();

		if ($order_info['invoice_no']) {
			$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$this->data['invoice_no'] = '';
		}

		$this->data['store_name'] = $order_info['store_name'];
		$this->data['store_url'] = $order_info['store_url'];
		$this->data['firstname'] = $order_info['firstname'];
		$this->data['lastname'] = $order_info['lastname'];

		if ($order_info['customer_id']) {
			$this->data['customer'] = UA('sale/customer/update', 'customer_id=' . $order_info['customer_id']);
		} else {
			$this->data['customer'] = '';
		}

		M('sale/customer_group');

		$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

		if ($customer_group_info) {
			$this->data['customer_group'] = $customer_group_info['name'];
		} else {
			$this->data['customer_group'] = '';
		}

		$this->data['email'] = $order_info['email'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['fax'] = $order_info['fax'];
		$this->data['comment'] = nl2br($order_info['comment']);
		$this->data['shipping_method'] = $order_info['shipping_method'];
		$this->data['payment_method'] = $order_info['payment_method'];
		$this->data['payment_payer_status'] = $order_info['payment_payer_status'];
		$this->data['payment_transaction_id'] = $order_info['payment_transaction_id'];
		$this->data['payment_type'] = $order_info['payment_type'];
		$this->data['payment_fee_amt'] = $order_info['payment_fee_amt'];
		$this->data['payment_status'] = $order_info['payment_status'];
		$this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

		if ($order_info['total'] < 0) {
			$this->data['credit'] = $order_info['total'];
		} else {
			$this->data['credit'] = 0;
		}

		M('sale/customer');

		$this->data['credit_total'] = $this->model_sale_customer->getTotalTransactionsByOrderId($order_id);

		$this->data['reward'] = $order_info['reward'];

		$this->data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_id);

		$this->data['affiliate_firstname'] = $order_info['affiliate_firstname'];
		$this->data['affiliate_lastname'] = $order_info['affiliate_lastname'];

		if ($order_info['affiliate_id']) {
			$this->data['affiliate'] = UA('sale/affiliate/update', 'affiliate_id=' . $order_info['affiliate_id']);
		} else {
			$this->data['affiliate'] = '';
		}

		$this->data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);

		M('sale/affiliate');

		$this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId($order_id);

		$this->data['order_statuses'] = C('cache_order_status');

		if (isset($this->data['order_statuses'][$order_info['order_status_id']])) {
			$this->data['order_status'] = $this->data['order_statuses'][$order_info['order_status_id']];
		} else {
			$this->data['order_status'] = '';
		}

		$this->data['ip'] = $order_info['ip'];
		$this->data['forwarded_ip'] = $order_info['forwarded_ip'];
		$this->data['user_agent'] = $order_info['user_agent'];
		$this->data['accept_language'] = $order_info['accept_language'];
		$this->data['date_added'] = date(L('date_format_short'), strtotime($order_info['date_added']));
		$this->data['date_modified'] = date(L('date_format_short'), strtotime($order_info['date_modified']));
		$this->data['payment_firstname'] = $order_info['payment_firstname'];
		$this->data['payment_lastname'] = $order_info['payment_lastname'];
		$this->data['payment_company'] = $order_info['payment_company'];
		$this->data['payment_company_id'] = $order_info['payment_company_id'];
		$this->data['payment_tax_id'] = $order_info['payment_tax_id'];
		$this->data['payment_address_1'] = $order_info['payment_address_1'];
		$this->data['payment_address_2'] = $order_info['payment_address_2'];
		$this->data['payment_city'] = $order_info['payment_city'];
		$this->data['payment_postcode'] = $order_info['payment_postcode'];
		$this->data['payment_zone'] = $order_info['payment_zone'];
		$this->data['payment_zone_code'] = $order_info['payment_zone_code'];
		$this->data['payment_country'] = $order_info['payment_country'];
		$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
		$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
		$this->data['shipping_company'] = $order_info['shipping_company'];
		$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
		$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
		$this->data['shipping_city'] = $order_info['shipping_city'];
		$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
		$this->data['shipping_zone'] = $order_info['shipping_zone'];
		$this->data['shipping_zone_code'] = $order_info['shipping_zone_code'];
		$this->data['shipping_country'] = $order_info['shipping_country'];

		$this->data['remark'] = $order_info['remark'];

		$this->data['products'] = array();

		$products = $this->model_sale_order->getOrderProducts($order_id);
		
		M('tool/image');
		
		foreach ($products as $product) {
		
			if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
				$image = $this->model_tool_image->resize($product['image'], 100, 100);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			
			$option_data = array();

			$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value'],
						'type'  => $option['type']
					);
				} else {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
						'type'  => $option['type'],
						'href'  => UA('sale/order/download', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . '&order_option_id=' . $option['order_option_id'], 'SSL')
					);
				}
			}

			$this->data['products'][$product['product_id']] = array(
				'order_product_id' => $product['order_product_id'],
				'product_id'       => $product['product_id'],
				'name'    	 	   => $product['name'],
				'model'    		   => $product['model'],
				'image'            => $image,
				'option'   		   => $option_data,
				'quantity'		   => $product['quantity'],
				'shipped_qty'	   => $product['shipped_qty'],
				'price'    		   => $this->currency->format($product['price'] + (C('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    		   => $this->currency->format($product['total'] + (C('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'href'     		   => U('product/product', 'product_id=' . $product['product_id'])
			);
		}

		$this->data['vouchers'] = array();

		$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

		foreach ($vouchers as $voucher) {
			$this->data['vouchers'][] = array(
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'href'        => UA('sale/voucher/update', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
			);
		}

		$this->data['totals'] = $this->model_sale_order->getOrderTotals($order_id);

		$this->data['downloads'] = array();

		foreach ($products as $product) {
			$results = $this->model_sale_order->getOrderDownloads($order_id, $product['order_product_id']);

			foreach ($results as $result) {
				$this->data['downloads'][] = array(
					'name'      => $result['name'],
					'filename'  => $result['mask'],
					'remaining' => $result['remaining']
				);
			}
		}

		$this->data['order_status_id'] = $order_info['order_status_id'];

		// Fraud
		M('sale/fraud');

		$fraud_info = $this->model_sale_fraud->getFraud($order_id);

		if ($fraud_info) {
			$this->data['country_match'] = $fraud_info['country_match'];
			$this->data['country_code'] = $fraud_info['country_code'];
			$this->data['high_risk_country'] = $fraud_info['high_risk_country'];
			$this->data['distance'] = $fraud_info['distance'];
			$this->data['ip_region'] = $fraud_info['ip_region'];
			$this->data['ip_city'] = $fraud_info['ip_city'];
			$this->data['ip_latitude'] = $fraud_info['ip_latitude'];
			$this->data['ip_longitude'] = $fraud_info['ip_longitude'];
			$this->data['ip_isp'] = $fraud_info['ip_isp'];
			$this->data['ip_org'] = $fraud_info['ip_org'];
			$this->data['ip_asnum'] = $fraud_info['ip_asnum'];
			$this->data['ip_user_type'] = $fraud_info['ip_user_type'];
			$this->data['ip_country_confidence'] = $fraud_info['ip_country_confidence'];
			$this->data['ip_region_confidence'] = $fraud_info['ip_region_confidence'];
			$this->data['ip_city_confidence'] = $fraud_info['ip_city_confidence'];
			$this->data['ip_postal_confidence'] = $fraud_info['ip_postal_confidence'];
			$this->data['ip_postal_code'] = $fraud_info['ip_postal_code'];
			$this->data['ip_accuracy_radius'] = $fraud_info['ip_accuracy_radius'];
			$this->data['ip_net_speed_cell'] = $fraud_info['ip_net_speed_cell'];
			$this->data['ip_metro_code'] = $fraud_info['ip_metro_code'];
			$this->data['ip_area_code'] = $fraud_info['ip_area_code'];
			$this->data['ip_time_zone'] = $fraud_info['ip_time_zone'];
			$this->data['ip_region_name'] = $fraud_info['ip_region_name'];
			$this->data['ip_domain'] = $fraud_info['ip_domain'];
			$this->data['ip_country_name'] = $fraud_info['ip_country_name'];
			$this->data['ip_continent_code'] = $fraud_info['ip_continent_code'];
			$this->data['ip_corporate_proxy'] = $fraud_info['ip_corporate_proxy'];
			$this->data['anonymous_proxy'] = $fraud_info['anonymous_proxy'];
			$this->data['proxy_score'] = $fraud_info['proxy_score'];
			$this->data['is_trans_proxy'] = $fraud_info['is_trans_proxy'];
			$this->data['free_mail'] = $fraud_info['free_mail'];
			$this->data['carder_email'] = $fraud_info['carder_email'];

			if ($fraud_info['high_risk_username']) {
				$this->data['high_risk_username'] = $fraud_info['high_risk_username'];
			} else {
				$this->data['high_risk_username'] = '';
			}

			if ($fraud_info['high_risk_password']) {
				$this->data['high_risk_password'] = $fraud_info['high_risk_password'];
			} else {
				$this->data['high_risk_password'] = '';
			}

			$this->data['bin_match'] = $fraud_info['bin_match'];

			if ($fraud_info['bin_country']) {
				$this->data['bin_country'] = $fraud_info['bin_country'];
			} else {
				$this->data['bin_country'] = '';
			}

			$this->data['bin_name_match'] = $fraud_info['bin_name_match'];

			if ($fraud_info['bin_name']) {
				$this->data['bin_name'] = $fraud_info['bin_name'];
			} else {
				$this->data['bin_name'] = '';
			}

			$this->data['bin_phone_match'] = $fraud_info['bin_phone_match'];

			if ($fraud_info['bin_phone']) {
				$this->data['bin_phone'] = $fraud_info['bin_phone'];
			} else {
				$this->data['bin_phone'] = '';
			}

			if ($fraud_info['customer_phone_in_billing_location']) {
				$this->data['customer_phone_in_billing_location'] = $fraud_info['customer_phone_in_billing_location'];
			} else {
				$this->data['customer_phone_in_billing_location'] = '';
			}

			$this->data['ship_forward'] = $fraud_info['ship_forward'];

			if ($fraud_info['city_postal_match']) {
				$this->data['city_postal_match'] = $fraud_info['city_postal_match'];
			} else {
				$this->data['city_postal_match'] = '';
			}

			if ($fraud_info['ship_city_postal_match']) {
				$this->data['ship_city_postal_match'] = $fraud_info['ship_city_postal_match'];
			} else {
				$this->data['ship_city_postal_match'] = '';
			}

			$this->data['score'] = $fraud_info['score'];
			$this->data['explanation'] = $fraud_info['explanation'];
			$this->data['risk_score'] = $fraud_info['risk_score'];
			$this->data['queries_remaining'] = $fraud_info['queries_remaining'];
			$this->data['maxmind_id'] = $fraud_info['maxmind_id'];
			$this->data['error'] = $fraud_info['error'];
		} else {
			$this->data['maxmind_id'] = '';
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/order_info.tpl');
	}

	public function product_info() {
		$this->language->load('sale/order');
		M('sale/order');
		M('tool/image');
		
		$order_id = G('order_id', 0);
		
		$this->data['products'] = array();
		
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		$products = $this->model_sale_order->getOrderProducts($order_id);

		foreach ($products as $product) {
			
			if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
				$image = $this->model_tool_image->resize($product['image'], 100, 100);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}

			$option_data = array();

			$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value'],
						'type'  => $option['type']
					);
				} else {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
						'type'  => $option['type'],
						'href'  => UA('sale/order/download', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . '&order_option_id=' . $option['order_option_id'], 'SSL')
					);
				}
			}

			$this->data['products'][$product['product_id']] = array(
				'order_product_id' => $product['order_product_id'],
				'product_id'       => $product['product_id'],
				'name'    	 	   => $product['name'],
				'model'    		   => $product['model'],
				'image'            => $image,
				'option'   		   => $option_data,
				'quantity'		   => $product['quantity'],
				'shipped_qty'	   => $product['shipped_qty'],
				'price'    		   => $this->currency->format($product['price'] + (C('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'    		   => $this->currency->format($product['total'] + (C('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'href'     		   => U('product/product', 'product_id=' . $product['product_id'])
			);
		}
		
		$this->data['vouchers'] = array();

		$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

		foreach ($vouchers as $voucher) {
			$this->data['vouchers'][] = array(
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'href'        => UA('sale/voucher/update', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
			);
		}

		$this->data['totals'] = $this->model_sale_order->getOrderTotals($order_id);
		
		$this->display('sale/order_product_info.tpl');
	}
	
	public function createInvoiceNo() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
		} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($this->request->get['order_id']);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = L('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}

	public function addCredit() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if ($order_info && $order_info['customer_id']) {
				M('sale/customer');

				$credit_total = $this->model_sale_customer->getTotalTransactionsByOrderId($this->request->get['order_id']);

				if (!$credit_total) {
					$this->model_sale_customer->addTransaction($order_info['customer_id'], L('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['total'], $this->request->get['order_id']);

					$json['success'] = L('text_credit_added');
				} else {
					$json['error'] = L('error_action');
				}
			}
		}

		$this->response->setOutput(json_encode($json));
  	}

	public function removeCredit() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if ($order_info && $order_info['customer_id']) {
				M('sale/customer');

				$this->model_sale_customer->deleteTransaction($this->request->get['order_id']);

				$json['success'] = L('text_credit_removed');
			} else {
				$json['error'] = L('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}

	public function addReward() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if ($order_info && $order_info['customer_id']) {
				M('sale/customer');

				$reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['order_id']);

				if (!$reward_total) {
					$this->model_sale_customer->addReward($order_info['customer_id'], L('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['reward'], $this->request->get['order_id']);

					$json['success'] = L('text_reward_added');
				} else {
					$json['error'] = L('error_action');
				}
			} else {
				$json['error'] = L('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}

	public function removeReward() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if ($order_info && $order_info['customer_id']) {
				M('sale/customer');

				$this->model_sale_customer->deleteReward($this->request->get['order_id']);

				$json['success'] = L('text_reward_removed');
			} else {
				$json['error'] = L('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}

	public function addCommission() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if ($order_info && $order_info['affiliate_id']) {
				M('sale/affiliate');

				$affiliate_total = $this->model_sale_affiliate->getTotalTransactionsByOrderId($this->request->get['order_id']);

				if (!$affiliate_total) {
					$this->model_sale_affiliate->addTransaction($order_info['affiliate_id'], L('text_order_id') . ' #' . $this->request->get['order_id'], $order_info['commission'], $this->request->get['order_id']);

					$json['success'] = L('text_commission_added');
				} else {
					$json['error'] = L('error_action');
				}
			} else {
				$json['error'] = L('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}

	public function removeCommission() {
		$this->language->load('sale/order');

		$json = array();

     	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = L('error_permission');
    	} elseif (isset($this->request->get['order_id'])) {
			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if ($order_info && $order_info['affiliate_id']) {
				M('sale/affiliate');

				$this->model_sale_affiliate->deleteTransaction($this->request->get['order_id']);

				$json['success'] = L('text_commission_removed');
			} else {
				$json['error'] = L('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}



	public function shipping_history() {
    	$this->language->load('sale/order');
		M('sale/order');
		$order_id = $this->request->get('order_id');

		if ($this->request->isPost()) {
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$this->setMessage('error', L('error_permission'));
			}
			else {
				$products = P('products');
				$temp = array();
				if (is_array($products)) {
					foreach ($products as $product_id => $qty) {
						if ($qty > 0) {
							$temp[$product_id] = $qty;
						}
					}
				}
				$this->request->post['products'] = $temp;
				$this->request->post['ship_date'] = strtotime($this->request->post['ship_date']);
				$this->model_sale_order->addShippingHistory($order_id, $this->request->post);
				$this->setMessage('success', L('text_success'));
			}
		}

		M('localisation/carrier');
		$carriers = $this->model_localisation_carrier->getCarriers();

		$products = array();
		$results = $this->model_sale_order->getOrderProducts($order_id);
		foreach ($results as $product) {
			$products[$product['product_id']] = $product;
		}

		$this->data['histories'] = array();
		$results = $this->model_sale_order->getOrderShippingHistory($order_id);

		foreach ($results as $his) {
			$temp = array();
			foreach($his['products'] as $product_id => $qty) {
				$temp[] = array(
				'name' => $products[$product_id]['name'],
				'model' => $products[$product_id]['model'],
				'quantity' =>  $products[$product_id]['quantity'],
				'shipped_qty' => $qty,
				);
			}
			$his['products'] = $temp;
			if ($his['ship_carrier'] && isset($carriers[$his['ship_carrier']])) {
				if ($his['track_number'] && $carriers[$his['ship_carrier']]['tracking_link']) {
					$his['tracking_link'] = $carriers[$his['ship_carrier']]['tracking_link'] . $his['track_number'];
				}
				else $his['tracking_link'] = '';

				$his['ship_carrier'] = $carriers[$his['ship_carrier']]['name'];
			}
			else $his['tracking_link'] = '';

			$this->data['histories'][] = $his;
		}

		$this->display('sale/order_shipping_history.tpl');
  	}

	public function history() {
    	$this->language->load('sale/order');

		M('sale/order');

		if ($this->request->isPost()) {
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$this->setMessage('error', L('error_permission'));
			}
			else {
				$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);

				$this->setMessage('success', L('text_success'));
			}
		}

		$page = $this->request->get('page', 1);

		$this->data['histories'] = array();

		$results = $this->model_sale_order->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? L('text_yes') : L('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
        		'date_added' => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/order/history', 'order_id=' . $this->request->get['order_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->display('sale/order_history.tpl');
  	}

	public function download() {
		M('sale/order');

		$order_option_id = $this->request->get('order_option_id', 0);
		$option_info = $this->model_sale_order->getOrderOption($this->request->get['order_id'], $order_option_id);

		if ($option_info && $option_info['type'] == 'file') {
			$file = DIR_DOWNLOAD . $option_info['value'];
			$mask = basename(utf8_substr($option_info['value'], 0, utf8_strrpos($option_info['value'], '.')));

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');
					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->redirect(UA('error/not_found'));
		}
	}

	public function upload() {
		$this->language->load('sale/order');

		$json = array();

		if ($this->request->isPost()) {
			if (!empty($this->request->files['file']['name'])) {
				$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = L('error_filename');
				}

				$allowed = array();

				$filetypes = explode(',', C('config_file_extension_allowed'));

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(utf8_substr(strrchr($filename, '.'), 1), $allowed)) {
					$json['error'] = L('error_filetype');
				}

				// Allowed file mime types
				$allowed = array();

				$filetypes = explode("\n", C('config_file_mime_allowed'));

				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = L('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = L('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = L('error_upload');
			}

			if (!isset($json['error'])) {
				if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
					$file = basename($filename) . '.' . md5(mt_rand());

					$json['file'] = $file;

					move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
				}

				$json['success'] = L('text_upload');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

  	public function invoice() {
		$this->language->load('sale/order');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		M('sale/order');
		M('setting/setting');

		$this->data['orders'] = array();
		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}
		elseif (isset($this->request->get['basket_id'])) {
			$orders = $this->model_sale_order->getOrdersByBasketId($this->request->get['basket_id']);
		}
		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = C('config_address');
					$store_email = C('config_email');
					$store_telephone = C('config_telephone');
					$store_fax = C('config_fax');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + (C('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + (C('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data = $this->model_sale_order->getOrderTotals($order_id);

				$this->data['orders'][] = array(
					'order_id'	         => $order_id,
					'invoice_no'         => $invoice_no,
					'date_added'         => date(L('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $order_info['email'],
					'telephone'          => $order_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $order_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_company_id' => $order_info['payment_company_id'],
					'payment_tax_id'     => $order_info['payment_tax_id'],
					'payment_method'     => $order_info['payment_method'],
					'product'            => $product_data,
					'voucher'            => $voucher_data,
					'total'              => $total_data,
					'comment'            => nl2br($order_info['comment'])
				);
			}
		}

		$this->display('sale/order_invoice.tpl');
	}
}
?>