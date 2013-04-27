<?php
class ControllerPaymentPaypalExpress extends Controller {

	public function index() {
		$this->language->load('payment/paypal_express');
		$this->document->settitle(L('heading_title'));

		if ($this->request->isPost() && $this->validate()) {
			M('setting/setting');
			$this->model_setting_setting->editSetting('paypal_express', $this->request->post);

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
			'href'      => UA('payment/paypal_express'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['paypal_express_username'])) {
			$this->data['paypal_express_username'] = $this->request->post['paypal_express_username'];
		} else {
			$this->data['paypal_express_username'] = C('paypal_express_username');
		}

		if (isset($this->request->post['paypal_express_password'])) {
			$this->data['paypal_express_password'] = $this->request->post['paypal_expresse_password'];
		} else {
			$this->data['paypal_express_password'] = C('paypal_express_password');
		}

		if (isset($this->request->post['paypal_express_signature'])) {
			$this->data['paypal_express_signature'] = $this->request->post['paypal_express_signature'];
		} else {
			$this->data['paypal_express_signature'] = C('paypal_express_signature');
		}

		if (isset($this->request->post['paypal_express_add_to_cart'])) {
			$this->data['paypal_express_add_to_cart'] = $this->request->post['paypal_express_add_to_cart'];
		} else {
			$this->data['paypal_express_add_to_cart'] = C('paypal_express_add_to_cart');
		}

		if (isset($this->request->post['paypal_express_test'])) {
			$this->data['paypal_express_test'] = $this->request->post['paypal_express_test'];
		} else {
			$this->data['paypal_express_test'] = C('paypal_express_test');
		}

		if (isset($this->request->post['paypal_express_method'])) {
			$this->data['paypal_express_method'] = $this->request->post['paypal_express_method'];
		} else {
			$this->data['paypal_express_method'] = C('paypal_express_method');
		}

		if (isset($this->request->post['paypal_express_order_status_id'])) {
			$this->data['paypal_express_order_status_id'] = $this->request->post['paypal_express_order_status_id'];
		} else {
			$this->data['paypal_express_order_status_id'] = C('paypal_express_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paypal_express_geo_zone_id'])) {
			$this->data['paypal_express_geo_zone_id'] = $this->request->post['paypal_express_geo_zone_id'];
		} else {
			$this->data['paypal_express_geo_zone_id'] = C('paypal_express_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paypal_express_status'])) {
			$this->data['paypal_express_status'] = $this->request->post['paypal_express_status'];
		} else {
			$this->data['paypal_express_status'] = C('paypal_express_status');
		}

		if (isset($this->request->post['paypal_express_sort_order'])) {
			$this->data['paypal_express_sort_order'] = $this->request->post['paypal_express_sort_order'];
		} else {
			$this->data['paypal_express_sort_order'] = C('paypal_express_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/paypal_express.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paypal_express')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (empty($this->request->post['paypal_express_username'])) {
			$this->setMessage('error_username', L('error_username'));
			$pass = false;
		}

		if (empty($this->request->post['paypal_express_password'])) {
			$this->setMessage('error_password', L('error_password'));
			$pass = false;
		}

		if (empty($this->request->post['paypal_express_signature'])) {
			$this->setMessage('error_signature', L('error_signature'));
			$pass = false;
		}

		return $pass;
	}
}
?>