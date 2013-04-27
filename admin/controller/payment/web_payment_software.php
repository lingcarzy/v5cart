<?php
class ControllerPaymentWebPaymentSoftware extends Controller {

	public function index() {
		$this->language->load('payment/web_payment_software');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('web_payment_software', $this->request->post);

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
			'href'      => UA('payment/web_payment_software'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['web_payment_software_login'])) {
			$this->data['web_payment_software_merchant_name'] = $this->request->post['web_payment_software_merchant_name'];
		} else {
			$this->data['web_payment_software_merchant_name'] = C('web_payment_software_merchant_name');
		}

		if (isset($this->request->post['web_payment_software_merchant_key'])) {
			$this->data['web_payment_software_merchant_key'] = $this->request->post['web_payment_software_merchant_key'];
		} else {
			$this->data['web_payment_software_merchant_key'] = C('web_payment_software_merchant_key');
		}

		if (isset($this->request->post['web_payment_software_mode'])) {
			$this->data['web_payment_software_mode'] = $this->request->post['web_payment_software_mode'];
		} else {
			$this->data['web_payment_software_mode'] = C('web_payment_software_mode');
		}

		if (isset($this->request->post['web_payment_software_method'])) {
			$this->data['web_payment_software_method'] = $this->request->post['web_payment_software_method'];
		} else {
			$this->data['web_payment_software_method'] = C('web_payment_software_method');
		}

		if (isset($this->request->post['web_payment_software_order_status_id'])) {
			$this->data['web_payment_software_order_status_id'] = $this->request->post['web_payment_software_order_status_id'];
		} else {
			$this->data['web_payment_software_order_status_id'] = C('web_payment_software_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['web_payment_software_geo_zone_id'])) {
			$this->data['web_payment_software_geo_zone_id'] = $this->request->post['web_payment_software_geo_zone_id'];
		} else {
			$this->data['web_payment_software_geo_zone_id'] = C('web_payment_software_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['web_payment_software_status'])) {
			$this->data['web_payment_software_status'] = $this->request->post['web_payment_software_status'];
		} else {
			$this->data['web_payment_software_status'] = C('web_payment_software_status');
		}

		if (isset($this->request->post['web_payment_software_total'])) {
			$this->data['web_payment_software_total'] = $this->request->post['web_payment_software_total'];
		} else {
			$this->data['web_payment_software_total'] = C('web_payment_software_total');
		}

		if (isset($this->request->post['web_payment_software_sort_order'])) {
			$this->data['web_payment_software_sort_order'] = $this->request->post['web_payment_software_sort_order'];
		} else {
			$this->data['web_payment_software_sort_order'] = C('web_payment_software_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/web_payment_software.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/web_payment_software')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['web_payment_software_merchant_name']) {
			$this->setMessage('error_login', L('error_login'));
			$pass = false;
		}

		if (!$this->request->post['web_payment_software_merchant_key']) {
			$this->setMessage('error_key', L('error_key'));
			$pass = false;
		}
		return $pass;
	}
}