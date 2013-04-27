<?php
class ControllerTotalTax extends Controller {

	public function index() {
		$this->language->load('total/tax');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('tax', $this->request->post);

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
			'href'      => UA('total/tax'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['tax_status'])) {
			$this->data['tax_status'] = $this->request->post['tax_status'];
		} else {
			$this->data['tax_status'] = C('tax_status');
		}

		if (isset($this->request->post['tax_sort_order'])) {
			$this->data['tax_sort_order'] = $this->request->post['tax_sort_order'];
		} else {
			$this->data['tax_sort_order'] = C('tax_sort_order');
		}


		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('total/tax.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/tax')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>