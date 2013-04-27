<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
  	public function __construct() {
		$_GET = $this->clean($_GET);
		$_POST = $this->clean($_POST);
		$_REQUEST = $this->clean($_REQUEST);
		$_COOKIE = $this->clean($_COOKIE);
		$_FILES = $this->clean($_FILES);
		$_SERVER = $this->clean($_SERVER);
		
		$this->get = $_GET;
		$this->post = $_POST;
		$this->request = $_REQUEST;
		$this->cookie = $_COOKIE;
		$this->files = $_FILES;
		$this->server = $_SERVER;
	}
	
  	public function clean($data) {
    	if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);
				
	    		$data[$this->clean($key)] = $this->clean($value);
	  		}
		} else { 
	  		$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}
	
	//////////////////////////////////////
	public function isPost() {
		return ($this->server['REQUEST_METHOD'] == 'POST');
	}
	
	public function query($keys = array()) {
		$query = '';
		if (!empty($keys)) {
			if (is_string($keys)) {
				$keys = preg_replace('/\s+/', '', $keys);
				$keys = explode(',', $keys);
			}
			foreach($keys as $key) {
				if(isset($this->get[$key])) {
					$query .= '&' . $key . '=' . urlencode($this->get[$key]);
				}
			}
		} else {
			foreach($this->get as $key => $val) {
				if ($key != 'route' && $key != 'token')
					$query .= '&' . $key . '=' . urlencode($val);
			}
		}
		return ltrim($query, '&');
	}
	
	public function get($key, $default = null) {
		if (isset($this->get[$key])) {
			return $this->_trim($this->get[$key]);
		}
		return $default;
	}
	
	public function post($key, $default = null) {
		if (isset($this->post[$key])) {
			return $this->_trim($this->post[$key]);
		}
		return $default;
	}
	
	public function request($key, $default = null) {
		if (isset($this->request[$key])) {
			return $this->_trim($this->request[$key]);
		}
		return $default;
	}
	
	private function _trim($val) {
		if (is_array($val)) {
			$result = array();
			foreach($val as $k => $v) {
				$result[$k] = $this->_trim($v);
			}
			return $result;
		}
		else return trim($val);
	}
	
	public function server($key, $default = null) {
		if (isset($this->server[$key])) {
			return $this->server[$key];
		}
		return $default;
	}
	
	public function cookie($key, $default = null) {
		if (isset($this->cookie[$key])) {
			return $this->cookie[$key];
		}
		return $default;
	}
}
?>