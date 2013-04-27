<?php
class ControllerTotalShipping extends Controller {

	public function index() {
		$this->language->load('total/shipping');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping', $this->request->post);

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
			'href'      => UA('total/shipping'),
      		'separator' => ' :: '
   		);

		if ($this->request->isPost()) {
			$this->data['shipping_estimator'] = P('shipping_estimator');
			$this->data['shipping_status'] = P('shipping_status');
			$this->data['shipping_sort_order'] = P('shipping_sort_order');
		} else {
			$this->data['shipping_estimator'] = C('shipping_estimator');
			$this->data['shipping_status'] = C('shipping_status');
			$this->data['shipping_sort_order'] = C('shipping_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/shipping.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/shipping')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>