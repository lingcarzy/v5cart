<?php
class ControllerModuleFeatured extends Controller {
	protected function index($setting) {
		$this->language->load('module/featured'); 
		
		$this->data['heading_title'] = L('heading_title');
		
		$this->data['button_cart'] = L('button_cart');
		
		M('catalog/product');
		M('tool/image');

		$this->data['products'] = array();

		$products = explode(',', C('featured_product'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		$product_ids = array_slice($products, 0, (int)$setting['limit']);
		$products = $this->model_catalog_product->getProductByIds($product_ids);
		foreach ($products as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $setting['image_width'], $setting['image_height']);
			} else {
				$image = false;
			}

			if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], C('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], C('config_tax')));
			} else {
				$special = false;
			}
			
			if (C('config_review_status')) {
				$rating = $product['rating'];
			} else {
				$rating = false;
			}
				
			$this->data['products'][] = array(
				'product_id' => $product['product_id'],
				'thumb'   	 => $image,
				'name'    	 => $product['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => sprintf(L('featured_text_reviews'), (int)$product['reviews']),
				'href'    	 => $product['link'],
			);
		}
		$this->render('module/' . (!empty($setting['template']) ? $setting['template'] : 'featured.tpl'));
	}
}
?>