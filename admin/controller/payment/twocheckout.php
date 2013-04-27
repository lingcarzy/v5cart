<?php
class ControllerPaymentTwoCheckout extends Controller {

	public function index() {
		$this->language->load('payment/twocheckout');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('twocheckout', $this->request->post);

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
			'href'      => UA('payment/twocheckout'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['twocheckout_account'])) {
			$this->data['twocheckout_account'] = $this->request->post['twocheckout_account'];
		} else {
			$this->data['twocheckout_account'] = C('twocheckout_account');
		}

		if (isset($this->request->post['twocheckout_secret'])) {
			$this->data['twocheckout_secret'] = $this->request->post['twocheckout_secret'];
		} else {
			$this->data['twocheckout_secret'] = C('twocheckout_secret');
		}

		if (isset($this->request->post['twocheckout_test'])) {
			$this->data['twocheckout_test'] = $this->request->post['twocheckout_test'];
		} else {
			$this->data['twocheckout_test'] = C('twocheckout_test');
		}

		if (isset($this->request->post['twocheckout_total'])) {
			$this->data['twocheckout_total'] = $this->request->post['twocheckout_total'];
		} else {
			$this->data['twocheckout_total'] = C('twocheckout_total');
		}

		if (isset($this->request->post['twocheckout_order_status_id'])) {
			$this->data['twocheckout_order_status_id'] = $this->request->post['twocheckout_order_status_id'];
		} else {
			$this->data['twocheckout_order_status_id'] = C('twocheckout_order_status_id');
		}

		M('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['twocheckout_geo_zone_id'])) {
			$this->data['twocheckout_geo_zone_id'] = $this->request->post['twocheckout_geo_zone_id'];
		} else {
			$this->data['twocheckout_geo_zone_id'] = C('twocheckout_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['twocheckout_status'])) {
			$this->data['twocheckout_status'] = $this->request->post['twocheckout_status'];
		} else {
			$this->data['twocheckout_status'] = C('twocheckout_status');
		}

		if (isset($this->request->post['twocheckout_sort_order'])) {
			$this->data['twocheckout_sort_order'] = $this->request->post['twocheckout_sort_order'];
		} else {
			$this->data['twocheckout_sort_order'] = C('twocheckout_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/twocheckout.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/twocheckout')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['twocheckout_account']) {
			$this->setMessage('error_account', L('error_account'));
			$pass = false;
		}

		if (!$this->request->post['twocheckout_secret']) {
			$this->setMessage('error_secret', L('error_secret'));
			$pass = false;
		}

		return $pass;
	}
}
?>