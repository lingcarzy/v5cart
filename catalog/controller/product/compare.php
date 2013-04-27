<?php
class ControllerProductCompare extends Controller {
	public function index() {
		$this->language->load('product/compare');

		M('catalog/product');

		M('tool/image');

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

		if (isset($this->request->get['remove'])) {
			$key = array_search($this->request->get['remove'], $this->session->data['compare']);

			if ($key !== false) {
				unset($this->session->data['compare'][$key]);
			}

			$this->session->data['success'] = L('text_remove');

			$this->redirect(U('product/compare'));
		}

		$this->document->setTitle(L('heading_title'));

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => L('heading_title'),
			'href'      => U('product/compare'),
			'separator' => L('text_separator')
		);

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_product'] = L('text_product');
		$this->data['text_name'] = L('text_name');
		$this->data['text_image'] = L('text_image');
		$this->data['text_price'] = L('text_price');
		$this->data['text_model'] = L('text_model');
		$this->data['text_manufacturer'] = L('text_manufacturer');
		$this->data['text_availability'] = L('text_availability');
		$this->data['text_rating'] = L('text_rating');
		$this->data['text_summary'] = L('text_summary');
		$this->data['text_weight'] = L('text_weight');
		$this->data['text_dimension'] = L('text_dimension');
		$this->data['text_empty'] = L('text_empty');

		$this->data['continue'] = HTTP_SERVER;

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_cart'] = L('button_cart');
		$this->data['button_remove'] = L('button_remove');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['review_status'] = C('config_review_status');

		$this->data['products'] = array();

		$this->data['attribute_groups'] = array();

		foreach ($this->session->data['compare'] as $key => $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], C('config_image_compare_width'), C('config_image_compare_height'));
				} else {
					$image = false;
				}

				if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], C('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], C('config_tax')));
				} else {
					$special = false;
				}

				if ($product_info['quantity'] <= 0) {
					$availability = $product_info['stock_status'];
				} elseif (C('config_stock_display')) {
					$availability = $product_info['quantity'];
				} else {
					$availability = L('text_instock');
				}

				$attribute_data = array();

				$attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);

				foreach ($attribute_groups as $attribute_group) {
					foreach ($attribute_group['attribute'] as $attribute) {
						$attribute_data[$attribute['attribute_id']] = $attribute['text'];
					}
				}

				$this->data['products'][$product_id] = array(
					'product_id'   => $product_info['product_id'],
					'name'         => $product_info['name'],
					'thumb'        => $image,
					'price'        => $price,
					'special'      => $special,
					'summary'     => html_entity_decode($product_info['summary'], ENT_QUOTES, 'UTF-8'),
					'model'        => $product_info['model'],
					'manufacturer' => $product_info['manufacturer'],
					'availability' => $availability,
					'rating'       => (int)$product_info['rating'],
					'reviews'      => sprintf(L('text_reviews'), (int)$product_info['reviews']),
					'weight'       => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
					'length'       => $this->length->format($product_info['length'], $product_info['length_class_id']),
					'width'        => $this->length->format($product_info['width'], $product_info['length_class_id']),
					'height'       => $this->length->format($product_info['height'], $product_info['length_class_id']),
					'attribute'    => $attribute_data,
					'href'         => $product_info['link'],
					'remove'       => U('product/compare', 'remove=' . $product_id)
				);

				foreach ($attribute_groups as $attribute_group) {
					$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];

					foreach ($attribute_group['attribute'] as $attribute) {
						$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
					}
				}
			} else {
				unset($this->session->data['compare'][$key]);
			}
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('product/compare.tpl');
  	}

	public function add() {
		$this->language->load('product/compare');

		$json = array();

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

		$product_id = P('product_id', 0);

		M('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (!in_array($product_id, $this->session->data['compare'])) {
				if (count($this->session->data['compare']) >= 4) {
					array_shift($this->session->data['compare']);
				}

				$this->session->data['compare'][] = $product_id;
			}

			$json['success'] = sprintf(L('text_success'), U('product/product', 'product_id=' . $product_id), $product_info['name'], U('product/compare'));

			$json['total'] = sprintf(L('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>