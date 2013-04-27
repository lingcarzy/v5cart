<?php
// Version
define('VERSION', '1.0.0');
define('CATALOG', 1);
// Configuration
require_once('config.php');

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Stores
$setting = cache_read('store-0.php');
if (!$setting) {
	include(DIR_SYSTEM . 'helper/cache.php');
	cache_all();
	$setting = cache_read('store-0.php');
}
$store_id = 0;
if (isset($stores)) {	
	foreach ($stores as $_id => $_urls) {
		if (isset($_SERVER['HTTP_HOST']) && $_url[0] == $_SERVER['HTTP_HOST']) {
			$store_id = $_id;
			break;
		}
	}
	if ($store_id > 0) {
		define('HTTP_SERVER', $stores[$store_id][0]);
		define('HTTP_IMAGE', HTTP_SERVER . 'image/');
		define('HTTPS_SERVER', $stores[$store_id][1]);
		define('HTTPS_IMAGE', HTTPS_SERVER . 'image/');
		$store_setting = cache_read('store-' . $store_id. '.php');
		$setting = array_merge($setting, $store_setting);
	}
	unset($stores);
}

// Config
$config = new Config($setting);
$registry->set('config', $config);
$config->set('config_store_id', $store_id);

$config->set('config_url', HTTP_SERVER);
$config->set('config_ssl', HTTPS_SERVER);

// Url
$url = new Url($config->get('config_url'), $config->get('config_use_ssl') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Cache
$cache = new Cache();
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);


$languages = $config->get('cache_language');

// Language Detection
$detect = '';
if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) 
	&& $request->server['HTTP_ACCEPT_LANGUAGE']) {
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
	
	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			$locale = explode(',', $value['locale']);
			if (in_array($browser_language, $locale)) {
				$detect = $key;
			}
		}
	}
}

if (isset($session->data['language']) 
	&& array_key_exists($session->data['language'], $languages)) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) 
	&& array_key_exists($request->cookie['language'], $languages)) {
	$code = $request->cookie['language'];
} elseif ($detect) {
	$code = $detect;
} else {
	$code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);

//extend
require_once(DIR_SYSTEM . 'extend/catalog.php');

//SEO Url
if ($config->get('config_seo_url')) {
	include(DIR_SYSTEM . 'helper/seo_url.php');
	$url->setRewrite();
}

// Document
$registry->set('document', new Document());

// Customer
$registry->set('customer', new Customer($registry));

// Affiliate
$registry->set('affiliate', new Affiliate($registry));

if (isset($request->get['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
}

// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));

//  Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));

// Front Controller
$controller = new Front($registry);

// Maintenance Mode
//$controller->addPreAction(new Action('common/maintenance'));

// Router
$action = new Action($router);

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>