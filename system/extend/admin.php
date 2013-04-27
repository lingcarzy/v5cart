<?php
$router = $request->get('route', 'common/admincp');
$path = '';
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
unset($parts);
define('ROUTE', $path);
define('METHOD', $method);

$system_data = cache_read('system-'. $config->get('config_language_id') .'.php');
foreach ($system_data as $key => $val) {
	$config->set($key, $val);
}
unset($system_data);

$CATEGORIES = cache_read("category-0-". $config->get('config_language_id') .".php");

//admin url
function UA($route, $args = '', $connection = 'SSL') {
	global $url, $session;
	return $url->link($route, $args . "&token=" . $session->data['token'], $connection);
}

//catalog url
function U($route, $args = '', $connection = 'NONSSL') {
	global $catalog_url;
	return $catalog_url->link($route, $args, $connection);
}


function bread_crumbs() {
	$crumbs = '<a href="'. UA('common/home') .'">'. L('text_home') .'</a>';
	$crumbs .= ' :: <a href="'. UA(ROUTE) .'">'. L('heading_title') .'</a>';
	return $crumbs;
}

function seo_url($str) {
	$url = preg_replace("/[^a-zA-Z0-9 ]/", " ",$str);		
	$url = preg_replace("/\s+/","-",$url);	
	$url = trim($url, "-");
	return strtolower($url);
}