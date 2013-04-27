<?php
class ControllerPaymentSagepay extends Controller {

	public function index() {
		$this->language->load('payment/sagepay');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay', $this->request->post);

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
			'href'      => UA('payment/sagepay'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['sagepay_vendor'])) {
			$this->data['sagepay_vendor'] = $this->request->post['sagepay_vendor'];
		} else {
			$this->data['sagepay_vendor'] = C('sagepay_vendor');
		}

		if (isset($this->request->post['sagepay_password'])) {
			$this->data['sagepay_password'] = $this->request->post['sagepay_password'];
		} else {
			$this->data['sagepay_password'] = C('sagepay_password');
		}

		if (isset($this->request->post['sagepay_test'])) {
			$this->data['sagepay_test'] = $this->request->post['sagepay_test'];
		} else {
			$this->data['sagepay_test'] = C('sagepay_test');
		}

		if (isset($this->request->post['sagepay_transaction'])) {
			$this->data['sagepay_transaction'] = $this->request->post['sagepay_transaction'];
		} else {
			$this->data['sagepay_transaction'] = C('sagepay_transaction');
		}

		if (isset($this->request->post['sagepay_total'])) {
			$this->data['sagepay_total'] = $this->request->post['sagepay_total'];
		} else {
			$this->data['sagepay_total'] = C('sagepay_total');
		}

		if (isset($this->request->post['sagepay_order_status_id'])) {
			$this->data['sagepay_order_status_id'] = $this->request->post['sagepay_order_status_id'];
		} else {
			$this->data['sagepay_order_status_id'] = C('sagepay_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sagepay_geo_zone_id'])) {
			$this->data['sagepay_geo_zone_id'] = $this->request->post['sagepay_geo_zone_id'];
		} else {
			$this->data['sagepay_geo_zone_id'] = C('sagepay_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sagepay_status'])) {
			$this->data['sagepay_status'] = $this->request->post['sagepay_status'];
		} else {
			$this->data['sagepay_status'] = C('sagepay_status');
		}

		if (isset($this->request->post['sagepay_sort_order'])) {
			$this->data['sagepay_sort_order'] = $this->request->post['sagepay_sort_order'];
		} else {
			$this->data['sagepay_sort_order'] = C('sagepay_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/sagepay.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['sagepay_vendor']) {
			$this->setMessage('error_vendor', L('error_vendor'));
			$pass = false;
		}

		if (!$this->request->post['sagepay_password']) {
			$this->setMessage('error_password', L('error_password'));
			$pass = false;
		}

		return $pass;
	}
}
?>