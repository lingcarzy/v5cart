<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		global $CATEGORIES;
		
		$this->data['title'] = $this->document->getTitle();
		if (!$this->data['title']) {
			$this->data['title'] = C('config_title');
		}
		else {
			$this->data['title'] .= ' - ' . C('config_title');
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = C('config_ssl');
		} else {
			$this->data['base'] = C('config_url');
		}
		
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['google_analytics'] = html_entity_decode(C('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['lang'] = L('code');
		$this->data['direction'] = L('direction');
				
		$this->language->load('common/header');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}	
				
		if (C('config_icon') && file_exists(DIR_IMAGE . C('config_icon'))) {
			$this->data['icon'] = $server . C('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		$this->data['name'] = C('config_name');
		
		if (C('config_logo') && file_exists(DIR_IMAGE . C('config_logo'))) {
			$this->data['logo'] = $server . C('config_logo');
		} else {
			$this->data['logo'] = '';
		}
		$this->data['text_home'] = L('text_home');
		$this->data['text_wishlist'] = sprintf(L('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_welcome'] = sprintf(L('text_welcome'), U('account/login', '', 'SSL'), U('account/register', '', 'SSL'));
		$this->data['text_shopping_cart'] = L('text_shopping_cart');
    	$this->data['text_search'] = L('text_search');
		$this->data['text_logged'] = sprintf(L('text_logged'), U('account/account', '', 'SSL'), $this->customer->getFirstName(), U('account/logout', '', 'SSL'));
		$this->data['text_account'] = L('text_account');
    	$this->data['text_checkout'] = L('text_checkout');
		
		$this->data['logged'] = $this->customer->isLogged();
		
		$this->data['home'] = HTTP_SERVER;
		$this->data['wishlist'] = U('account/wishlist', '', 'SSL');
		$this->data['account'] = U('account/account', '', 'SSL');
		$this->data['shopping_cart'] = U('checkout/cart');
		$this->data['checkout'] = U('checkout/checkout', '', 'SSL');
		
		//Search
		$this->data['search'] = G('search');
		
		// Menu
		M('catalog/category');		
		M('catalog/product');
		
		$this->data['categories'] = $CATEGORIES;
		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart'
		);
		
    	$this->render('common/header.tpl');
	} 	
}
?>