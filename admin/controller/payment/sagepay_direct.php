<?php
class ControllerPaymentSagepayDirect extends Controller {

	public function index() {
		$this->language->load('payment/sagepay_direct');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay_direct', $this->request->post);

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
			'href'      => UA('payment/sagepay_direct'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['sagepay_direct_vendor'])) {
			$this->data['sagepay_direct_vendor'] = $this->request->post['sagepay_direct_vendor'];
		} else {
			$this->data['sagepay_direct_vendor'] = C('sagepay_direct_vendor');
		}

		if (isset($this->request->post['sagepay_direct_password'])) {
			$this->data['sagepay_direct_password'] = $this->request->post['sagepay_direct_password'];
		} else {
			$this->data['sagepay_direct_password'] = C('sagepay_direct_password');
		}


		if (isset($this->request->post['sagepay_direct_test'])) {
			$this->data['sagepay_direct_test'] = $this->request->post['sagepay_direct_test'];
		} else {
			$this->data['sagepay_direct_test'] = C('sagepay_direct_test');
		}

		if (isset($this->request->post['sagepay_direct_transaction'])) {
			$this->data['sagepay_direct_transaction'] = $this->request->post['sagepay_direct_transaction'];
		} else {
			$this->data['sagepay_direct_transaction'] = C('sagepay_direct_transaction');
		}

		if (isset($this->request->post['sagepay_direct_total'])) {
			$this->data['sagepay_direct_total'] = $this->request->post['sagepay_direct_total'];
		} else {
			$this->data['sagepay_direct_total'] = C('sagepay_direct_total');
		}

		if (isset($this->request->post['sagepay_direct_order_status_id'])) {
			$this->data['sagepay_direct_order_status_id'] = $this->request->post['sagepay_direct_order_status_id'];
		} else {
			$this->data['sagepay_direct_order_status_id'] = C('sagepay_direct_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sagepay_direct_geo_zone_id'])) {
			$this->data['sagepay_direct_geo_zone_id'] = $this->request->post['sagepay_direct_geo_zone_id'];
		} else {
			$this->data['sagepay_direct_geo_zone_id'] = C('sagepay_direct_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sagepay_direct_status'])) {
			$this->data['sagepay_direct_status'] = $this->request->post['sagepay_direct_status'];
		} else {
			$this->data['sagepay_direct_status'] = C('sagepay_direct_status');
		}

		if (isset($this->request->post['sagepay_direct_sort_order'])) {
			$this->data['sagepay_direct_sort_order'] = $this->request->post['sagepay_direct_sort_order'];
		} else {
			$this->data['sagepay_direct_sort_order'] = C('sagepay_direct_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/sagepay_direct.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay_direct')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!$this->request->post['sagepay_direct_vendor']) {
			$this->setMessage('error_vendor', L('error_vendor'));
			return false;
		}

		return true;
	}
}
?>