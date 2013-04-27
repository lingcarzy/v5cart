<?php
class ControllerCommonAdmincp extends Controller {

	public function index() {
		$this->language->load('common/admincp');
		
		$menu = array();
		
		//catalog menu
		$menu[] = array(
			'id' => 'catalog',
			'name' => L('text_catalog'),
			'url' => null,
			'open' => true,
			'ChildItem' => array(
				0 => array('id' => 'category', 'name' => L('text_category'), 'url' => UA('catalog/category')),
				1 => array('id' => 'products', 'name' => L('text_product'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'product', 'name' => L('text_product'), 'url' => UA('catalog/product')),
						1 => array('id' => 'product_group', 'name' => L('text_product_group'), 'url' => UA('catalog/product_group')),
						2 => array('id' => 'product_tpl', 'name' => L('text_product_template'), 'url' => UA('catalog/product_tpl')),
						3 => array('id' => 'specials', 'name' => L('text_special'), 'url' => UA('catalog/specials')),
					)
				),
				2 => array('id' => 'attributes', 'name' => L('text_attribute'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'attribute', 'name' => L('text_attribute'), 'url' => UA('catalog/attribute')),
						1 => array('id' => 'attribute_group', 'name' => L('text_attribute_group'), 'url' => UA('catalog/attribute_group')),
					)
				),
				3 => array('id' => 'option', 'name' => L('text_option'), 'url' => UA('catalog/option')),
				4 => array('id' => 'manufacturer', 'name' => L('text_manufacturer'), 'url' => UA('catalog/manufacturer')),
				5 => array('id' => 'supplier', 'name' => L('text_supplier'), 'url' => UA('catalog/supplier')),
				6 => array('id' => 'download', 'name' => L('text_download'), 'url' => UA('catalog/download')),
				7 => array('id' => 'review', 'name' => L('text_review'), 'url' => UA('catalog/review')),
				8 => array('id' => 'page', 'name' => L('text_page'), 'url' => UA('catalog/page')),
			)
		);
		
		//sale menu
		$menu[] = array(
			'id' => 'sale',
			'name' => L('text_sale'),
			'url' => null,
			'open' => true,
			'ChildItem' => array(
				0 => array('id' => 'order', 'name' => L('text_order'), 'url' => UA('sale/order')),
				1 => array('id' => 'order_basket', 'name' => L('text_order_basket'), 'url' => UA('sale/order_basket')),
				2 => array('id' => 'return', 'name' => L('text_return'), 'url' => UA('sale/return')),
				3 => array('id' => 'customers', 'name' => L('text_customer'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'customer', 'name' => L('text_customer'), 'url' => UA('sale/customer')),
						1 => array('id' => 'customer_group', 'name' => L('text_customer_group'), 'url' => UA('sale/customer_group')),
						2 => array('id' => 'customer_blacklist', 'name' => L('text_customer_blacklist'), 'url' => UA('sale/customer_blacklist')),
					)
				),
				4 => array('id' => 'affiliate', 'name' => L('text_affiliate'), 'url' => UA('sale/affiliate')),
				5 => array('id' => 'coupon', 'name' => L('text_coupon'), 'url' => UA('sale/coupon')),
				6 => array('id' => 'vouchers', 'name' => L('text_voucher'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'voucher', 'name' => L('text_voucher'), 'url' => UA('sale/voucher')),
						1 => array('id' => 'voucher_theme', 'name' => L('text_voucher_theme'), 'url' => UA('sale/voucher_theme')),
					)
				),
				7 => array('id' => 'contact', 'name' => L('text_contact'), 'url' => UA('sale/contact'))
			)
			
		);
		//extension menu
		$menu[] = array(
			'id' => 'extension',
			'name' => L('text_extension'),
			'url' => null,
			'open' => false,
			'ChildItem' => array(
				0 => array('id' => 'module', 'name' => L('text_module'), 'url' => UA('extension/module')),
				1 => array('id' => 'shipping', 'name' => L('text_shipping'), 'url' => UA('extension/shipping')),
				2 => array('id' => 'payment', 'name' => L('text_payment'), 'url' => UA('extension/payment')),
				3 => array('id' => 'total', 'name' => L('text_total'), 'url' => UA('extension/total')),
				4 => array('id' => 'feed', 'name' => L('text_feed'), 'url' => UA('extension/feed')),
				5 => array('id' => 'livechat', 'name' => L('text_livechat'), 'url' => UA('tool/livechat'))
			)
		);
		
		//reports
		$menu[] = array(
			'id' => 'reports',
			'name' => L('text_reports'),
			'url' => null,
			'open' => false,
			'ChildItem' => array(
				0 => array('id' => 'report-sale', 'name' => L('text_sale'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'sale-order', 'name' => L('text_report_sale_order'), 'url' => UA('report/sale_order')),
						1 => array('id' => 'sale-tax', 'name' => L('text_report_sale_tax'), 'url' => UA('report/sale_tax')),
						2 => array('id' => 'sale-shipping', 'name' => L('text_report_sale_shipping'), 'url' => UA('report/sale_shipping')),
						3 => array('id' => 'sale-return', 'name' => L('text_report_sale_return'), 'url' => UA('report/sale_return')),
						4 => array('id' => 'sale-coupon', 'name' => L('text_report_sale_coupon'), 'url' => UA('report/sale_coupon')),
					)
				),
				1 => array('id' => 'report-product', 'name' => L('text_product'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'product-viewed', 'name' => L('text_report_product_viewed'), 'url' => UA('report/product_viewed')),
						1 => array('id' => 'product-purchased', 'name' => L('text_report_product_purchased'), 'url' => UA('report/product_purchased')),
					)
				),
				2 => array('id' => 'report-customer', 'name' => L('text_customer'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'customer-online', 'name' => L('text_report_customer_online'), 'url' => UA('report/customer_online')),
						1 => array('id' => 'customer-order', 'name' => L('text_report_customer_order'), 'url' => UA('report/customer_order')),
						2 => array('id' => 'customer-reward', 'name' => L('text_report_customer_reward'), 'url' => UA('report/customer_reward')),
						3 => array('id' => 'customer-credit', 'name' => L('text_report_customer_credit'), 'url' => UA('report/customer_credit')),
					)
				),
				3 => array('id' => 'report-affiliate', 'name' => L('text_affiliate'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'affiliate-commission', 'name' => L('text_report_affiliate_commission'), 'url' => UA('report/affiliate_commission')),
					)
				),
			)
		);
		
		//system
		$menu[] = array(
			'id' => 'system',
			'name' => L('text_system'),
			'url' => null,
			'open' => false,
			'ChildItem' => array(
				0 => array('id' => 'setting', 'name' => L('text_setting'), 'url' => UA('setting/store')),
				1 => array('id' => 'cache', 'name' => L('text_cache'), 'url' => UA('setting/cache')),
				2 => array('id' => 'desing', 'name' => L('text_design'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'layout', 'name' => L('text_layout'), 'url' => UA('design/layout')),
						1 => array('id' => 'banner', 'name' => L('text_banner'), 'url' => UA('design/banner')),
					)
				),
				3 => array('id' => 'users', 'name' => L('text_users'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'user', 'name' => L('text_user'), 'url' => UA('user/user')),
						1 => array('id' => 'user-group', 'name' => L('text_user_group'), 'url' => UA('user/user_permission')),
					)
				),
				4 => array('id' => 'localisation', 'name' => L('text_localisation'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'language', 'name' => L('text_language'), 'url' => UA('localisation/language')),
						1 => array('id' => 'currency', 'name' => L('text_currency'), 'url' => UA('localisation/currency')),
						2 => array('id' => 'stock-status', 'name' => L('text_stock_status'), 'url' => UA('localisation/stock_status')),
						3 => array('id' => 'order-status', 'name' => L('text_order_status'), 'url' => UA('localisation/order_status')),
						4 => array('id' => 'return', 'name' => L('text_return'), 'url' => null, 'open' => false,
							'ChildItem' => array(
								0 => array('id' => 'return-status', 'name' => L('text_return_status'), 'url' => UA('localisation/return_status')),
								1 => array('id' => 'return-action', 'name' => L('text_return_action'), 'url' => UA('localisation/return_action')),
								2 => array('id' => 'return-reason', 'name' => L('text_return_reason'), 'url' => UA('localisation/return_reason')),
							)
						),
						5 => array('id' => 'carrier', 'name' => L('text_carrier'), 'url' => UA('localisation/carrier')),
						6 => array('id' => 'country', 'name' => L('text_country'), 'url' => UA('localisation/country')),
						7 => array('id' => 'zone', 'name' => L('text_zone'), 'url' => UA('localisation/zone')),
						8 => array('id' => 'geo-zone', 'name' => L('text_geo_zone'), 'url' => UA('localisation/geo_zone')),
						9 => array('id' => 'tax', 'name' => L('text_tax'), 'url' => null, 'open' => false,
							'ChildItem' => array(
								0 => array('id' => 'tax-class', 'name' => L('text_tax_class'), 'url' => UA('localisation/tax_class')),
								1 => array('id' => 'tax-rate', 'name' => L('text_tax_rate'), 'url' => UA('localisation/tax_rate')),
							)
						),
						10 => array('id' => 'length-class', 'name' => L('text_length_class'), 'url' => UA('localisation/length_class')),
						11 => array('id' => 'weight-class', 'name' => L('text_weight_class'), 'url' => UA('localisation/weight_class')),
					)
				),
				5 => array('id' => 'error-log', 'name' => L('text_error_log'), 'url' => UA('tool/error_log')),
				6 => array('id' => 'database', 'name' => L('text_database'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'tool-backup', 'name' => L('text_backup'), 'url' => UA('tool/backup')),
						1 => array('id' => 'tool-export', 'name' => L('text_export'), 'url' => UA('tool/export')),
					)
				),
				7 => array('id' => 'seo-url', 'name' => L('text_seo_url'), 'url' => UA('setting/seo_url')),
				8 => array('id' => 'paypal-express', 'name' => L('text_paypal_express'), 'url' => null, 'open' => false,
					'ChildItem' => array(
						0 => array('id' => 'pp-session', 'name' => 'Sessions', 'url' => UA('sale/paypal_express')),
						1 => array('id' => 'pp-payment', 'name' => 'Payments', 'url' => UA('sale/paypal_express/payment')),
						2 => array('id' => 'pp-error', 'name' => 'Errors', 'url' => UA('sale/paypal_express/error')),
					)
				)
			)
		);
		
		//help
		$menu[] = array(
			'id' => 'help',
			'name' => L('text_help'),
			'url' => null,
		);
		
		
		$this->data['menu'] = json_encode($menu);
		$this->display('common/admincp.tpl');
	}
	
	public function header() {
		$this->language->load('common/admincp');
		
		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->data['logged'] = '';
			$this->data['home'] = $this->url->link('common/login', '', 'SSL');
		} else {
			$this->data['logged'] = sprintf(L('text_logged'), $this->user->getUserName());
			$this->data['home'] = UA('common/home');
		}
		$this->display('common/admincp_header.tpl');
	}
	public function tag() {		
		$this->display('common/admincp_tag.tpl');
	}
	
	public function left() {
		$this->display('common/admincp_left.tpl');
	}
	
	public function content() {
		$this->display('common/admincp_content.tpl');
	}
}

