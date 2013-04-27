<?php
class ControllerCatalogProduct extends Controller {

  	public function index() {
		$this->language->load('catalog/product');
		M('catalog/product');

		$this->getList();
  	}

  	public function insert() {
    	$this->language->load('catalog/product');

		M('catalog/product');

    	if ($this->request->isPost() && $this->validateForm()) {			
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/product'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->language->load('catalog/product');

		M('catalog/product');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/product'));
		}

    	$this->getForm();
  	}
	
	public function update_status() {
		if (isset($this->request->post['selected'])) {
			$this->language->load('catalog/product');
			$status = P('status', 1);
			$product_ids = implode(',', $this->request->post['selected']);
			$this->db->query("UPDATE @@product SET status=$status WHERE product_id IN ($product_ids)");
			$this->session->set_flashdata('success', L('text_success'));			
		}
		
		$this->redirect(UA('catalog/product'));
	}
	
  	public function delete() {
    	$this->language->load('catalog/product');

		M('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/product'));
		}

    	$this->getList();
  	}

  	public function copy() {
    	$this->language->load('catalog/product');

		M('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
	  		}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/product'));
		}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter('product_query_filter');
		$filter_name = $qf->get('filter_name');
		$filter_model = $qf->get('filter_model');
		$filter_price = $qf->get('filter_price');
		$filter_quantity = $qf->get('filter_quantity');
		$filter_status = $qf->get('filter_status');
		$filter_category_id = $qf->get('filter_category_id', 0);
		$filter_supplier_id = $qf->get('filter_supplier_id', 0);
		$sort = $qf->get('sort', 'pd.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);
		

		M('catalog/category');		
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		M('catalog/supplier');		
		$this->data['suppliers'] = $this->model_catalog_supplier->getSupplierOptions();

		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'filter_category_id' => $filter_category_id,
			'filter_supplier_id' => $filter_supplier_id,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * C('config_admin_limit'),
			'limit'           => C('config_admin_limit')
		);
		
		M('tool/image');

		$results = $this->model_catalog_product->getProducts($data, $product_total);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/product/update', 'product_id=' . $result['product_id'])
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 100, 100);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
					$special = $product_special['price'];
					break;
				}
			}

      		$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'cost'      =>  $result['cost'],
				'msrp'      =>  $result['msrp'],
				'special'    => $special,
				'image'      => $image,
				'viewed'     => $result['viewed'],
				'reviews'   =>  $result['reviews'],
				'rating'    =>  round($result['rating']),
				'quantity'   => $result['quantity'],
				'href'      => HTTP_CATALOG . $result['link'],
				'source_link' => $result['source_link'],
				'status'     => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('catalog/product', 'sort=pd.name&order=' . $url);
		$this->data['sort_model'] = UA('catalog/product', 'sort=p.model&order=' . $url);
		$this->data['sort_price'] = UA('catalog/product', 'sort=p.price&order=' . $url);
		$this->data['sort_quantity'] = UA('catalog/product', 'sort=p.quantity&order=' . $url);
		$this->data['sort_status'] = UA('catalog/product', 'sort=p.status&order=' . $url);
		$this->data['sort_order'] = UA('catalog/product', 'sort=p.sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/product', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_supplier_id'] = $filter_supplier_id;
		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);


		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('catalog/product_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');
		$this->document->addScript('view/javascript/jquery/jquery.checkboxtree.min.js');
		$this->document->addStyle('view/stylesheet/jquery.checkboxtree.min.css');
		
		$this->data['token'] = $this->session->data['token'];

		$product_id = G('product_id');

		if ($product_id) {
			$this->data['action'] = UA('catalog/product/update', 'product_id=' . $product_id);
		} else {
			$this->data['action'] = UA('catalog/product/insert');
		}

		if ($product_id && !$this->request->isPost()) {
      		$product_info = $this->model_catalog_product->getProduct($product_id);
    	}

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif ($product_id) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($product_id);
		} else {
			$this->data['product_description'] = array();
		}

		M('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['product_store'])) {
			$this->data['product_store'] = $this->request->post['product_store'];
		} elseif ($product_id) {
			$this->data['product_store'] = $this->model_catalog_product->getProductStores($product_id);
		} else {
			$this->data['product_store'] = array(0);
		}

		M('tool/image');
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		M('catalog/manufacturer');
    	$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		
		M('catalog/supplier');		
		$this->data['suppliers'] = $this->model_catalog_supplier->getSupplierOptions();
		
		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->data['stock_statuses'] = C('cache_stock_status');
		$this->data['weight_classes'] = C('cache_weight_class');
		$this->data['length_classes'] = C('cache_length_class');

		if (!empty($product_info)) {
			$this->data['model'] = $product_info['model'];
			$this->data['sku'] = $product_info['sku'];
			$this->data['upc'] = $product_info['upc'];
			$this->data['ean'] = $product_info['ean'];
			$this->data['jan'] = $product_info['jan'];
			$this->data['isbn'] = $product_info['isbn'];
			$this->data['mpn'] = $product_info['mpn'];
			$this->data['location'] = $product_info['location'];
			$this->data['seo_url'] = $product_info['seo_url'];
			$this->data['image'] = $product_info['image'];
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
			$this->data['supplier_id'] = $product_info['supplier_id'];
			$this->data['source_link'] = $product_info['source_link'];
			$this->data['shipping'] = $product_info['shipping'];
			$this->data['price'] = $product_info['price'];
			$this->data['cost'] = $product_info['cost'];
			$this->data['msrp'] = $product_info['msrp'];
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
			$this->data['tax_class_id'] = $product_info['tax_class_id'];
			$this->data['quantity'] = $product_info['quantity'];
      		$this->data['minimum'] = $product_info['minimum'];
			$this->data['subtract'] = $product_info['subtract'];
			$this->data['sort_order'] = $product_info['sort_order'];
			$this->data['stock_status_id'] = $product_info['stock_status_id'];
			$this->data['status'] = $product_info['status'];
			$this->data['weight'] = $product_info['weight'];
			$this->data['weight_class_id'] = $product_info['weight_class_id'];
			$this->data['length'] = $product_info['length'];
			$this->data['width'] = $product_info['width'];
			$this->data['height'] = $product_info['height'];
			$this->data['length_class_id'] = $product_info['length_class_id'];
			$this->data['points'] = $product_info['points'];
			$this->data['cate_id'] = $product_info['cate_id'];
		}
		else {
			$this->data['model'] = P('model');
			$this->data['sku'] = P('sku');
			$this->data['upc'] = P('upc');
			$this->data['ean'] = P('ean');
			$this->data['jan'] = P('jan');
			$this->data['isbn'] = P('isbn');
			$this->data['mpn'] = P('mpn');
			$this->data['location'] = P('location');
			$this->data['seo_url'] = P('seo_url');
			$this->data['image'] = P('image');
			$this->data['price'] = P('price');
			$this->data['cost'] = P('cost', 0);
			$this->data['msrp'] = P('msrp', 0);
			$this->data['manufacturer_id'] = P('manufacturer_id', 0);
			$this->data['supplier_id'] = P('supplier_id', 0);
			$this->data['source_link'] = P('source_link');
			$this->data['shipping'] = P('shipping', 1);
			$this->data['tax_class_id'] = P('tax_class_id', 0);
			$this->data['date_available'] = P('date_available', date('Y-m-d', time() - 86400));
			$this->data['quantity'] = P('quantity', 1000);
			$this->data['minimum'] = P('minimum', 1);
			$this->data['subtract'] = P('subtract', 0);
			$this->data['sort_order'] = P('sort_order', 1);
			$this->data['stock_status_id'] = P('stock_status_id', C('config_stock_status_id'));
			$this->data['status'] = P('status', 1);
			$this->data['weight_class_id'] = P('weight_class_id', C('config_weight_class_id'));
			$this->data['length_class_id'] = P('length_class_id', C('config_length_class_id'));
			$this->data['weight'] = P('weight');
			$this->data['length'] = P('length');
			$this->data['width'] = P('width');
			$this->data['height'] = P('height');
			$this->data['points'] = P('points');
			$this->data['cate_id'] = P('cate_id', 0);
		}

		if (isset($this->request->post['product_attribute'])) {
			$this->data['product_attributes'] = $this->request->post['product_attribute'];
		} elseif ($product_id) {
			$this->data['product_attributes'] = $this->model_catalog_product->getProductAttributes($product_id);
		} else {
			$this->data['product_attributes'] = array();
		}
		
		if ($this->data['product_attributes']) {
			$attribute_group_ids = array();
			foreach ($this->data['product_attributes'] as $product_attribute) {
				$attribute_group_ids[] = $product_attribute['attribute_group_id'];
			}
			$attribute_group_ids = array_unique($attribute_group_ids);
			$this->data['attribute_groups'] = $this->model_catalog_product->getAttributeGroupByIds($attribute_group_ids);
		}
		else $this->data['attribute_groups'] = array();
		
		M('catalog/attribute');
		
		M('catalog/option');

		if (isset($this->request->post['product_option'])) {
			$product_options = $this->request->post['product_option'];
		} elseif ($product_id) {
			$product_options = $this->model_catalog_product->getProductOptions($product_id);
		} else {
			$product_options = array();
		}

		$this->data['product_options'] = array();

		foreach ($product_options as $product_option) {
			if (in_array($product_option['type'], array('select', 'radio', 'checkbox', 'image'))) {
				$product_option_value_data = array();

				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$this->data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'required'             => $product_option['required']
				);
			} else {
				$this->data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);
			}
		}

		$this->data['option_values'] = array();

		foreach ($product_options as $product_option) {
			if (in_array($product_option['type'], array('select', 'radio', 'checkbox', 'image'))) {
				if (!isset($this->data['option_values'][$product_option['option_id']])) {
					$this->data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}

		M('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		//discount
		if (isset($this->request->post['product_discount'])) {
			$this->data['product_discounts'] = $this->request->post['product_discount'];
		} elseif ($product_id) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
		} else {
			$this->data['product_discounts'] = array();
		}
		
		//special
		if (isset($this->request->post['product_special'])) {
			$this->data['product_specials'] = $this->request->post['product_special'];
		} elseif ($product_id) {
			$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($product_id);
		} else {
			$this->data['product_specials'] = array();
		}
		
		//images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif ($product_id) {
			$product_images = $this->model_catalog_product->getProductImages($product_id);
		} else {
			$product_images = array();
		}

		$this->data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		//download
		M('catalog/download');
		$this->data['downloads'] = $this->model_catalog_download->getDownloads();

		if (isset($this->request->post['product_download'])) {
			$this->data['product_download'] = $this->request->post['product_download'];
		} elseif ($product_id) {
			$this->data['product_download'] = $this->model_catalog_product->getProductDownloads($product_id);
		} else {
			$this->data['product_download'] = array();
		}
		
		//category
		M('catalog/category');
		$this->data['category_tree'] = $this->model_catalog_category->getCategoryTree();
		
		if (isset($this->request->post['product_category'])) {
			$this->data['product_category'] = $this->request->post['product_category'];
		} elseif ($product_id) {
			$this->data['product_category'] = $this->model_catalog_product->getProductCategories($product_id);
		} else {
			$this->data['product_category'] = array();
		}
		
		//related product
		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif ($product_id) {
			$products = $this->model_catalog_product->getProductRelated($product_id);
		} else {
			$products = array();
		}

		$this->data['product_related'] = array();

		foreach ($products as $_product_id) {
			$related_info = $this->model_catalog_product->getProduct($_product_id);

			if ($related_info) {
				$this->data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}
		
		//reward
		if (isset($this->request->post['product_reward'])) {
			$this->data['product_reward'] = $this->request->post['product_reward'];
		} elseif ($product_id) {
			$this->data['product_reward'] = $this->model_catalog_product->getProductRewards($product_id);
		} else {
			$this->data['product_reward'] = array();
		}
		
		//layout
		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->post['product_layout'])) {
			$this->data['product_layout'] = $this->request->post['product_layout'];
		} elseif ($product_id) {
			$this->data['product_layout'] = $this->model_catalog_product->getProductLayouts($product_id);
		} else {
			$this->data['product_layout'] = array();
		}
		
		//product template
		// M('product/product_tpl');
		// $this->data['templates'] = $this->model_product_product_tpl->getTemplates(0, 0, $total, 'status=1');
		
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/product_form.tpl');
  	}
	
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$this->load->library('form_validation', true);
    	foreach ($this->request->post['product_description'] as $language_id => $value) {
			$this->form_validation->set_rules("product_description[$language_id][name]", '', 'required|range_length[3,255]', L('error_name'));
    	}
		$this->form_validation->set_rules('model', '', 'required|range_length[3,64]', L('error_model'));

		if ($this->form_validation->run()) {
			return true;
		}
		else {
			$this->setMessage('error_warning', L('error_warning'));
			return false;
		}
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		return true;
  	}

  	protected function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		return true;
  	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
			M('catalog/product');

			$filter_name = $this->request->get('filter_name', '');
			$filter_model = $this->request->get('filter_model', '');
			$filter_category_id = $this->request->get('filter_category_id', '');
			$filter_sub_category = $this->request->get('filter_sub_category', '');
			$limit = $this->request->get('limit', 20);
			$data = array(
				'filter_name'         => $filter_name,
				'filter_model'        => $filter_model,
				'filter_category_id'  => $filter_category_id,
				'filter_sub_category' => $filter_sub_category,
				'start'               => 0,
				'limit'               => $limit
			);
			$results = $this->model_catalog_product->getProducts($data, $total);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
						$option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $product_option_value['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], C('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);
						}

						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $option_value_data,
							'required'          => $product_option['required']
						);
					} else {
						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'option_id'         => $product_option['option_id'],
							'name'              => $product_option['name'],
							'type'              => $product_option['type'],
							'option_value'      => $product_option['option_value'],
							'required'          => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>