<?php
class ControllerPaymentPayMate extends Controller {

	public function index() {
		$this->language->load('payment/paymate');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('paymate', $this->request->post);

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
			'href'      => UA('payment/paymate'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['paymate_username'])) {
			$this->data['paymate_username'] = $this->request->post['paymate_username'];
		} else {
			$this->data['paymate_username'] = C('paymate_username');
		}

		if (isset($this->request->post['paymate_password'])) {
			$this->data['paymate_username'] = $this->request->post['paymate_username'];
		} elseif (C('paymate_password')) {
			$this->data['paymate_password'] = C('paymate_password');
		} else {
			$this->data['paymate_password'] = md5(mt_rand());
		}

		if (isset($this->request->post['paymate_test'])) {
			$this->data['paymate_test'] = $this->request->post['paymate_test'];
		} else {
			$this->data['paymate_test'] = C('paymate_test');
		}

		if (isset($this->request->post['paymate_total'])) {
			$this->data['paymate_total'] = $this->request->post['paymate_total'];
		} else {
			$this->data['paymate_total'] = C('paymate_total');
		}

		if (isset($this->request->post['paymate_order_status_id'])) {
			$this->data['paymate_order_status_id'] = $this->request->post['paymate_order_status_id'];
		} else {
			$this->data['paymate_order_status_id'] = C('paymate_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paymate_geo_zone_id'])) {
			$this->data['paymate_geo_zone_id'] = $this->request->post['paymate_geo_zone_id'];
		} else {
			$this->data['paymate_geo_zone_id'] = C('paymate_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paymate_status'])) {
			$this->data['paymate_status'] = $this->request->post['paymate_status'];
		} else {
			$this->data['paymate_status'] = C('paymate_status');
		}

		if (isset($this->request->post['paymate_sort_order'])) {
			$this->data['paymate_sort_order'] = $this->request->post['paymate_sort_order'];
		} else {
			$this->data['paymate_sort_order'] = C('paymate_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/paymate.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paymate')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['paymate_username']) {
			$this->setMessage('error_username', L('error_username'));
			$pass = false;
		}

		if (!$this->request->post['paymate_password']) {
			$this->setMessage('error_password', L('error_password'));
			$pass = false;
		}

		return $pass;
	}
}
?>
