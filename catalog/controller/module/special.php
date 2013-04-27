<?php
class ControllerModuleSpecial extends Controller {
	protected function index($setting) {
		$this->language->load('module/special');
		
		$this->data['heading_title'] = L('heading_title');

		$this->data['button_cart'] = L('button_cart');
		
		M('catalog/product');		
		M('tool/image');

		$this->data['products'] = array();
		
		$data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($data);

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
				'reviews'    => sprintf(L('specials_text_reviews'), (int)$result['reviews']),
				'href'    	 => $result['link'],
			);
		}
		$this->render('module/' . (!empty($setting['template']) ? $setting['template'] : 'special.tpl'));
	}
}
?>