<?php
class ControllerTotalTotal extends Controller {

	public function index() {
		$this->language->load('total/total');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('total', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/total'));
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_total'),
			'href'      => UA('extension/total'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('total/total'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['total_status'])) {
			$this->data['total_status'] = $this->request->post['total_status'];
		} else {
			$this->data['total_status'] = C('total_status');
		}

		if (isset($this->request->post['total_sort_order'])) {
			$this->data['total_sort_order'] = $this->request->post['total_sort_order'];
		} else {
			$this->data['total_sort_order'] = C('total_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/total.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/total')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>