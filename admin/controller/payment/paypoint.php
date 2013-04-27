<?php
class ControllerPaymentPayPoint extends Controller {

	public function index() {
		$this->language->load('payment/paypoint');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('paypoint', $this->request->post);

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
			'href'      => UA('payment/paypoint'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['paypoint_merchant'])) {
			$this->data['paypoint_merchant'] = $this->request->post['paypoint_merchant'];
		} else {
			$this->data['paypoint_merchant'] = C('paypoint_merchant');
		}

		if (isset($this->request->post['paypoint_password'])) {
			$this->data['paypoint_password'] = $this->request->post['paypoint_password'];
		} else {
			$this->data['paypoint_password'] = C('paypoint_password');
		}

		if (isset($this->request->post['paypoint_test'])) {
			$this->data['paypoint_test'] = $this->request->post['paypoint_test'];
		} else {
			$this->data['paypoint_test'] = C('paypoint_test');
		}

		if (isset($this->request->post['paypoint_total'])) {
			$this->data['paypoint_total'] = $this->request->post['paypoint_total'];
		} else {
			$this->data['paypoint_total'] = C('paypoint_total');
		}

		if (isset($this->request->post['paypoint_order_status_id'])) {
			$this->data['paypoint_order_status_id'] = $this->request->post['paypoint_order_status_id'];
		} else {
			$this->data['paypoint_order_status_id'] = C('paypoint_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paypoint_geo_zone_id'])) {
			$this->data['paypoint_geo_zone_id'] = $this->request->post['paypoint_geo_zone_id'];
		} else {
			$this->data['paypoint_geo_zone_id'] = C('paypoint_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paypoint_status'])) {
			$this->data['paypoint_status'] = $this->request->post['paypoint_status'];
		} else {
			$this->data['paypoint_status'] = C('paypoint_status');
		}

		if (isset($this->request->post['paypoint_sort_order'])) {
			$this->data['paypoint_sort_order'] = $this->request->post['paypoint_sort_order'];
		} else {
			$this->data['paypoint_sort_order'] = C('paypoint_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/paypoint.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paypoint')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!$this->request->post['paypoint_merchant']) {
			$this->setMessage('error_merchant', L('error_merchant'));
			return false;
		}

		return true;
	}
}
?>