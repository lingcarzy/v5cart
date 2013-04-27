<?php  
class ControllerCommonLogin extends Controller { 
	 
	public function index() { 
    	$this->language->load('common/login');

		$this->document->setTitle(L('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->redirect(UA('common/admincp'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			$this->session->data['token'] = md5(mt_rand());
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				$this->redirect(UA('common/admincp'));
			}
		}
		
		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) 
		|| ((isset($this->request->get['token']) && (isset($this->session->data['token']) 
							&& ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->setMessage('error_warning', L('error_token'));
		}
		
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
    	$this->data['action'] = $this->url->link('common/login', '', 'SSL');
		$this->data['username'] = P('username');
		$this->data['password'] = P('password');

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];			
			unset($this->request->get['route']);
			
			if (isset($this->request->get['token'])) {
				unset($this->request->get['token']);
			}
			
			$url = '';
			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}
			
			$this->data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$this->data['redirect'] = '';	
		}
	
		if (C('config_password')) {
			$this->data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$this->data['forgotten'] = '';
		}
	
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('common/login.tpl');
  	}
		
	protected function validate() {
		if (isset($this->request->post['username']) && isset($this->request->post['password']) 
			&& !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->setMessage('error_warning', L('error_login'));
			return false;
		}
		return true;
	}
}  
?>