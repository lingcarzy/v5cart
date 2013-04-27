<?php
class ControllerPaymentNOCHEX extends Controller {

	public function index() {
		$this->language->load('payment/nochex');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('nochex', $this->request->post);

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
			'href'      => UA('payment/nochex'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['nochex_email'])) {
			$this->data['nochex_email'] = $this->request->post['nochex_email'];
		} else {
			$this->data['nochex_email'] = C('nochex_email');
		}

		if (isset($this->request->post['nochex_account'])) {
			$this->data['nochex_account'] = $this->request->post['nochex_account'];
		} else {
			$this->data['nochex_account'] = C('nochex_account');
		}

		if (isset($this->request->post['nochex_merchant'])) {
			$this->data['nochex_merchant'] = $this->request->post['nochex_merchant'];
		} else {
			$this->data['nochex_merchant'] = C('nochex_merchant');
		}

		if (isset($this->request->post['nochex_template'])) {
			$this->data['nochex_template'] = $this->request->post['nochex_template'];
		} else {
			$this->data['nochex_template'] = C('nochex_template');
		}

		if (isset($this->request->post['nochex_test'])) {
			$this->data['nochex_test'] = $this->request->post['nochex_test'];
		} else {
			$this->data['nochex_test'] = C('nochex_test');
		}

		if (isset($this->request->post['nochex_total'])) {
			$this->data['nochex_total'] = $this->request->post['nochex_total'];
		} else {
			$this->data['nochex_total'] = C('nochex_total');
		}

		if (isset($this->request->post['nochex_order_status_id'])) {
			$this->data['nochex_order_status_id'] = $this->request->post['nochex_order_status_id'];
		} else {
			$this->data['nochex_order_status_id'] = C('nochex_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['nochex_geo_zone_id'])) {
			$this->data['nochex_geo_zone_id'] = $this->request->post['nochex_geo_zone_id'];
		} else {
			$this->data['nochex_geo_zone_id'] = C('nochex_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['nochex_status'])) {
			$this->data['nochex_status'] = $this->request->post['nochex_status'];
		} else {
			$this->data['nochex_status'] = C('nochex_status');
		}

		if (isset($this->request->post['nochex_sort_order'])) {
			$this->data['nochex_sort_order'] = $this->request->post['nochex_sort_order'];
		} else {
			$this->data['nochex_sort_order'] = C('nochex_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/nochex.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/nochex')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['nochex_email']) {
			$this->setMessage('error_email', L('error_email'));
			$pass = false;
		}

		if (!$this->request->post['nochex_merchant']) {
			$this->setMessage('error_merchant', L('error_merchant'));
			$pass = false;
		}

		return $pass;
	}
}
?>