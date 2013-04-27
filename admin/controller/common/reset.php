<?php
class ControllerCommonReset extends Controller {

	public function index() {
		if ($this->user->isLogged()) {
			$this->redirect(UA('common/home', '', 'SSL'));
		}
		if (!C('config_password')) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
		$code = $this->request->get('code', '');
		M('user/user');
		$user_info = $this->model_user_user->getUserByCode($code);

		if ($user_info) {
			$this->language->load('common/reset');

			if ($this->request->isPost() && $this->validate()) {
				$this->model_user_user->editPassword($user_info['user_id'], $this->request->post['password']);

				$this->session->data['success'] = L('text_success');
				$this->redirect($this->url->link('common/login', '', 'SSL'));
			}

			$this->data['breadcrumbs'] = array();
			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => UA('common/home'),
				'separator' => false
			);
			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_reset'),
				'href'      => UA('common/reset', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['action'] = $this->url->link('common/reset', 'code=' . $code, 'SSL');
			$this->data['cancel'] = $this->url->link('common/login', '', 'SSL');

			$this->data['password'] = P('password', '');
			$this->data['confirm'] = P('confirm', '');

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->display('common/reset.tpl');
		} else {
			M('setting/setting');
			$this->model_setting_setting->editSettingValue('config', 'config_password', '0');
			return $this->forward('common/login');
		}
	}

	protected function validate() {
    	if (range_length($this->request->post['password'], 4, 20)) {
      		$this->setMessage('error_password', L('error_password'));
			return false;
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->setMessage('error_confirm', L('error_confirm'));
			return false;
    	}
		return true;
	}
}
?>