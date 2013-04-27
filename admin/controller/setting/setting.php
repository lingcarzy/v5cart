<?php
class ControllerSettingSetting extends Controller {

	public function index() {
		$this->language->load('setting/setting');
		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('config', $this->request->post);

			if (C('config_currency_auto')) {
				M('localisation/currency');
				$this->model_localisation_currency->updateCurrencies();
			}
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('setting/store'));
		}

		$this->data['token'] = $this->session->data['token'];

		if ($this->request->isPost()) {
			$this->data['config_name'] = P('config_name');
			$this->data['config_owner'] = P('config_owner');
			$this->data['config_address'] = P('config_address');
			$this->data['config_email'] = P('config_email');
			$this->data['config_telephone'] = P('config_telephone');
			$this->data['config_fax'] = P('config_fax');

			$this->data['config_title'] = P('config_title');
			$this->data['config_meta_description'] = P('config_meta_description');
			$this->data['config_layout_id'] = P('config_layout_id');
			$this->data['config_template'] = P('config_template');
			$this->data['config_country_id'] = P('config_country_id');
			$this->data['config_zone_id'] = P('config_zone_id');

			$this->data['config_language'] = P('config_language');
			$this->data['config_admin_language'] = P('config_admin_language');
			$this->data['config_currency'] = P('config_currency');
			$this->data['config_currency_auto'] = P('config_currency_auto');
			$this->data['config_use_global_discount'] = P('config_use_global_discount');

			$this->data['config_customer_group_display'] = P('config_customer_group_display');

			$this->data['config_use_global_discount'] = P('config_use_global_discount');
			$this->data['config_global_discount_rate'] = P('config_global_discount_rate');

			$this->data['config_length_class_id'] = P('config_length_class_id');
			$this->data['config_weight_class_id'] = P('config_weight_class_id');

			$this->data['config_catalog_limit'] = P('config_catalog_limit');
			$this->data['config_admin_limit'] = P('config_admin_limit');
			$this->data['config_product_count'] = P('config_product_count');
			$this->data['config_review_status'] = P('config_review_status');
			$this->data['config_download'] = P('config_download');

			$this->data['config_file_extension_allowed'] = P('config_file_extension_allowed');
			$this->data['config_file_mime_allowed'] = P('config_file_mime_allowed');

			$this->data['config_voucher_min'] = P('config_voucher_min');
			$this->data['config_voucher_max'] = P('config_voucher_max');
			$this->data['config_tax'] = P('config_tax');
			$this->data['config_vat'] = P('config_vat');
			$this->data['config_tax_default'] = P('config_tax_default');
			$this->data['config_tax_customer'] = P('config_tax_customer');
			$this->data['config_customer_online'] = P('config_customer_online');
			$this->data['config_register_address'] = P('config_register_address');
			$this->data['config_customer_group_id'] = P('config_customer_group_id');

			$this->data['config_customer_price'] = P('config_customer_price');
			$this->data['config_account_id'] = P('config_account_id');

			$this->data['config_cart_weight'] = P('config_cart_weight');
			$this->data['config_guest_checkout'] = P('config_guest_checkout');
			$this->data['config_checkout_id'] = P('config_checkout_id');
			$this->data['config_order_edit'] = P('config_order_edit');
			$this->data['config_invoice_prefix'] = P('config_invoice_prefix');
			$this->data['config_order_status_id'] = P('config_order_status_id');
			$this->data['config_complete_status_id'] = P('config_complete_status_id');

			$this->data['config_stock_display'] = P('config_stock_display');
			$this->data['config_stock_warning'] = P('config_stock_warning');
			$this->data['config_stock_checkout'] = P('config_stock_checkout');
			$this->data['config_stock_status_id'] = P('config_stock_status_id');
			$this->data['config_affiliate_id'] = P('config_affiliate_id');
			$this->data['config_commission'] = P('config_commission');

			$this->data['config_return_id'] = P('config_return_id');
			$this->data['config_return_status_id'] = P('config_return_status_id');
			$this->data['config_logo'] = P('config_logo');
			$this->data['config_icon'] = P('config_icon');
			$this->data['config_image_category_width'] = P('config_image_category_width');
			$this->data['config_image_category_height'] = P('config_image_category_height');
			$this->data['config_image_thumb_width'] = P('config_image_thumb_width');
			$this->data['config_image_thumb_height'] = P('config_image_thumb_height');
			$this->data['config_image_popup_width'] = P('config_image_popup_width');
			$this->data['config_image_popup_height'] = P('config_image_popup_height');
			$this->data['config_image_product_width'] = P('config_image_product_width');
			$this->data['config_image_product_height'] = P('config_image_product_height');
			$this->data['config_image_additional_width'] = P('config_image_additional_width');
			$this->data['config_image_additional_height'] = P('config_image_additional_height');
			$this->data['config_image_related_width'] = P('config_image_related_width');
			$this->data['config_image_related_height'] = P('config_image_related_height');
			$this->data['config_image_compare_width'] = P('config_image_compare_width');
			$this->data['config_image_compare_height'] = P('config_image_compare_height');
			$this->data['config_image_wishlist_width'] = P('config_image_wishlist_width');
			$this->data['config_image_wishlist_height'] = P('config_image_wishlist_height');
			$this->data['config_image_cart_width'] = P('config_image_cart_width');
			$this->data['config_image_cart_height'] = P('config_image_cart_height');
			$this->data['config_mail_protocol'] = P('config_mail_protocol');
			$this->data['config_mail_parameter'] = P('config_mail_parameter');
			$this->data['config_smtp_host'] = P('config_smtp_host');
			$this->data['config_smtp_username'] = P('config_smtp_username');
			$this->data['config_smtp_password'] = P('config_smtp_password');
			$this->data['config_smtp_port'] = P('config_smtp_port');
			$this->data['config_smtp_timeout'] = P('config_smtp_timeout');
			
			$this->data['config_alert_mail'] = P('config_alert_mail');
			$this->data['config_account_mail'] = P('config_account_mail');
			$this->data['config_alert_emails'] = P('config_alert_emails');
			$this->data['config_fraud_detection'] = P('config_fraud_detection');
			$this->data['config_fraud_key'] = P('config_fraud_key');
			$this->data['config_fraud_score'] = P('config_fraud_score');
			$this->data['config_fraud_status_id'] = P('config_fraud_status_id');
			$this->data['config_use_ssl'] = P('config_use_ssl');
			$this->data['config_seo_url'] = P('config_seo_url');
			$this->data['config_maintenance'] = P('config_maintenance');
			$this->data['config_password'] = P('config_password');
			$this->data['config_encryption'] = P('config_encryption');
			$this->data['config_compression'] = P('config_compression');
			$this->data['config_error_display'] = P('config_error_display');
			$this->data['config_error_log'] = P('config_error_log');
			$this->data['config_error_filename'] = P('config_error_filename');
			$this->data['config_google_analytics'] = P('config_google_analytics');			
		}
		else {
			$this->data['config_name'] = C('config_name');
			$this->data['config_owner'] = C('config_owner');
			$this->data['config_address'] = C('config_address');
			$this->data['config_email'] = C('config_email');
			$this->data['config_telephone'] = C('config_telephone');
			$this->data['config_fax'] = C('config_fax');

			$this->data['config_title'] = C('config_title');
			$this->data['config_meta_description'] = C('config_meta_description');
			$this->data['config_layout_id'] = C('config_layout_id');
			$this->data['config_template'] = C('config_template');
			$this->data['config_country_id'] = C('config_country_id');
			$this->data['config_zone_id'] = C('config_zone_id');

			$this->data['config_language'] = C('config_language');
			$this->data['config_admin_language'] = C('config_admin_language');
			$this->data['config_currency'] = C('config_currency');
			$this->data['config_currency_auto'] = C('config_currency_auto');
			$this->data['config_use_global_discount'] = C('config_use_global_discount');

			if (C('config_customer_group_display')) {
				$this->data['config_customer_group_display'] = C('config_customer_group_display');
			} else {
				$this->data['config_customer_group_display'] = array();
			}

			$this->data['config_use_global_discount'] = C('config_use_global_discount');
			$this->data['config_global_discount_rate'] = C('config_global_discount_rate');

			$this->data['config_length_class_id'] = C('config_length_class_id');
			$this->data['config_weight_class_id'] = C('config_weight_class_id');

			$this->data['config_catalog_limit'] = C('config_catalog_limit');
			$this->data['config_admin_limit'] = C('config_admin_limit');
			$this->data['config_product_count'] = C('config_product_count');
			$this->data['config_review_status'] = C('config_review_status');
			$this->data['config_download'] = C('config_download');

			$this->data['config_file_extension_allowed'] = C('config_file_extension_allowed');
			$this->data['config_file_mime_allowed'] = C('config_file_mime_allowed');

			$this->data['config_voucher_min'] = C('config_voucher_min');
			$this->data['config_voucher_max'] = C('config_voucher_max');
			$this->data['config_tax'] = C('config_tax');
			$this->data['config_vat'] = C('config_vat');
			$this->data['config_tax_default'] = C('config_tax_default');
			$this->data['config_tax_customer'] = C('config_tax_customer');
			$this->data['config_customer_online'] = C('config_customer_online');
			$this->data['config_register_address'] = C('config_register_address');
			$this->data['config_customer_group_id'] = C('config_customer_group_id');

			$this->data['config_customer_price'] = C('config_customer_price');
			$this->data['config_account_id'] = C('config_account_id');

			$this->data['config_cart_weight'] = C('config_cart_weight');
			$this->data['config_guest_checkout'] = C('config_guest_checkout');
			$this->data['config_checkout_id'] = C('config_checkout_id');

			if (C('config_order_edit')) {
				$this->data['config_order_edit'] = C('config_order_edit');
			} else {
				$this->data['config_order_edit'] = 7;
			}

			if (C('config_invoice_prefix')) {
				$this->data['config_invoice_prefix'] = C('config_invoice_prefix');
			} else {
				$this->data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
			}
			$this->data['config_order_status_id'] = C('config_order_status_id');
			$this->data['config_complete_status_id'] = C('config_complete_status_id');

			$this->data['config_stock_display'] = C('config_stock_display');
			$this->data['config_stock_warning'] = C('config_stock_warning');
			$this->data['config_stock_checkout'] = C('config_stock_checkout');
			$this->data['config_stock_status_id'] = C('config_stock_status_id');
			$this->data['config_affiliate_id'] = C('config_affiliate_id');
			if ($this->config->has('config_commission')) {
				$this->data['config_commission'] = C('config_commission');
			} else {
				$this->data['config_commission'] = '5.00';
			}

			$this->data['config_return_id'] = C('config_return_id');
			$this->data['config_return_status_id'] = C('config_return_status_id');
			$this->data['config_logo'] = C('config_logo');
			$this->data['config_icon'] = C('config_icon');
			$this->data['config_image_category_width'] = C('config_image_category_width');
			$this->data['config_image_category_height'] = C('config_image_category_height');
			$this->data['config_image_thumb_width'] = C('config_image_thumb_width');
			$this->data['config_image_thumb_height'] = C('config_image_thumb_height');
			$this->data['config_image_popup_width'] = C('config_image_popup_width');
			$this->data['config_image_popup_height'] = C('config_image_popup_height');

			$this->data['config_image_product_width'] = C('config_image_product_width');
			$this->data['config_image_product_height'] = C('config_image_product_height');
			$this->data['config_image_additional_width'] = C('config_image_additional_width');
			$this->data['config_image_additional_height'] = C('config_image_additional_height');
			$this->data['config_image_related_width'] = C('config_image_related_width');
			$this->data['config_image_related_height'] = C('config_image_related_height');
			$this->data['config_image_compare_width'] = C('config_image_compare_width');
			$this->data['config_image_compare_height'] = C('config_image_compare_height');
			$this->data['config_image_wishlist_width'] = C('config_image_wishlist_width');
			$this->data['config_image_wishlist_height'] = C('config_image_wishlist_height');
			$this->data['config_image_cart_width'] = C('config_image_cart_width');
			$this->data['config_image_cart_height'] = C('config_image_cart_height');

			$this->data['config_mail_protocol'] = C('config_mail_protocol');
			$this->data['config_mail_parameter'] = C('config_mail_parameter');
			$this->data['config_smtp_host'] = C('config_smtp_host');
			$this->data['config_smtp_username'] = C('config_smtp_username');
			$this->data['config_smtp_password'] = C('config_smtp_password');
			if (C('config_smtp_port')) {
				$this->data['config_smtp_port'] = C('config_smtp_port');
			} else {
				$this->data['config_smtp_port'] = 25;
			}

			if (C('config_smtp_timeout')) {
				$this->data['config_smtp_timeout'] = C('config_smtp_timeout');
			} else {
				$this->data['config_smtp_timeout'] = 5;
			}

			$this->data['config_alert_mail'] = C('config_alert_mail');
			$this->data['config_account_mail'] = C('config_account_mail');
			$this->data['config_alert_emails'] = C('config_alert_emails');
			$this->data['config_fraud_detection'] = C('config_fraud_detection');
			$this->data['config_fraud_key'] = C('config_fraud_key');
			$this->data['config_fraud_score'] = C('config_fraud_score');
			$this->data['config_fraud_status_id'] = C('config_fraud_status_id');
			$this->data['config_use_ssl'] = C('config_use_ssl');
			$this->data['config_seo_url'] = C('config_seo_url');
			$this->data['config_maintenance'] = C('config_maintenance');
			$this->data['config_password'] = C('config_password');
			$this->data['config_encryption'] = C('config_encryption');
			$this->data['config_compression'] = C('config_compression');
			$this->data['config_error_display'] = C('config_error_display');
			$this->data['config_error_log'] = C('config_error_log');
			$this->data['config_error_filename'] = C('config_error_filename');
			$this->data['config_google_analytics'] = C('config_google_analytics');
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

		$this->data['length_classes'] = C('cache_length_class');
		$this->data['weight_classes'] = C('cache_weight_class');

		M('catalog/page');
		$this->data['pages'] = $this->model_catalog_page->getPages();

		$this->data['order_statuses'] = C('cache_order_status');


		M('localisation/stock_status');
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		M('localisation/return_status');
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		M('tool/image');

		if (C('config_logo') && file_exists(DIR_IMAGE . C('config_logo')) && is_file(DIR_IMAGE . C('config_logo'))) {
			$this->data['logo'] = $this->model_tool_image->resize(C('config_logo'), 100, 100);
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (C('config_icon') && file_exists(DIR_IMAGE . C('config_icon')) && is_file(DIR_IMAGE . C('config_icon'))) {
			$this->data['icon'] = $this->model_tool_image->resize(C('config_icon'), 100, 100);
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('setting/setting.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$this->load->library('form_validation', true);
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
		$this->form_validation->set_rules('config_admin_limit', '', 'required', L('error_limit'));
		$this->form_validation->set_rules('config_voucher_min', '', 'required', L('error_voucher_min'));
		$this->form_validation->set_rules('config_voucher_max', '', 'required', L('error_voucher_max'));
		$this->form_validation->set_rules('config_error_filename', '', 'required', L('error_error_filename'));

		if ($this->form_validation->run()) return true;
		else {
			$this->setMessage('error_warning', L('error_warning'));
			return false;
		}

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