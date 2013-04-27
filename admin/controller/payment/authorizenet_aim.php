<?php
class ControllerPaymentAuthorizenetAim extends Controller {

	public function index() {
		$this->language->load('payment/authorizenet_aim');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('authorizenet_aim', $this->request->post);

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
			'href'      => UA('payment/authorizenet_aim'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['authorizenet_aim_login'])) {
			$this->data['authorizenet_aim_login'] = $this->request->post['authorizenet_aim_login'];
		} else {
			$this->data['authorizenet_aim_login'] = C('authorizenet_aim_login');
		}

		if (isset($this->request->post['authorizenet_aim_key'])) {
			$this->data['authorizenet_aim_key'] = $this->request->post['authorizenet_aim_key'];
		} else {
			$this->data['authorizenet_aim_key'] = C('authorizenet_aim_key');
		}

		if (isset($this->request->post['authorizenet_aim_hash'])) {
			$this->data['authorizenet_aim_hash'] = $this->request->post['authorizenet_aim_hash'];
		} else {
			$this->data['authorizenet_aim_hash'] = C('authorizenet_aim_hash');
		}

		if (isset($this->request->post['authorizenet_aim_server'])) {
			$this->data['authorizenet_aim_server'] = $this->request->post['authorizenet_aim_server'];
		} else {
			$this->data['authorizenet_aim_server'] = C('authorizenet_aim_server');
		}

		if (isset($this->request->post['authorizenet_aim_mode'])) {
			$this->data['authorizenet_aim_mode'] = $this->request->post['authorizenet_aim_mode'];
		} else {
			$this->data['authorizenet_aim_mode'] = C('authorizenet_aim_mode');
		}

		if (isset($this->request->post['authorizenet_aim_method'])) {
			$this->data['authorizenet_aim_method'] = $this->request->post['authorizenet_aim_method'];
		} else {
			$this->data['authorizenet_aim_method'] = C('authorizenet_aim_method');
		}

		if (isset($this->request->post['authorizenet_aim_total'])) {
			$this->data['authorizenet_aim_total'] = $this->request->post['authorizenet_aim_total'];
		} else {
			$this->data['authorizenet_aim_total'] = C('authorizenet_aim_total');
		}

		if (isset($this->request->post['authorizenet_aim_order_status_id'])) {
			$this->data['authorizenet_aim_order_status_id'] = $this->request->post['authorizenet_aim_order_status_id'];
		} else {
			$this->data['authorizenet_aim_order_status_id'] = C('authorizenet_aim_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['authorizenet_aim_geo_zone_id'])) {
			$this->data['authorizenet_aim_geo_zone_id'] = $this->request->post['authorizenet_aim_geo_zone_id'];
		} else {
			$this->data['authorizenet_aim_geo_zone_id'] = C('authorizenet_aim_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['authorizenet_aim_status'])) {
			$this->data['authorizenet_aim_status'] = $this->request->post['authorizenet_aim_status'];
		} else {
			$this->data['authorizenet_aim_status'] = C('authorizenet_aim_status');
		}

		if (isset($this->request->post['authorizenet_aim_sort_order'])) {
			$this->data['authorizenet_aim_sort_order'] = $this->request->post['authorizenet_aim_sort_order'];
		} else {
			$this->data['authorizenet_aim_sort_order'] = C('authorizenet_aim_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/authorizenet_aim.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/authorizenet_aim')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['authorizenet_aim_login']) {
			$this->setMessage('error_login', L('error_login'));
			$pass = false;
		}

		if (!$this->request->post['authorizenet_aim_key']) {
			$this->setMessage('error_key', L('error_key'));
			$pass = false;
		}

		return $pass;
	}
}
?>