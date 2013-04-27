<?php
class ControllerCommonMaintenance extends Controller {
    public function index() {
        if (C('config_maintenance')) {
			$route = '';
			
			if (isset($this->request->get['route'])) {
				$part = explode('/', $this->request->get['route']);
				
				if (isset($part[0])) {
					$route .= $part[0];
				}			
			}
			
			// Show site if logged in as admin
			$this->load->library('user');
			
			$this->user = new User($this->registry);
	
			if (($route != 'payment') && !$this->user->isLogged()) {
				return $this->forward('common/maintenance/info');
			}						
        }
    }
		
	public function info() {
        $this->language->load('common/maintenance');
        
        $this->document->setTitle(L('heading_title'));
        
        $this->data['heading_title'] = L('heading_title');
                
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'text'      => L('text_maintenance'),
			'href'      => U('common/maintenance'),
            'separator' => false
        );
		
		 $this->data['message'] = L('text_message');
		 
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->display('common/maintenance.tpl');
    }
}
?>