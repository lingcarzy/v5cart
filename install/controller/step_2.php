<?php
class ControllerStep2 extends Controller {

	public function index() {
		if ($this->request->isPost() && ($this->validate())) {
			$this->redirect(HTTP_SERVER . 'index.php?route=step_3');
		}
		
		$this->data['action'] = HTTP_SERVER . 'index.php?route=step_2';

		$this->data['config_catalog'] = DIR_V5CART . 'config.php';
		$this->data['config_admin'] = DIR_V5CART . 'admin/config.php';
		
		$this->data['cache'] = DIR_SYSTEM . 'cache';
		$this->data['logs'] = DIR_SYSTEM . 'logs';
		$this->data['image'] = DIR_V5CART . 'image';
		$this->data['image_cache'] = DIR_V5CART . 'image/cache';
		$this->data['image_data'] = DIR_V5CART . 'image/data';
		$this->data['download'] = DIR_V5CART . 'download';	

		$this->children = array(
			'header',
			'footer'
		);
		
		$this->display('step_2.tpl');
	}
	
	private function validate() {
		if (phpversion() < '5.0') {
			$this->setMessage('error_warning', 'Warning: You need to use PHP5 or above for V5Cart to work!');
			return false;
		}

		if (!ini_get('file_uploads')) {
			$this->setMessage('error_warning', 'Warning: file_uploads needs to be enabled!');
			return false;
		}
	
		if (ini_get('session.auto_start')) {
			$this->setMessage('error_warning', 'Warning: V5Cart will not work with session.auto_start enabled!');
			return false;
		}
		
		if (!extension_loaded('mysql')) {
			$this->setMessage('error_warning', 'Warning: MySQL extension needs to be loaded for V5Cart to work!');
			return false;
		}
				
		if (!extension_loaded('gd')) {
			$this->setMessage('error_warning', 'Warning: GD extension needs to be loaded for V5Cart to work!');
			return false;
		}

		if (!extension_loaded('curl')) {
			$this->setMessage('error_warning', 'Warning: CURL extension needs to be loaded for V5Cart to work!');
			return false;
		}

		if (!function_exists('mcrypt_encrypt')) {
			$this->setMessage('error_warning', 'Warning: mCrypt extension needs to be loaded for V5Cart to work!');
			return false;
		}
				
		if (!extension_loaded('zlib')) {
			$this->setMessage('error_warning', 'Warning: ZLIB extension needs to be loaded for V5Cart to work!');
			return false;
		}
	
		if (!is_writable(DIR_V5CART . 'config.php')) {
			$this->setMessage('error_warning', 'Warning: config.php needs to be writable for V5Cart to be installed!');
			return false;
		}
				
		if (!is_writable(DIR_V5CART . 'admin/config.php')) {
			$this->setMessage('error_warning', 'Warning: admin/config.php needs to be writable for V5Cart to be installed!');
			return false;
		}

		if (!is_writable(DIR_SYSTEM . 'cache')) {
			$this->setMessage('error_warning', 'Warning: Cache directory needs to be writable for V5Cart to work!');
			return false;
		}
		
		if (!is_writable(DIR_SYSTEM . 'logs')) {
			$this->setMessage('error_warning', 'Warning: Logs directory needs to be writable for V5Cart to work!');
			return false;
		}
		
		if (!is_writable(DIR_V5CART . 'image')) {
			$this->setMessage('error_warning', 'Warning: Image directory needs to be writable for V5Cart to work!');
			return false;
		}

		if (!is_writable(DIR_V5CART . 'image/cache')) {
			$this->setMessage('error_warning', 'Warning: Image cache directory needs to be writable for V5Cart to work!');
			return false;
		}
		
		if (!is_writable(DIR_V5CART . 'image/data')) {
			$this->setMessage('error_warning', 'Warning: Image data directory needs to be writable for V5Cart to work!');
			return false;
		}
		
		if (!is_writable(DIR_V5CART . 'download')) {
			$this->setMessage('error_warning', 'Warning: Download directory needs to be writable for V5Cart to work!');
			return false;
		}
		
    	return true;
	}
}
?>