<?php

//router
$path = '';
$router = $request->get('route', 'common/home');
$parts = explode('/', str_replace(array('../', '..\\', '..'), '', $router));
foreach ($parts as $part) { 
	$path .= $part;	
	if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
		$path .= '/';
		array_shift($parts);
		continue;
	}	
	if (is_file(DIR_APPLICATION . 'controller/' . $path . '.php')) {
		array_shift($parts);
		break;
	}
}
$method = array_shift($parts);
if (!$method) $method = 'index';
define('ROUTE', $path);
define('METHOD', $method);
unset($parts);
//read system cache
$system_data = cache_read('system-'. $config->get('config_language_id') .'.php');
foreach ($system_data as $key => $val) {
	$config->set($key, $val);
}
unset($system_data);

//category
$CATEGORY = NULL;
$CATEGORIES = cache_read("category-{$store_id}-". $config->get('config_language_id') .".php");
if (isset($request->get['cate_id'])) {
	$cate_id = (int)$request->get['cate_id'];
	if (isset($CATEGORIES[$cate_id])) {
		$CATEGORY = $CATEGORIES[$cate_id];
	}
}
//layout id
$layout_id = 0;
if ($router == 'product/category'  && $CATEGORY) {
	if (isset($CATEGORY['layout'])) {
		$layout_id = $CATEGORY['layout'];
	}
}

if (!$layout_id) {
	if ($router == 'product/product'  && isset($request->get['product_id'])) {
		$product_id = (int) $request->get['product_id'];
		$layouts = $config->get('cache_layout_product');
		if (isset($layouts[$product_id])) {
			$layout_id = $layouts[$product_id];
		}
		elseif (count($layouts) == 30) {
			$layout_id = $db->queryOne("SELECT layout_id FROM @@product_to_layout WHERE product_id = $product_id AND store_id =  $store_id");
		}
	}
}

if (!$layout_id) {
	if ($router == 'page/index'  && isset($request->get['page_id'])) {
		$page_id = (int) $request->get['page_id'];
		$layouts = $config->get('cache_layout_page');
		if (isset($layouts[$page_id])) {
			$layout_id = $layouts[$page_id];
		}
		elseif (count($layouts) == 30) {
			$layout_id = $db->queryOne("SELECT layout_id FROM @@page_to_layout WHERE page_id = $page_id AND store_id =  $store_id");
		}
	}
}

if (!$layout_id) {
	$layouts = $config->get('cache_layout_route');
	foreach ($layouts as $_layout_id => $_layout_route) {
		if (strpos($router, $_layout_route) === 0) {
			$layout_id = $_layout_id;
			break;
		}
	}
}

if (!$layout_id) {
	$layout_id = $config->get('config_layout_id');
}

define('LAYOUT', $layout_id);

//front url
function U($route, $args = '', $connection = 'NONSSL') {
	return $GLOBALS['url']->link($route, $args, $connection);
}