<?php
abstract class Controller {
	protected $registry;
	protected $id;
	protected $layout;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $extract = array();
	protected $output;
	protected $messages;

	public function __construct($registry) {
		$this->registry = $registry;
		$this->messages = array();
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();
	}

	//cache function enhance
	protected function getChild($child, $args = array(), $cache = FALSE, $expire = 3600) {
		$action = new Action($child, $args);
		if ($cache) {
			$output = $this->cache->get($cache, 'module', FALSE);
			if ($output) return $output;
		}

		if (file_exists($action->getFile())) {
			require_once($action->getFile());
			$class = $action->getClass();
			$controller = new $class($this->registry);
			$controller->{$action->getMethod()}($action->getArgs());
			if ($cache) $this->cache->set($cache, $controller->output, 'module', FALSE, $expire);
			return $controller->output;
		} else {
			trigger_error('Error: Could not load controller ' . $child . '!');
			exit();
		}
	}

	protected function render($tpl = null) {
		if ($tpl) {
			if (defined('CATALOG')) {
				if (file_exists(DIR_TEMPLATE . C('config_template') . '/template/' . $tpl)) {
					$this->template = C('config_template') . '/template/' . $tpl;
				} else {
					$this->template = 'default/template/' . $tpl;
				}
			}
			else $this->template = $tpl;
		}

		foreach ($this->children as $child) {
			$this->data[basename($child)] = $this->getChild($child);
		}

		if (file_exists(DIR_TEMPLATE . $this->template)) {
			if ($this->registry->has('language')) {
				$_ = & $this->language->all();
			}
			extract($this->messages);
			
			if ($this->extract) {
				foreach($this->extract as $arr) {
					extract($arr);
				}
			}
			
			extract($this->data);
			
      		ob_start();
	  		require(DIR_TEMPLATE . $this->template);
	  		$this->output = ob_get_contents();
      		ob_end_clean();
			return $this->output;
    	} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();
    	}
	}

	public function display($tpl) {
		$this->response->setOutput($this->render($tpl));
	}

	protected function setMessage($key, $msg) {
		$this->messages[$key] = $msg;
	}
}