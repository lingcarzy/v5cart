<?php
class ControllerPaymentMoneyBookers extends Controller {

	public function index() {
		$this->language->load('payment/moneybookers');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('moneybookers', $this->request->post);

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
			'href'      => UA('payment/moneybookers'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['moneybookers_email'])) {
			$this->data['moneybookers_email'] = $this->request->post['moneybookers_email'];
		} else {
			$this->data['moneybookers_email'] = C('moneybookers_email');
		}

		if (isset($this->request->post['moneybookers_secret'])) {
			$this->data['moneybookers_secret'] = $this->request->post['moneybookers_secret'];
		} else {
			$this->data['moneybookers_secret'] = C('moneybookers_secret');
		}

		if (isset($this->request->post['moneybookers_total'])) {
			$this->data['moneybookers_total'] = $this->request->post['moneybookers_total'];
		} else {
			$this->data['moneybookers_total'] = C('moneybookers_total');
		}

		if (isset($this->request->post['moneybookers_order_status_id'])) {
			$this->data['moneybookers_order_status_id'] = $this->request->post['moneybookers_order_status_id'];
		} else {
			$this->data['moneybookers_order_status_id'] = C('moneybookers_order_status_id');
		}

		if (isset($this->request->post['moneybookers_pending_status_id'])) {
			$this->data['moneybookers_pending_status_id'] = $this->request->post['moneybookers_pending_status_id'];
		} else {
			$this->data['moneybookers_pending_status_id'] = C('moneybookers_pending_status_id');
		}

		if (isset($this->request->post['moneybookers_canceled_status_id'])) {
			$this->data['moneybookers_canceled_status_id'] = $this->request->post['moneybookers_canceled_status_id'];
		} else {
			$this->data['moneybookers_canceled_status_id'] = C('moneybookers_canceled_status_id');
		}

		if (isset($this->request->post['moneybookers_failed_status_id'])) {
			$this->data['moneybookers_failed_status_id'] = $this->request->post['moneybookers_failed_status_id'];
		} else {
			$this->data['moneybookers_failed_status_id'] = C('moneybookers_failed_status_id');
		}

		if (isset($this->request->post['moneybookers_chargeback_status_id'])) {
			$this->data['moneybookers_chargeback_status_id'] = $this->request->post['moneybookers_chargeback_status_id'];
		} else {
			$this->data['moneybookers_chargeback_status_id'] = C('moneybookers_chargeback_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['moneybookers_geo_zone_id'])) {
			$this->data['moneybookers_geo_zone_id'] = $this->request->post['moneybookers_geo_zone_id'];
		} else {
			$this->data['moneybookers_geo_zone_id'] = C('moneybookers_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['moneybookers_status'])) {
			$this->data['moneybookers_status'] = $this->request->post['moneybookers_status'];
		} else {
			$this->data['moneybookers_status'] = C('moneybookers_status');
		}

		if (isset($this->request->post['moneybookers_sort_order'])) {
			$this->data['moneybookers_sort_order'] = $this->request->post['moneybookers_sort_order'];
		} else {
			$this->data['moneybookers_sort_order'] = C('moneybookers_sort_order');
		}

		if (isset($this->request->post['moneybookers_rid'])) {
			$this->data['moneybookers_rid'] = $this->request->post['moneybookers_rid'];
		} else {
			$this->data['moneybookers_rid'] = C('moneybookers_rid');
		}

		if (isset($this->request->post['moneybookers_custnote'])) {
			$this->data['moneybookers_custnote'] = $this->request->post['moneybookers_custnote'];
		} else {
			$this->data['moneybookers_custnote'] = C('moneybookers_custnote');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/moneybookers.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/moneybookers')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!$this->request->post['moneybookers_email']) {
			$this->setMessage('error_email', L('error_email'));
			return false;
		}

		return true;
	}
}
?>