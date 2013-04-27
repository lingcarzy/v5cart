<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		
		//$this->data['text_information'] = L('text_information');
		//$this->data['text_service'] = L('text_service');
		//$this->data['text_extra'] = L('text_extra');
		
		$this->data['text_contact'] = L('text_contact');
		$this->data['text_return'] = L('text_return');
    	$this->data['text_sitemap'] = L('text_sitemap');
		$this->data['text_manufacturer'] = L('text_manufacturer');
		$this->data['text_voucher'] = L('text_voucher');
		$this->data['text_affiliate'] = L('text_affiliate');
		$this->data['text_special'] = L('text_special');
		
		//$this->data['text_account'] = L('text_account');
		//$this->data['text_order'] = L('text_order');
		//$this->data['text_wishlist'] = L('text_wishlist');
		//$this->data['text_newsletter'] = L('text_newsletter');
		
		// M('catalog/page');		
		// $this->data['informations'] = array();

		// foreach ($this->model_catalog_page->getPages() as $result) {
			// if ($result['bottom']) {
				// $this->data['informations'][] = array(
					// 'title' => $result['title'],
					// 'href'  => $result['link'],
				// );
			// }
    	// }
		
		
		$this->data['special'] = U('product/special');
		$this->data['affiliate'] = U('affiliate/account', '', 'SSL');
		$this->data['voucher'] = U('account/voucher', '', 'SSL');
		$this->data['manufacturer'] = U('product/manufacturer');
		$this->data['return'] = U('account/return/insert', '', 'SSL');
    	$this->data['sitemap'] = U('page/sitemap');
		$this->data['contact'] = U('page/contact');
		
		// $this->data['order'] = U('account/order', '', 'SSL');
		// $this->data['wishlist'] = U('account/wishlist', '', 'SSL');
		// $this->data['newsletter'] = U('account/newsletter', '', 'SSL');		
		// $this->data['account'] = U('account/account', '', 'SSL');
		
		$this->data['queries'] = sprintf(L('text_queries'), $this->db->getQueries());
		$this->data['powered'] = sprintf(L('text_powered'), C('config_name'), date('Y', time()));	
		
		$this->data['livechat'] = $this->getChild('module/livechat', null, 'livechat', 3600);
		
		// Whos Online
		if (C('config_customer_online')) {
			M('tool/online');
			
			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];	
			} else {
				$ip = ''; 
			}
			
			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];	
			} else {
				$url = '';
			}
			
			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];	
			} else {
				$referer = '';
			}
			
			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}
		
		$this->render('common/footer.tpl');
	}
}
?>