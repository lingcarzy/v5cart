<?php  
class ControllerProductProduct extends Controller {

	public function index() { 
		global $CATEGORIES, $CATEGORY;
		
		M('catalog/product');
		$product_info = null;
		$product_id = (int) G('product_id', 0);
		if ($product_id) $product_info = $this->model_catalog_product->getProduct($product_id);
		
		if (!$product_info) $this->redirect(U('error/not_found'));
		
		$this->language->load('product/product');
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => L('text_home'),
			'href'      => HTTP_SERVER,			
			'separator' => false
		);
		
		if (isset($CATEGORIES[$product_info['cate_id']])) {
			$CATEGORY = $CATEGORIES[$product_info['cate_id']];
		}
		if ($CATEGORY) {
			foreach (explode(',', $CATEGORY['path']) as $cate_id) {
				$this->data['breadcrumbs'][] = array(
					'text'      => $CATEGORIES[$cate_id]['name'],
					'href'      => $CATEGORIES[$cate_id]['link'],
					'separator' => L('text_separator')
				);
			}
		}	

		$this->data['breadcrumbs'][] = array(
			'text'      => $product_info['name'],
			'href'      => $product_info['link'],
			'separator' => L('text_separator')
		);			
		
		$this->document->setTitle(!empty($product_info['seo_title']) ? $product_info['seo_title'] : $product_info['name']);
		$this->document->setDescription($product_info['meta_description']);
		$this->document->setKeywords($product_info['meta_keyword']);
		$this->document->addLink(HTTP_SERVER . $product_info['link'], 'canonical');
		
		$this->data['heading_title'] = $product_info['name'];			
		
		$this->data['text_select'] = L('text_select');
		$this->data['text_manufacturer'] = L('text_manufacturer');
		$this->data['text_model'] = L('text_model');
		$this->data['text_reward'] = L('text_reward');
		$this->data['text_points'] = L('text_points');	
		$this->data['text_discount'] = L('text_discount');
		$this->data['text_stock'] = L('text_stock');
		$this->data['text_price'] = L('text_price');
		$this->data['text_tax'] = L('text_tax');
		$this->data['text_option'] = L('text_option');
		$this->data['text_qty'] = L('text_qty');
		$this->data['text_minimum'] = sprintf(L('text_minimum'), $product_info['minimum']);
		$this->data['text_or'] = L('text_or');
		$this->data['text_write'] = L('text_write');
		$this->data['text_note'] = L('text_note');
		$this->data['text_share'] = L('text_share');
		$this->data['text_wait'] = L('text_wait');
		$this->data['text_tags'] = L('text_tags');
		
		$this->data['entry_name'] = L('entry_name');
		$this->data['entry_review'] = L('entry_review');
		$this->data['entry_rating'] = L('entry_rating');
		$this->data['entry_good'] = L('entry_good');
		$this->data['entry_bad'] = L('entry_bad');
		$this->data['entry_captcha'] = L('entry_captcha');
		
		$this->data['button_cart'] = L('button_cart');
		$this->data['button_wishlist'] = L('button_wishlist');
		$this->data['button_compare'] = L('button_compare');			
		$this->data['button_upload'] = L('button_upload');
		$this->data['button_continue'] = L('button_continue');
		
		M('catalog/review');
		
		$this->data['tab_description'] = L('tab_description');
		$this->data['tab_attribute'] = L('tab_attribute');
		$this->data['tab_review'] = sprintf(L('tab_review'), $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']));
		$this->data['tab_related'] = L('tab_related');
		
		$this->data['product_id'] = $product_id;
		$this->data['manufacturer'] = $product_info['manufacturer'];
		$this->data['manufacturers'] = U('product/manufacturer/info/', 'manufacturer_id=' . $product_info['manufacturer_id']);
		$this->data['model'] = $product_info['model'];
		$this->data['reward'] = $product_info['reward'];
		$this->data['points'] = $product_info['points'];
		
		$stock_statuses = C('cache_stock_status');
		if (isset($stock_statuses[$product_info['stock_status_id']])) {
			$product_info['stock_status'] = $stock_statuses[$product_info['stock_status_id']];
		}
		
		$weight_classes = C('cache_weight_class');
		if (isset($weight_classes[$product_info['weight_class_id']])) {
			$product_info['weight_class'] = $weight_classes[$product_info['weight_class_id']]['unit'];
		}
		
		$length_classes = C('cache_length_class');
		if (isset($length_classes[$product_info['length_class_id']])) {
			$product_info['length_class'] = $length_classes[$product_info['length_class_id']]['unit'];
		}
		
		if ($product_info['quantity'] <= 0) {
			$this->data['stock'] = $product_info['stock_status'];
		} elseif (C('config_stock_display')) {
			$this->data['stock'] = $product_info['quantity'];
		} else {
			$this->data['stock'] = L('text_instock');
		}
		
		M('tool/image');

		if ($product_info['image']) {
			$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], C('config_image_popup_width'), C('config_image_popup_height'));
		} else {
			$this->data['popup'] = '';
		}
		
		if ($product_info['image']) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], C('config_image_thumb_width'), C('config_image_thumb_height'));
		} else {
			$this->data['thumb'] = '';
		}
		
		$this->data['images'] = array();
		
		$results = $this->model_catalog_product->getProductImages($product_id);
		
		foreach ($results as $result) {
			$this->data['images'][] = array(
				'popup' => $this->model_tool_image->resize($result['image'], C('config_image_popup_width'), C('config_image_popup_height')),
				'thumb' => $this->model_tool_image->resize($result['image'], C('config_image_additional_width'), C('config_image_additional_height'))
			);
		}	

		if ((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) {
			$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], C('config_tax')));
		} else {
			$this->data['price'] = false;
		}
					
		if ((float)$product_info['special']) {
			$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], C('config_tax')));
		} else {
			$this->data['special'] = false;
		}
		
		if (C('config_tax')) {
			$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
		} else {
			$this->data['tax'] = false;
		}
		
		$discounts = $this->model_catalog_product->getProductDiscounts($product_id);
		
		$this->data['discounts'] = array(); 
		if (!$this->data['special']) {
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], C('config_tax')))
				);
			}
		}
		$this->data['options'] = array();
		
		foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) { 
			if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
				$option_value_data = array();
				
				foreach ($option['option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if (((C('config_customer_price') && $this->customer->isLogged()) || !C('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], C('config_tax')));
						} else {
							$price = false;
						}
						
						$option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}
				
				$this->data['options'][] = array(
					'product_option_id' => $option['product_option_id'],
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					'option_value'      => $option_value_data,
					'required'          => $option['required']
				);					
			} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
				$this->data['options'][] = array(
					'product_option_id' => $option['product_option_id'],
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					'option_value'      => $option['option_value'],
					'required'          => $option['required']
				);						
			}
		}
						
		if ($product_info['minimum']) {
			$this->data['minimum'] = $product_info['minimum'];
		} else {
			$this->data['minimum'] = 1;
		}
		
		$this->data['review_status'] = C('config_review_status');
		$this->data['reviews'] = sprintf(L('text_reviews'), $product_info['reviews']);
		$this->data['rating'] = (int)$product_info['rating'];
		$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
		$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product_id);
		
		$this->data['products'] = array();
		
		$results = $this->model_catalog_product->getProductRelated($product_id);
		
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], C('config_image_related_width'), C('config_image_related_height'));
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
				$rating = (int)$result['rating'];
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
		
		$this->data['tags'] = array();
				
		$tags = explode(',', $product_info['tag']);
		
		foreach ($tags as $tag) {
			$this->data['tags'][] = array(
				'tag'  => trim($tag),
				'href' => U('product/search', 'filter_tag=' . trim($tag))
			);
		}
		
		$this->model_catalog_product->updateViewed($product_id);
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('product/product.tpl');
  	}
	
	public function review() {
    	$this->language->load('product/product');
		
		$this->data['text_on'] = L('text_on');
		$this->data['text_no_reviews'] = L('text_no_reviews');
		
		M('catalog/review');
		
		$page = (int) $this->request->get('page', 1);
		$product_id = (int) $this->request->get('product_id', 0);
		
		$this->data['reviews'] = array();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($product_id);
			
		$results = $this->model_catalog_review->getReviewsByProductId($product_id, ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
        		'reviews'    => sprintf(L('text_reviews'), (int)$review_total),
        		'date_added' => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
			
		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = L('text_pagination');
		$pagination->url = U('product/product/review', "product_id=$product_id" . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->display('product/review.tpl');
	}
	
	public function write() {
		$this->language->load('product/product');
		
		M('catalog/review');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = L('error_name');
			}
			
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = L('error_text');
			}
	
			if (empty($this->request->post['rating'])) {
				$json['error'] = L('error_rating');
			}
	
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = L('error_captcha');
			}
				
			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
				
				$json['success'] = L('text_success');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function captcha() {
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
	
	public function upload() {
		$this->language->load('product/product');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
			
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
        		$json['error'] = L('error_filename');
	  		}	  	
			
			// Allowed file extension types
			$allowed = array();
			
			$filetypes = explode(",", C('config_file_extension_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
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
		
		if (!$json && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			$file = basename($filename) . '.' . md5(mt_rand());
			
			// Hide the uploaded file name so people can not link to it directly.
			$json['file'] = $this->encryption->encrypt($file);
			
			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
			
			$json['success'] = L('text_upload');
		}	
		
		$this->response->setOutput(json_encode($json));		
	}
}
?>