<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = false;
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}
	
	public function setRewrite($rewrite = true) {
		$this->rewrite = $rewrite;
	}
	
	public function link($route, $args = '', $connection = 'NONSSL') {
		
		if ($connection ==  'NONSSL') {
			$url = $this->url;
		} else {
			$url = $this->ssl;	
		}
		
		if ($this->rewrite) {
			$url .= seo_url_rewrite($route, $args);
		}
		else {
			$url .= 'index.php?route=' . $route;				
			if ($args) {
				$url .= '&' . trim($args, '&');
			}
		}	
		return $url;
	}
}
?>