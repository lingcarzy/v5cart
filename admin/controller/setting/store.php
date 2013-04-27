<?php
class ControllerSettingStore extends Controller {

	public function index() {
		$this->language->load('setting/store');
		
		M('setting/store');
		$this->getList();
	}

  	public function insert() {
    	$this->language->load('setting/store');
		
		M('setting/store');

    	if ($this->request->isPost() && $this->validateForm()) {
			$store_id = $this->model_setting_store->addStore($this->request->post);
			M('setting/setting');
			$this->model_setting_setting->editSetting('config', $this->request->post, $store_id);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('setting/store'));
    	}
    	$this->getForm();
  	}

  	public function update() {
    	$this->language->load('setting/store');
		
		M('setting/store');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);

			M('setting/setting');
			$this->model_setting_setting->editSetting('config', $this->request->post, $this->request->get['store_id']);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('setting/store', 'store_id=' . $this->request->get['store_id']));
		}
    	$this->getForm();
  	}

  	public function delete() {
    	$this->language->load('setting/store');
		
		M('setting/store');
		M('setting/setting');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $store_id) {
				$this->model_setting_store->deleteStore($store_id);
				$this->model_setting_setting->deleteSetting('config', $store_id);
			}

			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(UA('setting/store'));
		}
    	$this->getList();
  	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->data['stores'] = array();

		$action = array();

		$action[] = array(
			'text' => L('text_edit'),
			'href' => UA('setting/setting')
		);

		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'     => C('config_name') . L('text_default'),
			'url'      => HTTP_CATALOG,
			'selected' => isset($this->request->post['selected']) && in_array(0, $this->request->post['selected']),
			'action'   => $action
		);

		$store_total = $this->model_setting_store->getTotalStores();

		$results = $this->model_setting_store->getStores();

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('setting/store/update', 'store_id=' . $result['store_id'])
			);

			$this->data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'selected' => isset($this->request->post['selected']) && in_array($result['store_id'], $this->request->post['selected']),
				'action'   => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('setting/store_list.tpl');
	}

	public function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$store_id = G('store_id');
		
		if ($store_id) {
			$this->data['action'] = UA('setting/store/update','store_id=' . $store_id);
		} else {
			$this->data['action'] = UA('setting/store/insert');
		}

		if ($store_id && !$this->request->isPost()) {
			M('setting/setting');
      		$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
    	}

		$this->data['token'] = $this->session->data['token'];

		if (!empty($store_info['config_url'])) {
			$this->data['config_url'] = $store_info['config_url'];
			$this->data['config_ssl'] = $store_info['config_ssl'];
			$this->data['config_name'] = $store_info['config_name'];
			$this->data['config_owner'] = $store_info['config_owner'];
			$this->data['config_address'] = $store_info['config_address'];
			$this->data['config_email'] = $store_info['config_email'];
			$this->data['config_telephone'] = $store_info['config_telephone'];
			$this->data['config_fax'] = $store_info['config_fax'];
			$this->data['config_title'] = $store_info['config_title'];
			$this->data['config_meta_description'] = $store_info['config_meta_description'];
			$this->data['config_layout_id'] = $store_info['config_layout_id'];
			$this->data['config_template'] = $store_info['config_template'];
			$this->data['config_country_id'] = $store_info['config_country_id'];
			$this->data['config_zone_id'] = $store_info['config_zone_id'];
			$this->data['config_language'] = $store_info['config_language'];
			$this->data['config_currency'] = $store_info['config_currency'];
			$this->data['config_catalog_limit'] = $store_info['config_catalog_limit'];		
			$this->data['config_tax'] = $store_info['config_tax'];
			$this->data['config_tax_default'] = $store_info['config_tax_default'];
			$this->data['config_tax_customer'] = $store_info['config_tax_customer'];
			$this->data['config_customer_group_id'] = $store_info['config_customer_group_id'];
			$this->data['config_customer_group_display'] = $store_info['config_customer_group_display'];
			$this->data['config_customer_price'] = $store_info['config_customer_price'];
			$this->data['config_account_id'] = $store_info['config_account_id'];
			$this->data['config_cart_weight'] = $store_info['config_cart_weight'];
			$this->data['config_guest_checkout'] = $store_info['config_guest_checkout'];
			$this->data['config_checkout_id'] = $store_info['config_checkout_id'];
			$this->data['config_order_status_id'] = $store_info['config_order_status_id'];
			$this->data['config_stock_display'] = $store_info['config_stock_display'];
			$this->data['config_stock_checkout'] = $store_info['config_stock_checkout'];
			$this->data['config_logo'] = $store_info['config_logo'];
			$this->data['config_icon'] = $store_info['config_icon'];
			$this->data['config_image_category_height'] = $store_info['config_image_category_height'];
			$this->data['config_image_category_width'] = $store_info['config_image_category_width'];
			$this->data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];
			$this->data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];
			$this->data['config_image_popup_width'] = $store_info['config_image_popup_width'];
			$this->data['config_image_popup_height'] = $store_info['config_image_popup_height'];
			$this->data['config_image_product_width'] = $store_info['config_image_product_width'];
			$this->data['config_image_product_height'] = $store_info['config_image_product_height'];
			$this->data['config_image_additional_width'] = $store_info['config_image_additional_width'];
			$this->data['config_image_additional_height'] = $store_info['config_image_additional_height'];
			$this->data['config_image_related_width'] = $store_info['config_image_related_width'];
			$this->data['config_image_related_height'] = $store_info['config_image_related_height'];
			$this->data['config_image_compare_width'] = $store_info['config_image_compare_width'];
			$this->data['config_image_compare_height'] = $store_info['config_image_compare_height'];
			$this->data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];
			$this->data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];
			$this->data['config_image_cart_width'] = $store_info['config_image_cart_width'];
			$this->data['config_image_cart_height'] = $store_info['config_image_cart_height'];
			$this->data['config_use_ssl'] = $store_info['config_use_ssl'];
			
		} else {
			$this->data['config_url'] = P('config_url');
			$this->data['config_ssl'] = P('config_ssl');
			$this->data['config_name'] = P('config_name');
			$this->data['config_owner'] = P('config_owner');
			$this->data['config_address'] = P('config_address');
			$this->data['config_email'] = P('config_email');
			$this->data['config_telephone'] = P('config_telephone');
			$this->data['config_fax'] = P('config_fax');
			$this->data['config_title'] = P('config_title');
			$this->data['config_meta_description'] = P('config_meta_description');
			$this->data['config_layout_id'] = P('config_layout_id', 0);
			$this->data['config_template'] = P('config_template', 'default');
			$this->data['config_country_id'] = P('config_country_id', C('config_country_id'));
			$this->data['config_zone_id'] = P('config_zone_id', C('config_zone_id'));
			$this->data['config_language'] = P('config_language', C('config_language'));
			$this->data['config_currency'] = P('config_currency', C('config_currency'));
			$this->data['config_catalog_limit'] = P('config_catalog_limit', 12);
			$this->data['config_tax'] = P('config_tax');
			$this->data['config_tax_default'] = P('config_tax_default');
			$this->data['config_tax_customer'] = P('config_tax_customer');
			$this->data['config_customer_group_id'] = P('config_customer_group_id');
			$this->data['config_customer_group_display'] = P('config_customer_group_display', array());
			$this->data['config_customer_price'] = P('config_customer_price');
			$this->data['config_account_id'] = P('config_account_id');
			$this->data['config_cart_weight'] = P('config_cart_weight');
			$this->data['config_guest_checkout'] = P('config_guest_checkout');
			$this->data['config_checkout_id'] = P('config_checkout_id');
			$this->data['config_order_status_id'] = P('config_order_status_id');
			$this->data['config_stock_display'] = P('config_stock_display');
			$this->data['config_stock_checkout'] = P('config_stock_checkout');
			$this->data['config_logo'] = P('config_logo');
			$this->data['config_icon'] = P('config_icon');
			$this->data['config_image_category_height'] = P('config_image_category_height', 80);
			$this->data['config_image_category_width'] = P('config_image_category_width', 80);
			$this->data['config_image_thumb_width'] = P('config_image_thumb_width', 228);
			$this->data['config_image_thumb_height'] = P('config_image_thumb_height', 228);
			$this->data['config_image_popup_width'] = P('config_image_popup_width', 500);
			$this->data['config_image_popup_height'] = P('config_image_popup_height', 500);
			$this->data['config_image_product_width'] = P('config_image_product_width', 80);
			$this->data['config_image_product_height'] = P('config_image_product_height', 80);
			$this->data['config_image_additional_width'] = P('config_image_additional_width', 74);
			$this->data['config_image_additional_height'] = P('config_image_additional_height', 74);
			$this->data['config_image_related_width'] = P('config_image_related_width', 80);
			$this->data['config_image_related_height'] = P('config_image_related_height', 80);
			$this->data['config_image_compare_width'] = P('config_image_compare_width', 90);
			$this->data['config_image_compare_height'] = P('config_image_compare_height', 90);
			$this->data['config_image_wishlist_width'] = P('config_image_wishlist_width', 50);
			$this->data['config_image_wishlist_height'] = P('config_image_wishlist_height', 50);
			$this->data['config_image_cart_width'] = P('config_image_cart_width', 80);
			$this->data['config_image_cart_height'] = P('config_image_cart_height', 80);
			$this->data['config_use_ssl'] = P('config_use_ssl');
		}

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->data['templates'] = array();
		
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}

		$this->data['countries'] = cache_read('country.php');
		$this->data['languages'] = C('cache_language');
		$this->data['currencies'] = C('cache_currency');

		M('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		M('catalog/page');
		$this->data['pages'] = $this->model_catalog_page->getPages();

		$this->data['order_statuses'] = C('cache_order_status');
		
		M('tool/image');
		
		if (isset($store_info['config_logo']) && file_exists(DIR_IMAGE . $store_info['config_logo']) && is_file(DIR_IMAGE . $store_info['config_logo'])) {
			$this->data['logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($store_info['config_icon']) && file_exists(DIR_IMAGE . $store_info['config_icon']) && is_file(DIR_IMAGE . $store_info['config_icon'])) {
			$this->data['icon'] = $this->model_tool_image->resize($store_info['config_icon'], 100, 100);
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('setting/store_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$this->load->library('form_validation', true);
    	$this->form_validation->set_rules('config_url', '', 'required', L('error_url'));
		$this->form_validation->set_rules('config_name', '', 'required', L('error_name'));
		$this->form_validation->set_rules('config_owner', '', 'required|range_length[3,64]', L('error_owner'));
		$this->form_validation->set_rules('config_address', '', 'required|range_length[3,256]', L('error_address'));
		$this->form_validation->set_rules('config_email', '', 'required|max_length[96]|email', L('error_email'));
		$this->form_validation->set_rules('config_telephone', '', 'required|range_length[3,32]', L('error_telephone'));
		$this->form_validation->set_rules('config_title', '', 'required', L('error_title'));
		$this->form_validation->set_rules('config_image_category_width', '', 'required', L('error_image_category'));
		$this->form_validation->set_rules('config_image_category_height', '', 'required', L('error_image_category'));
		$this->form_validation->set_rules('config_image_thumb_width', '', 'required', L('error_image_thumb'));
		$this->form_validation->set_rules('config_image_thumb_height', '', 'required', L('error_image_thumb'));
		$this->form_validation->set_rules('config_image_popup_width', '', 'required', L('error_image_popup'));
		$this->form_validation->set_rules('config_image_popup_height', '', 'required', L('error_image_popup'));
		$this->form_validation->set_rules('config_image_product_width', '', 'required', L('error_image_product'));
		$this->form_validation->set_rules('config_image_product_height', '', 'required', L('error_image_product'));
		$this->form_validation->set_rules('config_image_additional_width', '', 'required', L('error_image_additional'));
		$this->form_validation->set_rules('config_image_additional_height', '', 'required', L('error_image_additional'));
		$this->form_validation->set_rules('config_image_related_width', '', 'required', L('error_image_related'));
		$this->form_validation->set_rules('config_image_related_height', '', 'required', L('error_image_related'));
		$this->form_validation->set_rules('config_image_compare_width', '', 'required', L('error_image_compare'));
		$this->form_validation->set_rules('config_image_compare_height', '', 'required', L('error_image_compare'));
		$this->form_validation->set_rules('config_image_wishlist_width', '', 'required', L('error_image_wishlist'));
		$this->form_validation->set_rules('config_image_wishlist_height', '', 'required', L('error_image_wishlist'));
		$this->form_validation->set_rules('config_image_cart_width', '', 'required', L('error_image_cart'));
		$this->form_validation->set_rules('config_image_cart_height', '', 'required', L('error_image_cart'));
		$this->form_validation->set_rules('config_catalog_limit', '', 'required', L('error_limit'));

		if ($this->form_validation->run()) return true;
		else {
			$this->setMessage('error_warning', L('error_warning'));
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('sale/order');

		foreach ($this->request->post['selected'] as $store_id) {
			if (!$store_id) {
				$this->setMessage('error_warning', L('error_default'));
				return false;
			}
			$store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);
			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}
		}
		return true;
	}

	public function template() {
		if (file_exists(DIR_CATALOG . 'view/theme/' . basename($this->request->get['template']) . '/preview.png')) {
			$image = HTTPS_CATALOG . 'catalog/view/theme/' . basename($this->request->get['template']) . '/preview.png';
		} else {
			$image = HTTPS_IMAGE . 'no_image.jpg';
		}
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}
}
?>