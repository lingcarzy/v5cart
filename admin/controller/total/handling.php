<?php
class ControllerTotalHandling extends Controller {

	public function index() {
		$this->language->load('total/handling');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('handling', $this->request->post);

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
			'href'      => UA('total/handling'),
      		'separator' => ' :: '
   		);

		if ($this->request->isPost()) {
			$this->data['handling_total'] = $this->request->post['handling_total'];
			$this->data['handling_fee'] = $this->request->post['handling_fee'];
			$this->data['handling_tax_class_id'] = $this->request->post['handling_tax_class_id'];
			$this->data['handling_status'] = $this->request->post['handling_status'];
			$this->data['handling_sort_order'] = $this->request->post['handling_sort_order'];

		} else {
			$this->data['handling_total'] = C('handling_total');
			$this->data['handling_fee'] = C('handling_fee');
			$this->data['handling_tax_class_id'] = C('handling_tax_class_id');
			$this->data['handling_status'] = C('handling_status');
			$this->data['handling_sort_order'] = C('handling_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();


		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/handling.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/handling')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>