<?php
class ControllerPaymentFreeCheckout extends Controller {

	public function index() {
		$this->language->load('payment/free_checkout');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('free_checkout', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/payment'));
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      =>  UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_payment'),
			'href'      => UA('extension/payment'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('payment/free_checkout'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['free_checkout_order_status_id'])) {
			$this->data['free_checkout_order_status_id'] = $this->request->post['free_checkout_order_status_id'];
		} else {
			$this->data['free_checkout_order_status_id'] = C('free_checkout_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['free_checkout_status'])) {
			$this->data['free_checkout_status'] = $this->request->post['free_checkout_status'];
		} else {
			$this->data['free_checkout_status'] = C('free_checkout_status');
		}

		if (isset($this->request->post['free_checkout_sort_order'])) {
			$this->data['free_checkout_sort_order'] = $this->request->post['free_checkout_sort_order'];
		} else {
			$this->data['free_checkout_sort_order'] = C('free_checkout_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/free_checkout.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/free_checkout')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		return true;
	}
}
?>