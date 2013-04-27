<?php 
class ControllerPageIndex extends Controller {
	public function index($page_id = 0) {  
    	$this->language->load('page/page');
		
		M('catalog/page');
		
		if (!$page_id) $page_id = $this->request->get('page_id', 0);
		$page_id = (int) $page_id;
		
		$page_info = $this->model_catalog_page->getPage($page_id);
   		
		if (!$page_info) $this->redirect(U('error/not_found'));
		
		$this->document->setTitle($page_info['title']); 
		
		$this->data['continue'] = HTTP_SERVER;
		
		$this->data['button_continue'] = L('button_continue');
		
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
			'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
			'separator' => false
		);
		
		$this->data['breadcrumbs'][] = array(
			'text'      => $page_info['title'],
			'href'      => $page_info['link'],
			'separator' => L('text_separator')
		);
		$this->data['title'] = $page_info['title'];
		$this->data['content'] = html_entity_decode($page_info['content'], ENT_QUOTES, 'UTF-8');
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('page/index.tpl');
  	}
	
	public function info($page_id = 0) {
		M('catalog/page');
		
		if (!$page_id) $page_id = $this->request->get('page_id', 0);
		$page_id = (int) $page_id;    
		
		$page_info = $this->model_catalog_page->getPage($page_id);

		if ($page_info) {
			$output  = '<html dir="ltr" lang="en">' . "\n";
			$output .= '<head>' . "\n";
			$output .= '  <title>' . $page_info['title'] . '</title>' . "\n";
			$output .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$output .= '  <meta name="robots" content="noindex">' . "\n";
			$output .= '</head>' . "\n";
			$output .= '<body>' . "\n";
			$output .= '  <h1>' . $page_info['title'] . '</h1>' . "\n";
			$output .= html_entity_decode($page_info['content'], ENT_QUOTES, 'UTF-8') . "\n";
			$output .= '  </body>' . "\n";
			$output .= '</html>' . "\n";			

			$this->response->setOutput($output);
		}
	}
}
?>