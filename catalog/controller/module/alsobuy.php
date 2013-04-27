<?php
class ControllerModuleAlsoBuy extends Controller {
	protected function index($setting) {
		$product_id = G('product_id', 0);
		
		if (!($product_id && ROUTE == 'product/product')) return;
		
		M('catalog/product');		
		$results = $this->model_catalog_product->getAlsoBuyProducts($product_id, $setting['limit']);
		
		if (!$results) return;
		
		$this->language->load('module/alsobuy');
		$this->data['heading_title'] = L('heading_title');
		$this->data['button_cart'] = L('button_cart');
		
		$this->data['products'] = array();
		
		M('tool/image');
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}

			if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], C('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], C('config_tax')));
			} else {
				$special = false;
			}

			if (C('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $result['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf(L('text_reviews'), (int)$result['reviews']),
				'href'    	 => $result['link'],
			);
		}
		$this->render('module/' . (!empty($setting['template']) ? $setting['template'] : 'alsobuy.tpl'));
	}
}
?>