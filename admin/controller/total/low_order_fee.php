<?php
class ControllerTotalLowOrderFee extends Controller {

	public function index() {
		$this->language->load('total/low_order_fee');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('low_order_fee', $this->request->post);

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
			'href'      => UA('total/low_order_fee'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['low_order_fee_total'])) {
			$this->data['low_order_fee_total'] = $this->request->post['low_order_fee_total'];
		} else {
			$this->data['low_order_fee_total'] = C('low_order_fee_total');
		}

		if (isset($this->request->post['low_order_fee_fee'])) {
			$this->data['low_order_fee_fee'] = $this->request->post['low_order_fee_fee'];
		} else {
			$this->data['low_order_fee_fee'] = C('low_order_fee_fee');
		}

		if (isset($this->request->post['low_order_fee_tax_class_id'])) {
			$this->data['low_order_fee_tax_class_id'] = $this->request->post['low_order_fee_tax_class_id'];
		} else {
			$this->data['low_order_fee_tax_class_id'] = C('low_order_fee_tax_class_id');
		}

		if (isset($this->request->post['low_order_fee_status'])) {
			$this->data['low_order_fee_status'] = $this->request->post['low_order_fee_status'];
		} else {
			$this->data['low_order_fee_status'] = C('low_order_fee_status');
		}

		if (isset($this->request->post['low_order_fee_sort_order'])) {
			$this->data['low_order_fee_sort_order'] = $this->request->post['low_order_fee_sort_order'];
		} else {
			$this->data['low_order_fee_sort_order'] = C('low_order_fee_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/low_order_fee.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/low_order_fee')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>