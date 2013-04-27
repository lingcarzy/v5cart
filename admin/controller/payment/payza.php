<?php
class ControllerPaymentPayza extends Controller {

	public function index() {
		$this->language->load('payment/payza');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('payza', $this->request->post);

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
			'href'      => UA('payment/payza'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['payza_merchant'])) {
			$this->data['payza_merchant'] = $this->request->post['payza_merchant'];
		} else {
			$this->data['payza_merchant'] = C('payza_merchant');
		}

		if (isset($this->request->post['payza_security'])) {
			$this->data['payza_security'] = $this->request->post['payza_security'];
		} else {
			$this->data['payza_security'] = C('payza_security');
		}

		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/payza/callback';

		if (isset($this->request->post['payza_total'])) {
			$this->data['payza_total'] = $this->request->post['payza_total'];
		} else {
			$this->data['payza_total'] = C('payza_total');
		}

		if (isset($this->request->post['payza_order_status_id'])) {
			$this->data['payza_order_status_id'] = $this->request->post['payza_order_status_id'];
		} else {
			$this->data['payza_order_status_id'] = C('payza_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payza_geo_zone_id'])) {
			$this->data['payza_geo_zone_id'] = $this->request->post['payza_geo_zone_id'];
		} else {
			$this->data['payza_geo_zone_id'] = C('payza_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payza_status'])) {
			$this->data['payza_status'] = $this->request->post['payza_status'];
		} else {
			$this->data['payza_status'] = C('payza_status');
		}

		if (isset($this->request->post['payza_sort_order'])) {
			$this->data['payza_sort_order'] = $this->request->post['payza_sort_order'];
		} else {
			$this->data['payza_sort_order'] = C('payza_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/payza.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payza')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['payza_merchant']) {
			$this->setMessage('error_merchant', L('error_merchant'));
			$pass = false;
		}

		if (!$this->request->post['payza_security']) {
			$this->setMessage('error_security', L('error_security'));
			$pass = false;
		}

		return $pass;
	}
}
?>