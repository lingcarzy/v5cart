<?php

function cache_all() {
	cache_setting();
	cache_system();
}

function cache_system() {
	global $db;
	
	$data = array();
	//currency
	$data['cache_currency'] = $db->queryArray("SELECT currency_id,title,code,symbol_left,symbol_right, decimal_place, value FROM @@currency WHERE status=1 ORDER BY title", 'code');
	
	//extension
	$data['cache_extension_module'] = $db->queryArray("SELECT code FROM @@extension WHERE `type` = 'module'");
	
	$data['cache_extension_shipping'] = $db->queryArray("SELECT code FROM @@extension WHERE `type` = 'shipping'");
	
	$data['cache_extension_payment'] = $db->queryArray("SELECT code FROM @@extension WHERE `type` = 'payment'");
	
	$data['cache_extension_total'] = $db->queryArray("SELECT code FROM @@extension WHERE `type` = 'total'");
	
	$languages = $db->queryArray("SELECT language_id FROM @@language WHERE status=1");
	
	foreach ($languages as $id) {
		
		//length class
		$data['cache_length_class'] = $db->queryArray("SELECT lc.length_class_id,title,unit,value FROM @@length_class lc LEFT JOIN @@length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = $id", 'length_class_id');
		
		//weight class
		$data['cache_weight_class'] = $db->queryArray("SELECT wc.weight_class_id,title,unit,value FROM @@weight_class wc LEFT JOIN @@weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = $id", 'weight_class_id');
		
		//order status
		$data['cache_order_status'] = $db->queryArray("SELECT order_status_id, name FROM @@order_status WHERE language_id = $id ORDER BY name", 'order_status_id', 'name');
		
		//stock status
		$data['cache_stock_status'] = $db->queryArray("SELECT stock_status_id, name FROM @@stock_status WHERE language_id = $id", 'stock_status_id', 'name');
		
		cache_write("system-$id.php", $data);
	}
	
}

function cache_setting() {
	global $db;
	$store_ids = $db->queryArray("SELECT store_id FROM @@store");
	$store_ids[] = 0;
	foreach ($store_ids as $store_id) {
		$query = $db->query("SELECT * FROM @@setting WHERE store_id = $store_id");
		$data = array();
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}
		
		//global discount setting
		if ($data['config_use_global_discount']) {
			$global_discount_rates = trim($data['config_global_discount_rate']);
			$global_discount_rates = explode("\n", $global_discount_rates);
			$rates = array();
			foreach($global_discount_rates as $global_discount_rate) {
				$t = explode('/', $global_discount_rate);
				$group_id = $t[0];
				$product_rates = explode(',',trim($t[1]));
				$rates[$group_id] = array();
				foreach($product_rates as $product_rate) {
					$t = explode(':', $product_rate);
					$rates[$group_id][$t[0]] = $t[1];
				}
			}
			$data['config_global_discount_rates'] = $rates;
		}
		
		$data['cache_language'] = $db->queryArray("SELECT language_id, name, code, locale, image, directory, filename FROM @@language WHERE status=1 ORDER BY sort_order, name", 'code');
		
		$data['cache_layout_route'] = $db->queryArray("SELECT layout_id, route FROM @@layout_route WHERE store_id=$store_id", 'layout_id', 'route');
		
		$data['cache_layout_product'] = $db->queryArray("SELECT pl.product_id, pl.layout_id FROM @@product_to_layout pl, @@product_to_store ps, @@product p WHERE pl.product_id=ps.product_id AND pl.store_id=ps.store_id AND p.product_id = ps.product_id AND pl.store_id = $store_id ORDER BY viewed DESC LIMIT 30", 'product_id', 'layout_id');
		
		$data['cache_layout_page'] = $db->queryArray("SELECT page_id, layout_id FROM @@page_to_layout WHERE store_id = $store_id LIMIT 30", 'page_id', 'layout_id');
		
		$data['cache_geo_zone'] = array();
		$query = $db->query("SELECT geo_zone_id,country_id,zone_id FROM @@zone_to_geo_zone");
		foreach ($query->rows as $row) {
			$data['cache_geo_zone'][$row['country_id']][$row['zone_id']][] = (int)$row['geo_zone_id'];
		}
		
		$tax_groups = $db->queryArray("SELECT tax_rate_id,GROUP_CONCAT(customer_group_id) AS `groups` FROM `@@tax_rate_to_customer_group` GROUP BY tax_rate_id", 'tax_rate_id', 'groups');
		
		$tax_rate_query = $db->query("SELECT tr1.tax_class_id,tr1.based,tr2.tax_rate_id,tr2.rate,tr2.type, tr2.geo_zone_id FROM @@tax_rule tr1 LEFT JOIN @@tax_rate tr2 ON ( tr1.tax_rate_id =tr2.tax_rate_id ) ORDER BY tax_class_id, based, tr1.priority ASC");
		$tax_rate = array();
		foreach($tax_rate_query->rows as $row) {
			if (isset($tax_groups[$row['tax_rate_id']])) {
				$row['cg'] = $tax_groups[$row['tax_rate_id']];
			}
			else $row['cg'] = 0;
			$tax_rate[$row['tax_class_id']][$row['based']][$row['tax_rate_id']] = array(
				'tax_rate_id' => (int)$row['tax_rate_id'],
				'rate' => (float)$row['rate'],
				'type' => $row['type'],
				'gzid' => (int)$row['geo_zone_id'],
				'cg' => ','.$row['cg'].','
			);
		}
		
		$data['cache_tax_rate'] = $tax_rate;
		
		cache_write('store-'. $store_id .'.php', $data);
	}
}