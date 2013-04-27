<?php 
class ControllerProductSearch extends Controller { 	
	public function index() {
    	$this->language->load('product/search');
		
		M('catalog/category');		
		M('catalog/product');		
		M('tool/image'); 
		
		$filter_name = $this->request->get('filter_name', '');
		$filter_tag = $this->request->get('filter_tag', '');
		if (!$filter_tag) $filter_tag = $filter_name;
		$filter_description = $this->request->get('filter_description', '');
		$filter_category_id = $this->request->get('filter_category_id', 0);
		$filter_sub_category = $this->request->get('filter_sub_category', '');
		$sort = $this->request->get('sort', 'p.sort_order');
		$order = $this->request->get('order', 'ASC');
		$page = $this->request->get('page', 1);
		$limit = $this->request->get('limit', C('config_catalog_limit'));
		
		if ($filter_name) {
			$this->document->setTitle(L('heading_title') .  ' - ' . $filter_name);
		} else {
			$this->document->setTitle(L('heading_title'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array( 
       		'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
      		'separator' => false
   		);
		
		$url = '';		
		if ($filter_name) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
		}			
		if ($filter_tag) {
			$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
		}
		$_url = $this->request->query('filter_description,filter_category_id,filter_sub_category,sort,order,page,limit');
		if ($_url) $url .= '&' . $_url;
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => U('product/search', $url),
      		'separator' => L('text_separator')
   		);
		
		if ($filter_name) {
    		$this->data['heading_title'] = L('heading_title') .  ' - ' . $filter_name;
		} else {
			$this->data['heading_title'] = L('heading_title');
		}
		$this->data['text_empty'] = L('text_empty');
    	$this->data['text_critea'] = L('text_critea');
    	$this->data['text_search'] = L('text_search');
		$this->data['text_keyword'] = L('text_keyword');
		$this->data['text_category'] = L('text_category');
		$this->data['text_sub_category'] = L('text_sub_category');
		$this->data['text_quantity'] = L('text_quantity');
		$this->data['text_manufacturer'] = L('text_manufacturer');
		$this->data['text_model'] = L('text_model');
		$this->data['text_price'] = L('text_price');
		$this->data['text_tax'] = L('text_tax');
		$this->data['text_points'] = L('text_points');
		$this->data['text_compare'] = sprintf(L('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$this->data['text_display'] = L('text_display');
		$this->data['text_list'] = L('text_list');
		$this->data['text_grid'] = L('text_grid');		
		$this->data['text_sort'] = L('text_sort');
		$this->data['text_limit'] = L('text_limit');
		
		$this->data['entry_search'] = L('entry_search');
    	$this->data['entry_description'] = L('entry_description');
		  
    	$this->data['button_search'] = L('button_search');
		$this->data['button_cart'] = L('button_cart');
		$this->data['button_wishlist'] = L('button_wishlist');
		$this->data['button_compare'] = L('button_compare');		
		
		M('catalog/category');
		
		// 3 Level Category Search
		$this->data['categories'] = array();
		
		$categories_1 = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}
				
				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}
			
			$this->data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
		$this->data['products'] = array();
		
		if ($filter_name || $filter_tag) {
			$data = array(
				'filter_name'         => $filter_name, 
				'filter_tag'          => $filter_tag, 
				'filter_description'  => $filter_description,
				'filter_category_id'  => $filter_category_id, 
				'filter_sub_category' => $filter_sub_category, 
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);
					
			$product_total = $this->model_catalog_product->getTotalProducts($data);
								
			$results = $this->model_catalog_product->getProducts($data);
					
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], C('config_image_product_width'), C('config_image_product_height'));
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
				
				if (C('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if (C('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
			
				$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'summary'     => html_entity_decode($result['summary'], ENT_QUOTES, 'UTF-8'),
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf(L('text_reviews'), (int)$result['reviews']),
					'href'        => $result['link'],
				);
			}
					
			$url = '';
			
			if ($filter_name) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			}			
			if ($filter_tag) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
			}
			
			$_url = $this->request->query('filter_description,filter_category_id,filter_sub_category,limit');
			if ($_url) $url .= '&' . $_url;
			
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => L('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => U('product/search', 'sort=p.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => L('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => U('product/search', 'sort=pd.name&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => L('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => U('product/search', 'sort=pd.name&order=DESC' . $url)
			);
	
			$this->data['sorts'][] = array(
				'text'  => L('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => U('product/search', 'sort=p.price&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => L('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => U('product/search', 'sort=p.price&order=DESC' . $url)
			); 
			
			if (C('config_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => L('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => U('product/search', 'sort=rating&order=DESC' . $url)
				); 
				
				$this->data['sorts'][] = array(
					'text'  => L('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => U('product/search', 'sort=rating&order=ASC' . $url)
				);
			}
			
			$this->data['sorts'][] = array(
				'text'  => L('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => U('product/search', 'sort=p.model&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => L('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => U('product/search', 'sort=p.model&order=DESC' . $url)
			);
	
			$url = '';
			
			if ($filter_name) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			}			
			if ($filter_tag) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
			}
			$_url = $this->request->query('filter_description,filter_category_id,filter_sub_category,sort,order');
			if ($_url) $url .= '&' . $_url;
			
			$this->data['limits'] = array();
			
			$this->data['limits'][] = array(
				'text'  => C('config_catalog_limit'),
				'value' => C('config_catalog_limit'),
				'href'  => U('product/search', $url . '&limit=' . C('config_catalog_limit'))
			);
						
			$this->data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => U('product/search', $url . '&limit=25')
			);
			
			$this->data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => U('product/search', $url . '&limit=50')
			);
	
			$this->data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => U('product/search', $url . '&limit=75')
			);
			
			$this->data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => U('product/search', $url . '&limit=100')
			);
					
			$url = '';	
			if ($filter_name) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			}			
			if ($filter_tag) {
				$url .= '&filter_tag=' . urlencode(html_entity_decode($filter_tag, ENT_QUOTES, 'UTF-8'));
			}
			$_url = $this->request->query('filter_description,filter_category_id,filter_sub_category,sort,order,limit');
			if ($_url) $url .= '&' . $_url;
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = L('text_pagination');
			$pagination->url = U('product/search', $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
		}	
		
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_description'] = $filter_description;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_sub_category'] = $filter_sub_category;
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('product/search.tpl');
  	}
}
?>