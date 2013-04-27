<?php 
class ControllerAccountWishList extends Controller {
	public function index() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = U('account/wishlist', '', 'SSL');

	  		$this->redirect(U('account/login', '', 'SSL')); 
    	}    	
		
		$this->language->load('account/wishlist');
		
		M('catalog/product');
		
		M('tool/image');
		
		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}
		
		if (isset($this->request->get['remove'])) {
			$key = array_search($this->request->get['remove'], $this->session->data['wishlist']);
			
			if ($key !== false) {
				unset($this->session->data['wishlist'][$key]);
			}
		
			$this->session->set_flashdata('success', L('text_remove'));
		
			$this->redirect(U('account/wishlist'));
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

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => L('heading_title'),
			'href'      => U('account/wishlist'),
        	'separator' => L('text_separator')
      	);
								
		$this->data['heading_title'] = L('heading_title');	
		
		$this->data['text_empty'] = L('text_empty');
     	
		$this->data['column_image'] = L('column_image');
		$this->data['column_name'] = L('column_name');
		$this->data['column_model'] = L('column_model');
		$this->data['column_stock'] = L('column_stock');
		$this->data['column_price'] = L('column_price');
		$this->data['column_action'] = L('column_action');
		
		$this->data['button_continue'] = L('button_continue');
		$this->data['button_cart'] = L('button_cart');
		$this->data['button_remove'] = L('button_remove');
		
		$this->data['success'] = $this->session->flashdata('success');
		
		$this->data['products'] = array();
	
		foreach ($this->session->data['wishlist'] as $key => $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], C('config_image_wishlist_width'), C('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif (C('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = L('text_instock');
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

				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => U('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => U('account/wishlist', 'remove=' . $product_info['product_id'])
				);
			} else {
				unset($this->session->data['wishlist'][$key]);
			}
		}	

		$this->data['continue'] = U('account/account', '', 'SSL');		
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
							
		$this->display('account/wishlist.tpl');		
	}
	
	public function add() {
		$this->language->load('account/wishlist');
		
		$json = array();

		if (!isset($this->session->data['wishlist'])) {
			$this->session->data['wishlist'] = array();
		}
				
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		M('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			if (!in_array($this->request->post['product_id'], $this->session->data['wishlist'])) {	
				$this->session->data['wishlist'][] = $this->request->post['product_id'];
			}
			 
			if ($this->customer->isLogged()) {			
				$json['success'] = sprintf(L('text_success'), U('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], U('account/wishlist'));				
			} else {
				$json['success'] = sprintf(L('text_login'), U('account/login', '', 'SSL'), U('account/register', '', 'SSL'), U('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], U('account/wishlist'));				
			}
			
			$json['total'] = sprintf(L('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}	
		
		$this->response->setOutput(json_encode($json));
	}	
}
?>