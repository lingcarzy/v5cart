<?php  
class ControllerModuleCurrency extends Controller {
	protected function index() {
		if (isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect(HTTP_SERVER);
			}
   		}
		
		$this->language->load('module/currency');		
		
		$this->data['text_currency'] = L('text_currency');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
		
		$this->data['action'] = U('module/currency', '', $connection);
		
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->data['currencies'] = C('cache_currency');
		
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
		
		$this->render('module/currency.tpl');
	}
}
?>