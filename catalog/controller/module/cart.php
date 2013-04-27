<?php
class ControllerModuleCart extends Controller {
	public function index() {
		$this->language->load('module/cart');

      	if (isset($this->request->get['remove'])) {
          	$this->cart->remove($this->request->get['remove']);
			unset($this->session->data['vouchers'][$this->request->get['remove']]);
      	}

		// Totals
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		// Display prices
		if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
			$sort_order = array();

			$totals = C("cache_extension_total");

			foreach ($totals as $key => $value) {
				$sort_order[$key] = C($value . '_sort_order');
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

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_items'] = sprintf(L('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));

		$this->data['text_empty'] = L('text_empty');
		$this->data['text_cart'] = L('text_cart');
		$this->data['text_checkout'] = L('text_checkout');

		$this->data['button_remove'] = L('button_remove');

		$this->data['cart'] = U('checkout/cart');

		$this->data['checkout'] = U('checkout/checkout', '', 'SSL');

		M('tool/image');

		$this->data['products'] = array();

		foreach ($this->cart->getProducts() as $product) {
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
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
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
				'price'    => $price,
				'total'    => $total,
				'href'     => $product['link']
			);
		}

		// Gift Voucher
		$this->data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$this->data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'])
				);
			}
		}

		$this->display('module/cart.tpl');
	}
}
?>