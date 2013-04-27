<?php
class ControllerPaymentPerpetualPayments extends Controller {

	public function index() {
		$this->language->load('payment/perpetual_payments');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('perpetual_payments', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/payment'));
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_payment'),
			'href'      => UA('extension/payment'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('payment/perpetual_payments'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['perpetual_payments_auth_id'])) {
			$this->data['perpetual_payments_auth_id'] = $this->request->post['perpetual_payments_auth_id'];
		} else {
			$this->data['perpetual_payments_auth_id'] = C('perpetual_payments_auth_id');
		}

		if (isset($this->request->post['perpetual_payments_auth_pass'])) {
			$this->data['perpetual_payments_auth_pass'] = $this->request->post['perpetual_payments_auth_pass'];
		} else {
			$this->data['perpetual_payments_auth_pass'] = C('perpetual_payments_auth_pass');
		}

		if (isset($this->request->post['perpetual_payments_test'])) {
			$this->data['perpetual_payments_test'] = $this->request->post['perpetual_payments_test'];
		} else {
			$this->data['perpetual_payments_test'] = C('perpetual_payments_test');
		}

		if (isset($this->request->post['perpetual_payments_total'])) {
			$this->data['perpetual_payments_total'] = $this->request->post['perpetual_payments_total'];
		} else {
			$this->data['perpetual_payments_total'] = C('perpetual_payments_total');
		}

		if (isset($this->request->post['perpetual_payments_order_status_id'])) {
			$this->data['perpetual_payments_order_status_id'] = $this->request->post['perpetual_payments_order_status_id'];
		} else {
			$this->data['perpetual_payments_order_status_id'] = C('perpetual_payments_order_status_id');
		}

		M('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['perpetual_payments_geo_zone_id'])) {
			$this->data['perpetual_payments_geo_zone_id'] = $this->request->post['perpetual_payments_geo_zone_id'];
		} else {
			$this->data['perpetual_payments_geo_zone_id'] = C('perpetual_payments_geo_zone_id');
		}

		M('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['perpetual_payments_status'])) {
			$this->data['perpetual_payments_status'] = $this->request->post['perpetual_payments_status'];
		} else {
			$this->data['perpetual_payments_status'] = C('perpetual_payments_status');
		}

		if (isset($this->request->post['perpetual_payments_sort_order'])) {
			$this->data['perpetual_payments_sort_order'] = $this->request->post['perpetual_payments_sort_order'];
		} else {
			$this->data['perpetual_payments_sort_order'] = C('perpetual_payments_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/perpetual_payments.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/perpetual_payments')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['perpetual_payments_auth_id']) {
			$this->setMessage('error_auth_id', L('error_auth_id'));
			$pass = false;
		}

		if (!$this->request->post['perpetual_payments_auth_pass']) {
			$this->setMessage('error_auth_pass', L('error_auth_pass'));
			$pass = false;
		}

		return true;
	}
}
?>