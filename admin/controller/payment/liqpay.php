<?php
class ControllerPaymentLiqPay extends Controller {

	public function index() {
		$this->language->load('payment/liqpay');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('liqpay', $this->request->post);

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
			'href'      => UA('payment/liqpay'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['liqpay_merchant'])) {
			$this->data['liqpay_merchant'] = $this->request->post['liqpay_merchant'];
		} else {
			$this->data['liqpay_merchant'] = C('liqpay_merchant');
		}

		if (isset($this->request->post['liqpay_signature'])) {
			$this->data['liqpay_signature'] = $this->request->post['liqpay_signature'];
		} else {
			$this->data['liqpay_signature'] = C('liqpay_signature');
		}

		if (isset($this->request->post['liqpay_type'])) {
			$this->data['liqpay_type'] = $this->request->post['liqpay_type'];
		} else {
			$this->data['liqpay_type'] = C('liqpay_type');
		}

		if (isset($this->request->post['liqpay_total'])) {
			$this->data['liqpay_total'] = $this->request->post['liqpay_total'];
		} else {
			$this->data['liqpay_total'] = C('liqpay_total');
		}

		if (isset($this->request->post['liqpay_order_status_id'])) {
			$this->data['liqpay_order_status_id'] = $this->request->post['liqpay_order_status_id'];
		} else {
			$this->data['liqpay_order_status_id'] = C('liqpay_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['liqpay_geo_zone_id'])) {
			$this->data['liqpay_geo_zone_id'] = $this->request->post['liqpay_geo_zone_id'];
		} else {
			$this->data['liqpay_geo_zone_id'] = C('liqpay_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['liqpay_status'])) {
			$this->data['liqpay_status'] = $this->request->post['liqpay_status'];
		} else {
			$this->data['liqpay_status'] = C('liqpay_status');
		}

		if (isset($this->request->post['liqpay_sort_order'])) {
			$this->data['liqpay_sort_order'] = $this->request->post['liqpay_sort_order'];
		} else {
			$this->data['liqpay_sort_order'] = C('liqpay_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/liqpay.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/liqpay')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;
		if (!$this->request->post['liqpay_merchant']) {
			$this->setMessage('error_merchant', L('error_merchant'));
			$pass = false;
		}

		if (!$this->request->post['liqpay_signature']) {
			$this->setMessage('error_signature', L('error_signature'));
			$pass = false;
		}

		return $pass;
	}
}
?>