<?php
class ControllerPaymentWorldPay extends Controller {

	public function index() {
		$this->language->load('payment/worldpay');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('worldpay', $this->request->post);

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
			'href'      => UA('payment/worldpay'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['worldpay_merchant'])) {
			$this->data['worldpay_merchant'] = $this->request->post['worldpay_merchant'];
		} else {
			$this->data['worldpay_merchant'] = C('worldpay_merchant');
		}

		if (isset($this->request->post['worldpay_password'])) {
			$this->data['worldpay_password'] = $this->request->post['worldpay_password'];
		} else {
			$this->data['worldpay_password'] = C('worldpay_password');
		}

		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/worldpay/callback';

		if (isset($this->request->post['worldpay_test'])) {
			$this->data['worldpay_test'] = $this->request->post['worldpay_test'];
		} else {
			$this->data['worldpay_test'] = C('worldpay_test');
		}

		if (isset($this->request->post['worldpay_total'])) {
			$this->data['worldpay_total'] = $this->request->post['worldpay_total'];
		} else {
			$this->data['worldpay_total'] = C('worldpay_total');
		}

		if (isset($this->request->post['worldpay_order_status_id'])) {
			$this->data['worldpay_order_status_id'] = $this->request->post['worldpay_order_status_id'];
		} else {
			$this->data['worldpay_order_status_id'] = C('worldpay_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['worldpay_geo_zone_id'])) {
			$this->data['worldpay_geo_zone_id'] = $this->request->post['worldpay_geo_zone_id'];
		} else {
			$this->data['worldpay_geo_zone_id'] = C('worldpay_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['worldpay_status'])) {
			$this->data['worldpay_status'] = $this->request->post['worldpay_status'];
		} else {
			$this->data['worldpay_status'] = C('worldpay_status');
		}

		if (isset($this->request->post['worldpay_sort_order'])) {
			$this->data['worldpay_sort_order'] = $this->request->post['worldpay_sort_order'];
		} else {
			$this->data['worldpay_sort_order'] = C('worldpay_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/worldpay.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/worldpay')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['worldpay_merchant']) {
			$this->setMessage('error_merchant', L('error_merchant'));
			$pass = false;
		}

		if (!$this->request->post['worldpay_password']) {
			$this->setMessage('error_password', L('error_password'));
			$pass = false;
		}

		return $pass;
	}
}
?>