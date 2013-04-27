<?php
class ControllerProductManufacturer extends Controller {
	public function index() {
		$this->language->load('product/manufacturer');

		M('catalog/manufacturer');
		M('tool/image');

		$this->document->setTitle(L('heading_title'));
		$this->data['heading_title'] = L('heading_title');

		$this->data['text_index'] = L('text_index');
		$this->data['text_empty'] = L('text_empty');

		$this->data['button_continue'] = L('button_continue');
		$this->data['continue'] = HTTP_SERVER;

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

		$this->data['breadcrumbs'][] = array(
			'text'      => L('text_brand'),
			'href'      => U('product/manufacturer'),
			'separator' => L('text_separator')
		);

		$this->data['categories'] = array();

		$results = $this->model_catalog_manufacturer->getManufacturers();

		foreach ($results as $result) {
			if (is_numeric(utf8_substr($result['name'], 0, 1))) {
				$key = '0 - 9';
			} else {
				$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
			}

			if (!isset($this->data['manufacturers'][$key])) {
				$this->data['categories'][$key]['name'] = $key;
			}

			$this->data['categories'][$key]['manufacturer'][] = array(
				'name' => $result['name'],
				'href' => U('product/manufacturer/info/', 'manufacturer_id=' . $result['manufacturer_id'])
			);
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('product/manufacturer_list.tpl');
  	}

	public function info($manufacturer_id = 0) {
    	$this->language->load('product/manufacturer');

		M('catalog/manufacturer');

		if (!$manufacturer_id) $manufacturer_id = $this->request->get('manufacturer_id', 0);
		$manufacturer_id = (int) $manufacturer_id;

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

		if (!$manufacturer_info) $this->redirect(U('error/not_found'));

		$sort = $this->request->get('sort', 'p.sort_order');
		$order = $this->request->get('order', 'ASC');
		$page = $this->request->get('page', 1);
		$limit = $this->request->get('limit', C('config_catalog_limit'));

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
      		'separator' => false
   		);

		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_brand'),
			'href'      => U('product/manufacturer'),
      		'separator' => L('text_separator')
   		);

		$this->data['heading_title'] = $manufacturer_info['name'];

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

		$this->data['button_cart'] = L('button_cart');
		$this->data['button_wishlist'] = L('button_wishlist');
		$this->data['button_compare'] = L('button_compare');
		$this->data['button_continue'] = L('button_continue');

		$this->data['continue'] = HTTP_SERVER;

		M('catalog/product');
		M('tool/image');

		$this->document->setTitle($manufacturer_info['name']);

		$url = $this->request->get('sort,order,page,limit');

		$this->data['breadcrumbs'][] = array(
			'text'      => $manufacturer_info['name'],
			'href'      => U('product/manufacturer/info/' . $manufacturer_id, $url),
			'separator' => L('text_separator')
		);

		$this->data['products'] = array();

		$data = array(
			'filter_manufacturer_id' => $manufacturer_id,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $limit,
			'limit'                  => $limit
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
				'href'        => $result['link']
			);
		}

		$url = $this->request->query('manufacturer_id,limit');
		$this->data['sorts'] = array();

		$this->data['sorts'][] = array(
			'text'  => L('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => U('product/manufacturer/info', $url . '&sort=p.sort_order&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => U('product/manufacturer/info', $url . '&sort=pd.name&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => U('product/manufacturer/info', $url . '&sort=pd.name&order=DESC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => U('product/manufacturer/info', $url . '&sort=p.price&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => U('product/manufacturer/info', $url . '&sort=p.price&order=DESC')
		);

		if (C('config_review_status')) {
			$this->data['sorts'][] = array(
				'text'  => L('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => U('product/manufacturer/info', $url . '&sort=rating&order=DESC')
			);

			$this->data['sorts'][] = array(
				'text'  => L('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => U('product/manufacturer/info', $url. '&sort=rating&order=ASC')
			);
		}

		$this->data['sorts'][] = array(
			'text'  => L('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => U('product/manufacturer/info', $url . '&sort=p.model&order=ASC')
		);

		$this->data['sorts'][] = array(
			'text'  => L('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => U('product/manufacturer/info', $url . '&sort=p.model&order=DESC')
		);

		$url = $this->request->query('manufacturer_id,sort,order');

		$this->data['limits'] = array();

		$this->data['limits'][] = array(
			'text'  => C('config_catalog_limit'),
			'value' => C('config_catalog_limit'),
			'href'  => U('product/manufacturer/info', $url . '&limit=' . C('config_catalog_limit'))
		);

		$this->data['limits'][] = array(
			'text'  => 25,
			'value' => 25,
			'href'  => U('product/manufacturer/info', $url . '&limit=25')
		);

		$this->data['limits'][] = array(
			'text'  => 50,
			'value' => 50,
			'href'  => U('product/manufacturer/info', $url . '&limit=50')
		);

		$this->data['limits'][] = array(
			'text'  => 75,
			'value' => 75,
			'href'  => U('product/manufacturer/info', $url . '&limit=75')
		);

		$this->data['limits'][] = array(
			'text'  => 100,
			'value' => 100,
			'href'  => U('product/manufacturer/info', $url . '&limit=100')
		);

		$url = $this->request->query('manufacturer_id,sort,order,limit');
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = L('text_pagination');
		$pagination->url = U('product/manufacturer/info', $url . '&page={page}');

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

		$this->display('product/manufacturer_info.tpl');
  	}
}
?>