<?php
// Version
define('VERSION', '1.0.0');
define('ADMIN', 1);
// Configuration
require_once('config.php');

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);



// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$setting = cache_read('store-0.php');
if (!$setting) {
	include(DIR_SYSTEM . 'helper/cache.php');
	cache_all();
	$setting = cache_read('store-0.php');
}
// Config
$config = new Config($setting);
$registry->set('config', $config);

// Url
$url = new Url(HTTP_SERVER, $config->get('config_use_ssl') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// Catalog Url
$catalog_url = new Url(HTTP_CATALOG, $config->get('config_use_ssl') ? HTTPS_CATALOG : HTTP_CATALOG);
if ($config->get('config_seo_url')) {
	include(DIR_SYSTEM . 'helper/seo_url.php');
	$catalog_url->setRewrite();
}

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
$registry->set('response', $response);

// Cache
$cache = new Cache();
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);

// Language
$languages = $config->get('cache_language');
$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);
$config->set('config_language', $languages[$config->get('config_admin_language')]['code']);

// Language
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);
$registry->set('language', $language);

require_once(DIR_SYSTEM . 'extend/admin.php');

// Document
$registry->set('document', new Document());

// Currency
$registry->set('currency', new Currency($registry));

// Weight
//$registry->set('weight', new Weight($registry));

// Length
//$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

// Front Controller
$controller = new Front($registry);

// Login
$controller->addPreAction(new Action('common/home/login'));

// Permission
$controller->addPreAction(new Action('common/home/permission'));

// Router
$action = new Action($router);

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>