<?php
class ControllerFeedGoogleBase extends Controller {

	public function index() {
		$this->language->load('feed/google_base');

		$this->document->setTitle(L('heading_title'));

		if ($this->request->isPost() && $this->validate()) {
			M('setting/setting');
			$this->model_setting_setting->editSetting('google_base', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/feed'));
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_feed'),
			'href'      => UA('extension/feed'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('feed/google_base'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['google_base_status'])) {
			$this->data['google_base_status'] = $this->request->post['google_base_status'];
		} else {
			$this->data['google_base_status'] = C('google_base_status');
		}

		$this->data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/google_base';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('feed/google_base.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'feed/google_base')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>