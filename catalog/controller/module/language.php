<?php  
class ControllerModuleLanguage extends Controller {
	protected function index() {
    	if (isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect(HTTP_SERVER);
			}
    	}		
		
		$this->language->load('module/language');
		$this->data['text_language'] = L('text_language');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
			
		$this->data['action'] = U('module/language', '', $connection);

		$this->data['language_code'] = $this->session->data['language'];
		
		$this->data['languages'] = C('cache_language');

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = HTTP_SERVER;
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}	
					
			$this->data['redirect'] = U($route, $url, $connection);
		}
		
		$this->render('module/language.tpl');
	}
}
?>