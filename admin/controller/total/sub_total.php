<?php
class ControllerTotalSubTotal extends Controller {

	public function index() {
		$this->language->load('total/sub_total');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('sub_total', $this->request->post);

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
			'href'      => UA('total/sub_total'),
      		'separator' => ' :: '
   		);

		if ($this->request->isPost()) {
			$this->data['sub_total_status'] = P('sub_total_status');
			$this->data['sub_total_sort_order'] = P('sub_total_sort_order');
		} else {
			$this->data['sub_total_status'] = C('sub_total_status');
			$this->data['sub_total_sort_order'] = C('sub_total_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/sub_total.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/sub_total')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>