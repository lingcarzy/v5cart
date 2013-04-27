<?php
class ControllerProductCategory extends Controller {
	protected $filter_attribute = array();
	
	public function index() {
		global $CATEGORIES, $CATEGORY;
		
		if (!$CATEGORY) $this->redirect(U('error/not_found'));
		
		$this->language->load('product/category');
		M('catalog/category');
		M('catalog/product');
		M('tool/image');
		
		$sort = G('sort', 'p.sort_order');
		$order = G('order', 'ASC');
		$attribute_filter = G('af', '');
		if (!preg_match('/^[0-9\-]+$/', $attribute_filter)) $attribute_filter = '';
		
		$page = (int) G('page', 1);
		$limit = (int) G('limit', C('config_catalog_limit'));
		
		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
       		'separator' => false
   		);

		foreach (explode(',', $CATEGORY['path']) as $cate_id) {
			$this->data['breadcrumbs'][] = array(
				'text'      => $CATEGORIES[$cate_id]['name'],
				'href'      => $CATEGORIES[$cate_id]['link'],
				'separator' => L('text_separator')
			);
		}
		$category_id = $CATEGORY['id'];
			
		$category_info = $this->model_catalog_category->getCategory($category_id);
		
		$this->document->setTitle(!empty($category_info['seo_title']) ? $category_info['seo_title'] : $category_info['name']);
		$this->document->setDescription($category_info['meta_description']);
		$this->document->setKeywords($category_info['meta_keyword']);

		$this->data['heading_title'] = $category_info['name'];
		$this->data['text_refine'] = L('text_refine');
		$this->data['text_empty'] = L('text_empty');			
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
		$this->data['text_empty_result'] = L('text_empty_result');
		
		$this->data['button_cart'] = L('button_cart');
		$this->data['button_wishlist'] = L('button_wishlist');
		$this->data['button_compare'] = L('button_compare');
		$this->data['button_continue'] = L('button_continue');
		
		$this->data['continue'] = HTTP_SERVER;
		
		if ($category_info['image']) {
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], C('config_image_category_width'), C('config_image_category_height'));
		} else {
			$this->data['thumb'] = '';
		}

		$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
		$this->data['compare'] = U('product/compare');
		
		$this->data['categories'] = array();
		
		// if (isset($CATEGORY['sub'])) {
			// foreach (explode(',',$CATEGORY['sub']) as $cate_id) {
			
				// $this->data['categories'][] = array(
					// 'name'  => $CATEGORIES[$cate_id]['name'] . (C('config_product_count') ? ' (' . $CATEGORIES[$cate_id]['total'] . ')' : ''),
					// 'href'  => $CATEGORIES[$cate_id]['link'],
				// );
			// }
		// }
		
		$this->data['products'] = array();
		
		//attribute filters
		$this->filter_attribute = array();
		
		$filter_attribute = array();
		
		if (!empty($category_info['attribute_ids'])) {
			$attributes = $this->model_catalog_category->getCategoryAttributes($category_id, $category_info['attribute_ids']);
			
			if ($attribute_filter) {
				$params = explode('--', $attribute_filter);
				foreach ($params as $param) {
					if (strpos($param, '-') !== FALSE) {
						list($attribute_id, $attribute_value_idx) = explode('-', $param);
						$attribute_id = intval($attribute_id);
						$attribute_value_idx = intval($attribute_value_idx);
						
						if (isset($attributes[$attribute_id])) {
						
							$this->filter_attribute[$attribute_id] = $attribute_value_idx;
							
							$filter_attribute[$attribute_id] = $attributes[$attribute_id]['values'][$attribute_value_idx];
						}
					}
				}
			}
			
			$url = $this->request->query('cate_id,sort,order,limit');
			
			$this->data['attributes'] = array();			
			foreach ($attributes as $attribute_id => $attribute) {
			
				$values = array();
				
				$values[] = array(
					'link' => U('product/category', $url . $this->getAttributeUrl($attribute_id, -1)),
					'value' => L('text_all'),
					'selected' => !isset($this->filter_attribute[$attribute_id])
				);
				
				foreach ($attribute['values'] as $idx => $value) {
					$values[] = array(
						'link' => U('product/category', $url . $this->getAttributeUrl($attribute_id, $idx)),
						'value' => $value,
						'selected' => isset($this->filter_attribute[$attribute_id]) ? ($this->filter_attribute[$attribute_id] == $idx) : false
					);
				}
				
				$this->data['attributes'][] = array(
					'name' => $attribute['name'],
					'values' => $values
				);
			}
		}
		
		$data = array(
			'filter_category_id' => $category_id,
			'filter_attribute'   => $filter_attribute,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
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

			$this->data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'summary' => html_entity_decode($result['summary'], ENT_QUOTES, 'UTF-8'),
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'rating'      => C('config_review_status') ? $result['rating'] : false,
				'reviews'     => sprintf(L('text_reviews'), (int)$result['reviews']),
				'href'        => $result['link']
			);
		}

		$url = $this->request->query('cate_id,af,limit');

		$this->data['sorts'] = array();

		$this->data['sorts'][] = array(
			'text'  => L('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => U('product/category', $url . '&sort=p.sort_order&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => U('product/category', $url . '&sort=pd.name&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => U('product/category', $url . '&sort=pd.name&order=DESC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => U('product/category', $url . '&sort=p.price&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => U('product/category', $url . '&sort=p.price&order=DESC')
		);

		if (C('config_review_status')) {
			$this->data['sorts'][] = array(
				'text'  => L('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => U('product/category', $url . '&sort=rating&order=DESC')
			);

			$this->data['sorts'][] = array(
				'text'  => L('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => U('product/category', $url . '&sort=rating&order=ASC')
			);
		}

		$this->data['sorts'][] = array(
			'text'  => L('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => U('product/category', $url . '&sort=p.model&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => U('product/category', $url . '&sort=p.model&order=DESC')
		);

		$url = $this->request->query('cate_id,af,sort,order');

		$this->data['limits'] = array();

		$this->data['limits'][] = array(
			'text'  => C('config_catalog_limit'),
			'value' => C('config_catalog_limit'),
			'href'  => U('product/category', $url . '&limit=' . C('config_catalog_limit'))
		);

		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => U('product/category', $url . '&limit=25')
		);

		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => U('product/category', $url . '&limit=50')
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => U('product/category', $url . '&limit=75')
		);

		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => U('product/category', $url . '&limit=100')
		);
		
		$url = $this->request->query('sort,qf,order,limit,cate_id');
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = L('text_pagination');
		$pagination->url = U('product/category', $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

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
		
		$this->display('product/category.tpl');
  	}
	
	protected function getAttributeUrl($attribute_id, $attribute_value_idx = -1) {
		$url = '';
		if ($attribute_value_idx > -1) $url = "$attribute_id-$attribute_value_idx";
		foreach ($this->filter_attribute as $aid => $idx) {
			if ($attribute_id != $aid) {
				$url .= "--$aid-$idx";
			}
		}
		$url = trim($url, '-');
		return $url ? "&af=$url" : '';
	}
}
?>