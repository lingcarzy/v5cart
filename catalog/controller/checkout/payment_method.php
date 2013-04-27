<?php
class ControllerCheckoutPaymentMethod extends Controller {
  	public function index() {
		$this->language->load('checkout/checkout');

		M('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		if (!empty($payment_address)) {
			// Totals
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

			// Payment Methods
			$method_data = array();

			$extensions = C('cache_extension_payment');
			foreach ($extensions as $extension) {
				if (C($extension . '_status')) {
					M('payment/' . $extension);

					$method = $this->{'model_payment_' . $extension}->getMethod($payment_address, $total);

					if ($method) {
						$method_data[$extension] = $method;
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['payment_methods'] = $method_data;
		}

		$this->data['text_payment_method'] = L('text_payment_method');
		$this->data['text_comments'] = L('text_comments');

		$this->data['button_continue'] = L('button_continue');

		if (empty($this->session->data['payment_methods'])) {
			$this->data['error_warning'] = sprintf(L('error_no_payment'), U('page/contact'));
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['payment_methods'])) {
			$this->data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$this->data['payment_methods'] = array();
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$this->data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (C('config_checkout_id')) {
			M('catalog/page');

			$page_info = $this->model_catalog_page->getPage(C('config_checkout_id'));

			if ($page_info) {
				$this->data['text_agree'] = sprintf(L('text_agree'), U('page/index/info', 'page_id=' . C('config_checkout_id'), 'SSL'), $page_info['title'], $page_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}

		if (isset($this->session->data['agree'])) {
			$this->data['agree'] = $this->session->data['agree'];
		} else {
			$this->data['agree'] = '';
		}

		$this->display('checkout/payment_method.tpl');
  	}

	public function validate() {
		$this->language->load('checkout/checkout');

		$json = array();

		// Validate if payment address has been set.
		M('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		if (empty($payment_address)) {
			$json['redirect'] = U('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !C('config_stock_checkout'))) {
			$json['redirect'] = U('checkout/cart');
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
				$json['redirect'] = U('checkout/cart');

				break;
			}
		}

		if (!$json) {
			if (!isset($this->request->post['payment_method'])) {
				$json['error']['warning'] = L('error_payment');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error']['warning'] = L('error_payment');
			}

			if (C('config_checkout_id')) {
				M('catalog/page');

				$page_info = $this->model_catalog_page->getPage(C('config_checkout_id'));

				if ($page_info && !isset($this->request->post['agree'])) {
					$json['error']['warning'] = sprintf(L('error_agree'), $page_info['title']);
				}
			}

			if (!$json) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>