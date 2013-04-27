<?php
class ControllerTotalCredit extends Controller {

	public function index() {
		$this->language->load('total/credit');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('credit', $this->request->post);

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
			'href'      => UA('total/credit'),
      		'separator' => ' :: '
   		);


		$this->data['credit_status'] = P('credit_status', C('credit_status'));
		$this->data['credit_sort_order'] = P('credit_sort_order', C('credit_sort_order'));

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/credit.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/credit')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>