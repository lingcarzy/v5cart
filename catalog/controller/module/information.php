<?php  
class ControllerModuleInformation extends Controller {
	protected function index() {
		$this->language->load('module/information');
		
		$this->data['heading_title'] = L('heading_title');
    	
		$this->data['text_contact'] = L('text_contact');
    	$this->data['text_sitemap'] = L('text_sitemap');
		
		$this->data['contact'] = U('information/contact');
    	$this->data['sitemap'] = U('information/sitemap');
		
		M('catalog/page');
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_page->getPages() as $result) {
      		$this->data['informations'][] = array(
        		'title' => $result['title'],
	    		'href'  => $result['link'],
      		);
    	}
		
		$this->render('module/information.tpl');
	}
}
?>