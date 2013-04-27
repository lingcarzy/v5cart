<?php  
class ControllerPageSitemap extends Controller {
	public function index() {
    	$this->language->load('page/sitemap');
 
		$this->document->setTitle(L('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('page/sitemap'),      	
        	'separator' => L('text_separator')
      	);
		
		$this->data['heading_title'] = L('heading_title');

		$this->data['text_special'] = L('text_special');
		$this->data['text_account'] = L('text_account');
    	$this->data['text_edit'] = L('text_edit');
    	$this->data['text_password'] = L('text_password');
    	$this->data['text_address'] = L('text_address');
    	$this->data['text_history'] = L('text_history');
    	$this->data['text_download'] = L('text_download');
    	$this->data['text_cart'] = L('text_cart');
    	$this->data['text_checkout'] = L('text_checkout');
    	$this->data['text_search'] = L('text_search');
    	$this->data['text_information'] = L('text_information');
    	$this->data['text_contact'] = L('text_contact');
		
		M('catalog/category');
		M('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories_1 = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'name' => $category_3['name'],
						'href' => U('product/category', 'cate_id=' . $category_3['category_id'])
					);
				}
				
				$level_2_data[] = array(
					'name'     => $category_2['name'],
					'children' => $level_3_data,
					'href'     => U('product/category', 'cate_id=' . $category_2['category_id'])	
				);					
			}
			
			$this->data['categories'][] = array(
				'name'     => $category_1['name'],
				'children' => $level_2_data,
				'href'     => U('product/category', 'cate_id=' . $category_1['category_id'])
			);
		}
		
		M('catalog/page');		
		$this->data['pages'] = array();
    	
		foreach ($this->model_catalog_page->getpages() as $result) {
      		$this->data['pages'][] = array(
        		'title' => $result['title'],
        		'href'  => $result['link'], 
      		);
    	}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('page/sitemap.tpl');
	}
}
?>