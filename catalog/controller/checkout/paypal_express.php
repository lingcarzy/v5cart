<?php
class ControllerCheckoutPaypalExpress extends Controller {
	public function index() {
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !C('config_stock_checkout'))) {
	  		$this->redirect(U('checkout/cart'));
    	}
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$this->redirect(U('checkout/cart'));
			}				
		}
		
		$guest_checkout = C('config_guest_checkout') && !C('config_customer_price') && !$this->cart->hasDownload();
		
		if (!$this->customer->isLogged() && !$guest_checkout) {
			$this->session->data['redirect'] = U('checkout/cart', '', 'SSL');
			$this->redirect(U('account/login', '', 'SSL'));
		}
		
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$sort_order = array();
		$totals = C("cache_extension_total");

		foreach ($totals as $key => $code) {
			$sort_order[$key] = C($code . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $totals);

		foreach ($totals as $code) {
			if (C($code . '_status')) {
				M('total/' . $code);
				$this->{'model_total_' . $code}->getTotal($total_data, $total, $taxes);
			}
		}

		$sort_order = array();

		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $total_data);

		$data = array();

		$data['invoice_prefix'] = C('config_invoice_prefix');
		$data['store_id'] = C('config_store_id');
		$data['store_name'] = C('config_name');

		if ($data['store_id']) {
			$data['store_url'] = C('config_url');
		} else {
			$data['store_url'] = HTTP_SERVER;
		}

		if ($this->customer->isLogged()) {
			$data['customer_id'] = $this->customer->getId();
			$data['customer_group_id'] = $this->customer->getCustomerGroupId();
			$data['firstname'] = $this->customer->getFirstName();
			$data['lastname'] = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['telephone'] = $this->customer->getTelephone();
			$data['fax'] = $this->customer->getFax();
		} elseif (isset($this->session->data['guest'])) {
			$data['customer_id'] = 0;
			$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
			$data['firstname'] = $this->session->data['guest']['firstname'];
			$data['lastname'] = $this->session->data['guest']['lastname'];
			$data['email'] = $this->session->data['guest']['email'];
			$data['telephone'] = $this->session->data['guest']['telephone'];
			$data['fax'] = $this->session->data['guest']['fax'];
		} else {
			$data['customer_id'] = 0;
			$data['customer_group_id'] = C('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
		}

		$data['payment_firstname'] = '';
		$data['payment_lastname'] = '';
		$data['payment_company'] = '';
		$data['payment_company_id'] = '';
		$data['payment_tax_id'] = '';
		$data['payment_address_1'] = '';
		$data['payment_address_2'] = '';
		$data['payment_city'] = '';
		$data['payment_postcode'] = '';
		$data['payment_zone'] = '';
		$data['payment_zone_id'] = 0;
		$data['payment_country'] = '';
		$data['payment_country_id'] = 0;
		$data['payment_address_format'] = '';
		$data['payment_method'] = 'Paypal Express';
		$data['payment_code'] = 'paypal_express'; 
		
		$data['shipping_firstname'] = '';
		$data['shipping_lastname'] = '';
		$data['shipping_company'] = '';
		$data['shipping_address_1'] = '';
		$data['shipping_address_2'] = '';
		$data['shipping_city'] = '';
		$data['shipping_postcode'] = '';
		$data['shipping_zone'] = '';
		$data['shipping_zone_id'] = 0;
		$data['shipping_country'] = '';
		$data['shipping_country_id'] = 0;
		$data['shipping_address_format'] = '';
		$data['shipping_method'] = '';
		$data['shipping_code'] = '';

		//$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);


		$product_data = array();

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['option_value'];	
				} else {
					$value = $this->encryption->decrypt($option['option_value']);
				}	
				
				$option_data[] = array(
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],								   
					'name'                    => $option['name'],
					'value'                   => $value,
					'type'                    => $option['type']
				);					
			}

			$product_data[] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
				'total'      => $product['total'],
				'tax'        => 0, //$this->tax->getRate($product['tax_class_id'])
				'reward'     => $product['reward']
			);
		}

		// Gift Voucher
		$voucher_data = array();
			
		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$voucher_data[] = array(
					'description'      => $voucher['description'],
					'code'             => substr(md5(mt_rand()), 0, 10),
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],						
					'amount'           => $voucher['amount']
				);
			}
		}

		$data['products'] = $product_data;
		$data['vouchers'] = $voucher_data;
		$data['totals'] = $total_data;
		$data['comment'] = '';
		$data['total'] = $total;
		
		if (isset($this->request->cookie['tracking'])) {
			M('affiliate/affiliate');
			
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);
			$subtotal = $this->cart->getSubTotal();
			
			if ($affiliate_info) {
				$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
				$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
		} else {
			$data['affiliate_id'] = 0;
			$data['commission'] = 0;
		}

		$data['language_id'] = C('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency_code'] = $this->currency->getCode();
		$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];	
		} elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];	
		} else {
			$data['forwarded_ip'] = '';
		}
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];	
		} else {
			$data['user_agent'] = '';
		}
		
		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];	
		} else {
			$data['accept_language'] = '';
		}
		M('checkout/order');
		$this->session->data['total'] = $total;
		$this->session->data['order_id'] = $this->model_checkout_order->addOrder($data);

		//Paypal
		M("payment/paypalexpresscurl");
		$invNum = $this->session->data['order_id'];
		
		M('checkout/order');		
		$order_info = $this->model_checkout_order->getOrder($invNum);
		
		$paymentAmount = $this->currency->convert($total, 'USD' , $order_info['currency_code']);
		
		$paymentAmount = round($paymentAmount, 2);
		$returnURL = U('checkout/paypal_express/confirm', '', 'SSL');
		$cancelURL = U('checkout/cart', '', 'SSL');

		$resArray = $this->model_payment_paypalexpresscurl->SetExpressCheckout($invNum, $paymentAmount, $returnURL, $cancelURL);
		$json = array();
		if($this->model_payment_paypalexpresscurl->error()) {
			echo $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
			exit(0);
		}
		else {
			$this->model_payment_paypalexpresscurl->redirectToPayPal();
		}
	}
	
	//TODO: tax calculate
	public function confirm() {
		M("payment/paypalexpresscurl");
		$resArray = $this->model_payment_paypalexpresscurl->getCheckoutDetails();
		if ($this->model_payment_paypalexpresscurl->error()) {
			echo $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
			exit(0);
		}
		$order_id = $this->session->data['order_id'];
		$order_datas =  $this->model_payment_paypalexpresscurl->getShippingAddress($resArray);
		$payerInfo = $this->model_payment_paypalexpresscurl->getPayerInfo($resArray);
		$order_datas['firstname'] = $payerInfo['firstname'];
		$order_datas['lastname'] = $payerInfo['lastname'];
		$this->db->update("order", $order_datas, "order_id = $order_id");

		///////////////////////////
		$this->data['order'] = $order_datas;
		$this->language->load('checkout/checkout');
		$this->data['breadcrumbs'] = array();
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => U('common/home'),
        	'separator' => false
      	);
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_cart'),
			'href'      => U('checkout/cart'),
        	'separator' => L('text_separator')
      	);
      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('checkout/checkout', '', 'SSL'),
        	'separator' => L('text_separator')
      	);
		$this->document->setTitle(L('heading_title'));
		$this->data['heading_title'] = L('heading_title');
		$this->data['column_image'] = L('column_image');
		$this->data['column_name'] = L('column_name');
		$this->data['column_model'] = L('column_model');
		$this->data['column_quantity'] = L('column_quantity');
		$this->data['column_price'] = L('column_price');
		$this->data['column_total'] = L('column_total');
		$this->data['text_shipping_method'] = L('text_shipping_method');
		$this->data['text_comments'] = L('text_comments');
		$this->data['button_confirm'] = L('button_confirm');

		$this->data['products'] = array();
		M('tool/image');
		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();
	
			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['option_value'];	
				} else {
					$filename = $this->encryption->decrypt($option['option_value']);
					
					$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
				}
									
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}  

			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], C('config_image_cart_width'), C('config_image_cart_height'));
			} else {
				$image = '';
			}

			$this->data['products'][] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], C('config_tax'))),
				'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], C('config_tax')) * $product['quantity']),
				'href'       =>$product['link'],
				'image'      => $image
			);
		}

		// Gift Voucher
		$this->data['vouchers'] = array();
			
		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$this->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'])
				);
			}
		}
		M("account/order");
		$this->data["totals"] = $this->model_account_order->getOrderTotals($order_id);

		$quote_data = array();
		$zoneInfo = array(
			'country_id' => $order_datas['shipping_country_id'],
			'zone_id' => $order_datas['shipping_zone_id'],
		);
		
		$shippings = C('cache_extension_shipping');
		foreach ($shippings as $code) {
			if (C($code . '_status')) {
				M('shipping/' . $code);
				$quote = $this->{'model_shipping_' . $code}->getQuote($zoneInfo);
				if ($quote) {
					$quote_data[$code] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}
		$sort_order = array();
		foreach ($quote_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
		array_multisort($sort_order, SORT_ASC, $quote_data);
		$this->session->data['shipping_methods'] = $quote_data;
		$this->data['shipping_methods'] = $quote_data;
		$this->data['code'] = '';
		$this->data['continue'] = U("checkout/paypal_express/pay", '', 'SSL');
		$this->children = array(
			'common/footer',
			'common/header'
		);
		$this->display('checkout/paypal_express_confirm.tpl');
	}

	public function pay() {
		if (!$this->request->isPost()) {
			$this->redirect(U('checkout/cart'));
		}
		$order_id = $this->session->data["order_id"];
		
		M('checkout/order');		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$total = $this->session->data['total'];
		
		$comment = ES(strip_tags(P('comment')));
		
		//shipping method
		$shipping = explode('.', $this->request->post['shipping_method']);
		M("total/shipping");		
		$order_total_shipping = array();
		$shipping_fee = 0;
		$tax = 0;
		$shipping_exsits = isset($this->session->data['shipping_method']);
		$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
		$this->model_total_shipping->getTotal($order_total_shipping, $shipping_fee, $tax);
		$order_total_shipping = $order_total_shipping[0];
		$order_total_shipping['order_id'] = $order_id;
		
		if ($shipping_exsits) {						
			$this->db->update("order_total", $order_total_shipping, "order_id = $order_id AND code = 'shipping'");
		}
		else {	
			$this->db->insert("order_total", $order_total_shipping);
		}
		
		if ($shipping_fee > 0) {
			$total += $shipping_fee;
			$text = $this->currency->format($total);
			$this->db->runSql("UPDATE @@order_total SET text = '$text', value = '$total' WHERE order_id = '$order_id' AND code = 'total'");
		}
		$shipping_method = $this->session->data['shipping_method']['title'];
		$shipping_code = $this->session->data['shipping_method']['code'];
		$this->db->runSql("UPDATE `@@order` SET comment = '$comment', total = '$total', shipping_method='$shipping_method', shipping_code='$shipping_code' WHERE order_id = '$order_id'");
		
		M("payment/paypalexpresscurl");
		$paymentAmount = $this->currency->convert($total, 'USD' , $order_info['currency_code']);
		$paymentAmount = round($paymentAmount, 2);
		$resArray = $this->model_payment_paypalexpresscurl->ConfirmPayment($order_id, $paymentAmount);
		$json = array();
		if ($this->model_payment_paypalexpresscurl->error()) {
			$json['error'] = $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
		}
		else {
			if($resArray["PAYMENTINFO_0_AMT"] == $paymentAmount) {
				$order_status_id = C("paypal_express_order_status_id");
				M('checkout/order');
				$this->model_checkout_order->confirm($order_id, $order_status_id);
				$json['redirect'] = U('checkout/success');
			}
			else {
				$json['error'] = "Unknow Error!";
			}
		}
		$this->response->setOutput(json_encode($json));
	}
}
?>