<?php

class Query_filter {
	private $session_key = null;
	private $router = null;
	
	public function __construct($session_key = null) {
		if (!$session_key) {
			$session_key = 'common_query_filter';
			if (isset($_SESSION['filter_route']) && $_SESSION['filter_route'] != ROUTE) {
				$_SESSION[$session_key] = array();
			}
			$_SESSION['filter_route'] = ROUTE;
		}
		
		if (!isset($_SESSION[$session_key]) || isset($_GET['filter_reset'])) {
			$_SESSION[$session_key] = array();
		}
		$this->session_key = $session_key;
	}
	public function get($key, $default = null) {
		if (isset($_GET[$key])) {
			$_SESSION[$this->session_key][$key] = $_GET[$key];
		}
		if (isset($_SESSION[$this->session_key][$key])) {
			return $_SESSION[$this->session_key][$key];
		}
		else return $default;
	}
}