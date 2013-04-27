<?php
class ControllerCheckoutCart extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('checkout/cart');

		if (!isset($this->session->data['vouchers'])) {
			$this->session->data['vouchers'] = array();
		}

		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			$this->redirect(U('checkout/cart'));
		}

		// Remove
		if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);

			unset($this->session->data['vouchers'][$this->request->get['remove']]);

			$this->session->data['success'] = L('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			$this->redirect(U('checkout/cart'));
		}

		// Coupon
		if (isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = L('text_coupon');

			$this->redirect(U('checkout/cart'));
		}

		// Voucher
		if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = L('text_voucher');

			$this->redirect(U('checkout/cart'));
		}

		// Reward
		if (isset($this->request->post['reward']) && $this->validateReward()) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$this->session->data['success'] = L('text_reward');

			$this->redirect(U('checkout/cart'));
		}

		// Shipping
		if (isset($this->request->post['shipping_method']) && $this->validateShipping()) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$this->session->data['success'] = L('text_shipping');

			$this->redirect(U('checkout/cart'));
		}

		$this->document->setTitle(L('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => HTTP_SERVER,
        	'text'      => L('text_home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'href'      => U('checkout/cart'),
        	'text'      => L('heading_title'),
        	'separator' => L('text_separator')
      	);

    	if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
			$points = $this->customer->getRewardPoints();

			$points_total = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}

      		$this->data['heading_title'] = L('heading_title');

			$this->data['text_next'] = L('text_next');
			$this->data['text_next_choice'] = L('text_next_choice');
     		$this->data['text_use_coupon'] = L('text_use_coupon');
			$this->data['text_use_voucher'] = L('text_use_voucher');
			$this->data['text_use_reward'] = sprintf(L('text_use_reward'), $points);
			$this->data['text_shipping_estimate'] = L('text_shipping_estimate');
			$this->data['text_shipping_detail'] = L('text_shipping_detail');
			$this->data['text_shipping_method'] = L('text_shipping_method');
			$this->data['text_select'] = L('text_select');
			$this->data['text_none'] = L('text_none');

			$this->data['column_image'] = L('column_image');
      		$this->data['column_name'] = L('column_name');
      		$this->data['column_model'] = L('column_model');
      		$this->data['column_quantity'] = L('column_quantity');
			$this->data['column_price'] = L('column_price');
      		$this->data['column_total'] = L('column_total');

			$this->data['entry_coupon'] = L('entry_coupon');
			$this->data['entry_voucher'] = L('entry_voucher');
			$this->data['entry_reward'] = sprintf(L('entry_reward'), $points_total);
			$this->data['entry_country'] = L('entry_country');
			$this->data['entry_zone'] = L('entry_zone');
			$this->data['entry_postcode'] = L('entry_postcode');

			$this->data['button_update'] = L('button_update');
			$this->data['button_remove'] = L('button_remove');
			$this->data['button_coupon'] = L('button_coupon');
			$this->data['button_voucher'] = L('button_voucher');
			$this->data['button_reward'] = L('button_reward');
			$this->data['button_quote'] = L('button_quote');
			$this->data['button_shipping'] = L('button_shipping');
      		$this->data['button_shopping'] = L('button_shopping');
      		$this->data['button_checkout'] = L('button_checkout');

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} elseif (!$this->cart->hasStock() && (!C('config_stock_checkout') || C('config_stock_warning'))) {
      			$this->data['error_warning'] = L('error_stock');
			} else {
				$this->data['error_warning'] = '';
			}

			if (C('config_customer_price') && !$this->customer->isLogged()) {
				$this->data['attention'] = sprintf(L('text_login'), U('account/login'), U('account/register'));
			} else {
				$this->data['attention'] = '';
			}

			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';
			}

			$this->data['action'] = U('checkout/cart');

			if (C('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), C('config_weight_class_id'), L('decimal_point'), L('thousand_point'));
			} else {
				$this->data['weight'] = '';
			}

			M('tool/image');

      		$this->data['products'] = array();

			$products = $this->cart->getProducts();

      		foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$this->data['error_warning'] = sprintf(L('error_minimum'), $product['name'], $product['minimum']);
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], C('config_image_cart_width'), C('config_image_cart_height'));
				} else {
					$image = '';
				}

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

				// Display prices
				if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], C('config_tax')));
				} else {
					$price = false;
				}

				// Display prices
				if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], C('config_tax')) * $product['quantity']);
				} else {
					$total = false;
				}

        		$this->data['products'][] = array(
          			'key'      => $product['key'],
          			'thumb'    => $image,
					'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
          			'stock'    => $product['stock'] ? true : !(!C('config_stock_checkout') || C('config_stock_warning')),
					'reward'   => ($product['reward'] ? sprintf(L('text_points'), $product['reward']) : ''),
					'price'    => $price,
					'total'    => $total,
					'href'     => U('product/product', 'product_id=' . $product['product_id']),
					'remove'   => U('checkout/cart', 'remove=' . $product['key'])
				);
      		}

			// Gift Voucher
			$this->data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$this->data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount']),
						'remove'      => U('checkout/cart', 'remove=' . $key)
					);
				}
			}

			if (isset($this->request->post['next'])) {
				$this->data['next'] = $this->request->post['next'];
			} else {
				$this->data['next'] = '';
			}

			$this->data['coupon_status'] = C('coupon_status');

			if (isset($this->request->post['coupon'])) {
				$this->data['coupon'] = $this->request->post['coupon'];
			} elseif (isset($this->session->data['coupon'])) {
				$this->data['coupon'] = $this->session->data['coupon'];
			} else {
				$this->data['coupon'] = '';
			}

			$this->data['voucher_status'] = C('voucher_status');

			if (isset($this->request->post['voucher'])) {
				$this->data['voucher'] = $this->request->post['voucher'];
			} elseif (isset($this->session->data['voucher'])) {
				$this->data['voucher'] = $this->session->data['voucher'];
			} else {
				$this->data['voucher'] = '';
			}

			$this->data['reward_status'] = ($points && $points_total && C('reward_status'));

			if (isset($this->request->post['reward'])) {
				$this->data['reward'] = $this->request->post['reward'];
			} elseif (isset($this->session->data['reward'])) {
				$this->data['reward'] = $this->session->data['reward'];
			} else {
				$this->data['reward'] = '';
			}

			$this->data['shipping_status'] = C('shipping_status') && C('shipping_estimator') && $this->cart->hasShipping();

			if (isset($this->request->post['country_id'])) {
				$this->data['country_id'] = $this->request->post['country_id'];
			} elseif (isset($this->session->data['shipping_country_id'])) {
				$this->data['country_id'] = $this->session->data['shipping_country_id'];
			} else {
				$this->data['country_id'] = C('config_country_id');
			}

			$this->data['countries'] = cache_read('country.php');

			if (isset($this->request->post['zone_id'])) {
				$this->data['zone_id'] = $this->request->post['zone_id'];
			} elseif (isset($this->session->data['shipping_zone_id'])) {
				$this->data['zone_id'] = $this->session->data['shipping_zone_id'];
			} else {
				$this->data['zone_id'] = '';
			}

			if (isset($this->request->post['postcode'])) {
				$this->data['postcode'] = $this->request->post['postcode'];
			} elseif (isset($this->session->data['shipping_postcode'])) {
				$this->data['postcode'] = $this->session->data['shipping_postcode'];
			} else {
				$this->data['postcode'] = '';
			}

			if (isset($this->request->post['shipping_method'])) {
				$this->data['shipping_method'] = $this->request->post['shipping_method'];
			} elseif (isset($this->session->data['shipping_method'])) {
				$this->data['shipping_method'] = $this->session->data['shipping_method']['code'];
			} else {
				$this->data['shipping_method'] = '';
			}

			// Totals
			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			// Display prices
			if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
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

					$sort_order = array();

					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $total_data);
				}
			}

			$this->data['totals'] = $total_data;

			$this->data['continue'] = HTTP_SERVER;

			$this->data['checkout'] = U('checkout/checkout', '', 'SSL');

			if (C('paypal_express_add_to_cart') && C('paypal_express_status')) {
				$this->data['show_pp_express_button'] = TRUE;
				$this->data['paypal_express_checkout'] = U('checkout/paypal_express', '', 'SSL');
			}
			else {
				$this->data['show_pp_express_button'] = FALSE;
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_bottom',
				'common/content_top',
				'common/footer',
				'common/header'
			);

			$this->display('checkout/cart.tpl');
    	} else {
      		$this->data['heading_title'] = L('heading_title');

      		$this->data['text_error'] = L('text_empty');

      		$this->data['button_continue'] = L('button_continue');

      		$this->data['continue'] = HTTP_SERVER;

			unset($this->session->data['success']);

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

	protected function validateCoupon() {
		M('checkout/coupon');

		$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

		if (!$coupon_info) {
			$this->error['warning'] = L('error_coupon');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateVoucher() {
		M('checkout/voucher');

		$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);

		if (!$voucher_info) {
			$this->error['warning'] = L('error_voucher');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateReward() {
		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		if (empty($this->request->post['reward'])) {
			$this->error['warning'] = L('error_reward');
		}

		if ($this->request->post['reward'] > $points) {
			$this->error['warning'] = sprintf(L('error_points'), $this->request->post['reward']);
		}

		if ($this->request->post['reward'] > $points_total) {
			$this->error['warning'] = sprintf(L('error_maximum'), $points_total);
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateShipping() {
		if (!empty($this->request->post['shipping_method'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->error['warning'] = L('error_shipping');
			}
		} else {
			$this->error['warning'] = L('error_shipping');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function add() {
		$this->language->load('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		M('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf(L('error_required'), $product_option['name']);
				}
			}

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option);

				$json['success'] = sprintf(L('text_success'), U('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], U('checkout/cart'));

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();

				// Display prices
				if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
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

						$sort_order = array();

						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}

						array_multisort($sort_order, SORT_ASC, $total_data);
					}
				}

				$json['total'] = sprintf(L('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', U('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function quote() {
		$this->language->load('checkout/cart');

		$json = array();

		if (!$this->cart->hasProducts()) {
			$json['error']['warning'] = L('error_product');
		}

		if (!$this->cart->hasShipping()) {
			$json['error']['warning'] = sprintf(L('error_no_shipping'), U('page/contact'));
		}

		if ($this->request->post['country_id'] == '') {
			$json['error']['country'] = L('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			$json['error']['zone'] = L('error_zone');
		}

		M('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
			$json['error']['postcode'] = L('error_postcode');
		}

		if (!$json) {
			$this->tax->setShippingAddress($this->request->post['country_id'], $this->request->post['zone_id']);

			// Default Shipping Address
			$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
			$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
			$this->session->data['shipping_postcode'] = $this->request->post['postcode'];

			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			M('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'firstname'      => '',
				'lastname'       => '',
				'company'        => '',
				'address_1'      => '',
				'address_2'      => '',
				'postcode'       => $this->request->post['postcode'],
				'city'           => '',
				'zone_id'        => $this->request->post['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $this->request->post['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);

			$quote_data = array();

			$extensions = C('cache_extension_shipping');

			foreach ($extensions as $extension) {
				if (C($extension . '_status')) {
					M('shipping/' . $extension);

					$quote = $this->{'model_shipping_' . $extension}->getQuote($address_data);

					if ($quote) {
						$quote_data[$extension] = array(
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

			if ($this->session->data['shipping_methods']) {
				$json['shipping_method'] = $this->session->data['shipping_methods'];
			} else {
				$json['error']['warning'] = sprintf(L('error_no_shipping'), U('page/contact'));
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>