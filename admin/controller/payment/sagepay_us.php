<?php
class ControllerPaymentSagepayUS extends Controller {

	public function index() {
		$this->language->load('payment/sagepay_us');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay_us', $this->request->post);

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
			'href'      => UA('payment/sagepay_us'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['sagepay_us_merchant_id'])) {
			$this->data['sagepay_us_merchant_id'] = $this->request->post['sagepay_us_merchant_id'];
		} else {
			$this->data['sagepay_us_merchant_id'] = C('sagepay_us_merchant_id');
		}

		if (isset($this->request->post['sagepay_us_merchant_key'])) {
			$this->data['sagepay_us_merchant_key'] = $this->request->post['sagepay_us_merchant_key'];
		} else {
			$this->data['sagepay_us_merchant_key'] = C('sagepay_us_merchant_key');
		}

		if (isset($this->request->post['sagepay_us_total'])) {
			$this->data['sagepay_us_total'] = $this->request->post['sagepay_us_total'];
		} else {
			$this->data['sagepay_us_total'] = C('sagepay_us_total');
		}

		if (isset($this->request->post['sagepay_us_order_status_id'])) {
			$this->data['sagepay_us_order_status_id'] = $this->request->post['sagepay_us_order_status_id'];
		} else {
			$this->data['sagepay_us_order_status_id'] = C('sagepay_us_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sagepay_us_geo_zone_id'])) {
			$this->data['sagepay_us_geo_zone_id'] = $this->request->post['sagepay_us_geo_zone_id'];
		} else {
			$this->data['sagepay_us_geo_zone_id'] = C('sagepay_us_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sagepay_us_status'])) {
			$this->data['sagepay_us_status'] = $this->request->post['sagepay_us_status'];
		} else {
			$this->data['sagepay_us_status'] = C('sagepay_us_status');
		}

		if (isset($this->request->post['sagepay_us_sort_order'])) {
			$this->data['sagepay_us_sort_order'] = $this->request->post['sagepay_us_sort_order'];
		} else {
			$this->data['sagepay_us_sort_order'] = C('sagepay_us_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/sagepay_us.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay_us')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['sagepay_us_merchant_id']) {
			$this->setMessage('error_merchant_id', L('error_merchant_id'));
			$pass = false;
		}

		if (!$this->request->post['sagepay_us_merchant_key']) {
			$this->setMessage('error_merchant_key', L('error_merchant_key'));
		}

		return $pass;
	}
}
?>