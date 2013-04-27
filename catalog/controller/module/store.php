<?php   
class ControllerModuleStore extends Controller {
	protected function index() {
		$status = true;
		
		if (C('store_admin')) {
			$this->load->library('user');
		
			$this->user = new User($this->registry);
			
			$status = $this->user->isLogged();
		}
		
		if ($status) {
			$this->language->load('module/store');
			
			$this->data['heading_title'] = L('heading_title');
			
			$this->data['text_store'] = L('text_store');
			
			$this->data['store_id'] = C('config_store_id');			
			$this->data['stores'] = array();			
			$this->data['stores'][] = array(
				'store_id' => 0,
				'name'     => L('store_text_default'),
				'url'      => HTTP_SERVER
			);
			
			M('setting/store');			
			$results = $this->model_setting_store->getStores();
			
			foreach ($results as $result) {
				$this->data['stores'][] = array(
					'store_id' => $result['store_id'],
					'name'     => $result['name'],
					'url'      => $result['url']
				);
			}
			$this->render('module/store.tpl');
		}
	}
}
?>