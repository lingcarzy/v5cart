<?php
class ControllerAccountOrder extends Controller {

	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = U('account/order', '', 'SSL');

	  		$this->redirect(U('account/login', '', 'SSL'));
    	}

		$this->language->load('account/order');

		M('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);

			if ($order_info) {
				$order_products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

				foreach ($order_products as $order_product) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}

					$this->session->data['success'] = sprintf(L('text_success'), $this->request->get['order_id']);

					$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
				}

				$this->redirect(U('checkout/cart'));
			}
		}

    	$this->document->setTitle(L('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_account'),
			'href'      => U('account/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('account/order', $url, 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_order_id'] = L('text_order_id');
		$this->data['text_status'] = L('text_status');
		$this->data['text_date_added'] = L('text_date_added');
		$this->data['text_customer'] = L('text_customer');
		$this->data['text_products'] = L('text_products');
		$this->data['text_total'] = L('text_total');
		$this->data['text_empty'] = L('text_empty');

		$this->data['button_view'] = L('button_view');
		$this->data['button_reorder'] = L('button_reorder');
		$this->data['button_continue'] = L('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['orders'] = array();

		$order_total = $this->model_account_order->getTotalOrders();

		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'href'       => U('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
				'reorder'    => U('account/order', 'order_id=' . $result['order_id'], 'SSL')
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = U('account/order', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['continue'] = U('account/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/order_list.tpl');
	}

	public function info() {
		$this->language->load('account/order');

		$order_id = (int) G('order_id', 0);

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/order/info', 'order_id=' . $order_id, 'SSL');

			$this->redirect(U('account/login', '', 'SSL'));
    	}

		M('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			$this->document->setTitle(L('text_order'));

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => HTTP_SERVER,
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_account'),
				'href'      => U('account/account', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => L('heading_title'),
				'href'      => U('account/order', $url, 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_order'),
				'href'      => U('account/order/info', 'order_id=' . $order_id . $url, 'SSL'),
				'separator' => L('text_separator')
			);

      		$this->data['heading_title'] = L('text_order');

			$this->data['text_order_detail'] = L('text_order_detail');
			$this->data['text_invoice_no'] = L('text_invoice_no');
    		$this->data['text_order_id'] = L('text_order_id');
			$this->data['text_date_added'] = L('text_date_added');
      		$this->data['text_shipping_method'] = L('text_shipping_method');
			$this->data['text_shipping_address'] = L('text_shipping_address');
      		$this->data['text_payment_method'] = L('text_payment_method');
      		$this->data['text_payment_address'] = L('text_payment_address');
      		$this->data['text_history'] = L('text_history');
			$this->data['text_comment'] = L('text_comment');
			$this->data['text_shipping_info'] = L('text_shipping_info');

      		$this->data['column_name'] = L('column_name');
      		$this->data['column_model'] = L('column_model');
      		$this->data['column_quantity'] = L('column_quantity');
      		$this->data['column_price'] = L('column_price');
      		$this->data['column_total'] = L('column_total');
			$this->data['column_action'] = L('column_action');
			$this->data['column_date_added'] = L('column_date_added');
      		$this->data['column_status'] = L('column_status');
      		$this->data['column_comment'] = L('column_comment');
			$this->data['column_shipped'] = L('column_shipped');

			$this->data['button_return'] = L('button_return');
      		$this->data['button_continue'] = L('button_continue');

			if ($order_info['invoice_no']) {
				$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$this->data['invoice_no'] = '';
			}

			$this->data['order_id'] = $order_id;
			$this->data['date_added'] = date(L('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
      			$format = $order_info['payment_address_format'];
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

			$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

      		$this->data['payment_method'] = $order_info['payment_method'];

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

			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$this->data['shipping_method'] = $order_info['shipping_method'];

			$this->data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($order_id);

      		foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

         		foreach ($options as $option) {
          			if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
        		}

        		$this->data['products'][$product['product_id']] = array(
          			'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
					'shipped_qty' => $product['shipped_qty'],
          			'price'    => $this->currency->format($product['price'] + (C('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + (C('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href' => U('product/product', 'product_id=' . $product['product_id']),
					'return'   => U('account/return/insert', 'order_id=' . $order_id . '&product_id=' . $product['product_id'], 'SSL')
        		);
      		}

			// Voucher
			$this->data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$this->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

      		$this->data['totals'] = $this->model_account_order->getOrderTotals($order_id);

			$this->data['comment'] = nl2br($order_info['comment']);

			$this->data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($order_id);

      		foreach ($results as $result) {
        		$this->data['histories'][] = array(
          			'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => nl2br($result['comment'])
        		);
      		}

			//shipping history
			$this->data['shipping_histories'] = array();
			$results = $this->model_account_order->getOrderShippingHistory($order_id);
			if ($results) {
				M('localisation/carrier');
				$carriers = $this->model_localisation_carrier->getCarriers();

				foreach ($results as $his) {
					$temp = array();
					foreach($his['products'] as $product_id => $qty) {
						$temp[] = array(
						'name' => $this->data['products'][$product_id]['name'],
						'model' => $this->data['products'][$product_id]['model'],
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

					$this->data['shipping_histories'][] = $his;
				}
			}

      		$this->data['continue'] = U('account/order', '', 'SSL');

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->display('account/order_info.tpl');
    	} else {
			$this->document->setTitle(L('text_order'));

      		$this->data['heading_title'] = L('text_order');

      		$this->data['text_error'] = L('text_error');

      		$this->data['button_continue'] = L('button_continue');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => HTTP_SERVER,
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_account'),
				'href'      => U('account/account', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('heading_title'),
				'href'      => U('account/order', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_order'),
				'href'      => U('account/order/info', 'order_id=' . $order_id, 'SSL'),
				'separator' => L('text_separator')
			);

      		$this->data['continue'] = U('account/order', '', 'SSL');

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->display('error/not_found.tpl');
    	}
  	}
}
?>