<?php
class ControllerPaymentPPPro extends Controller {

	public function index() {
		$this->language->load('payment/pp_pro');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro', $this->request->post);

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
			'href'      => UA('payment/pp_pro'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['pp_pro_username'])) {
			$this->data['pp_pro_username'] = $this->request->post['pp_pro_username'];
		} else {
			$this->data['pp_pro_username'] = C('pp_pro_username');
		}

		if (isset($this->request->post['pp_pro_password'])) {
			$this->data['pp_pro_password'] = $this->request->post['pp_pro_password'];
		} else {
			$this->data['pp_pro_password'] = C('pp_pro_password');
		}

		if (isset($this->request->post['pp_pro_signature'])) {
			$this->data['pp_pro_signature'] = $this->request->post['pp_pro_signature'];
		} else {
			$this->data['pp_pro_signature'] = C('pp_pro_signature');
		}

		if (isset($this->request->post['pp_pro_test'])) {
			$this->data['pp_pro_test'] = $this->request->post['pp_pro_test'];
		} else {
			$this->data['pp_pro_test'] = C('pp_pro_test');
		}

		if (isset($this->request->post['pp_pro_method'])) {
			$this->data['pp_pro_transaction'] = $this->request->post['pp_pro_transaction'];
		} else {
			$this->data['pp_pro_transaction'] = C('pp_pro_transaction');
		}

		if (isset($this->request->post['pp_pro_total'])) {
			$this->data['pp_pro_total'] = $this->request->post['pp_pro_total'];
		} else {
			$this->data['pp_pro_total'] = C('pp_pro_total');
		}

		if (isset($this->request->post['pp_pro_order_status_id'])) {
			$this->data['pp_pro_order_status_id'] = $this->request->post['pp_pro_order_status_id'];
		} else {
			$this->data['pp_pro_order_status_id'] = C('pp_pro_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_geo_zone_id'])) {
			$this->data['pp_pro_geo_zone_id'] = $this->request->post['pp_pro_geo_zone_id'];
		} else {
			$this->data['pp_pro_geo_zone_id'] = C('pp_pro_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_status'])) {
			$this->data['pp_pro_status'] = $this->request->post['pp_pro_status'];
		} else {
			$this->data['pp_pro_status'] = C('pp_pro_status');
		}

		if (isset($this->request->post['pp_pro_sort_order'])) {
			$this->data['pp_pro_sort_order'] = $this->request->post['pp_pro_sort_order'];
		} else {
			$this->data['pp_pro_sort_order'] = C('pp_pro_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/pp_pro.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!$this->request->post['pp_pro_username']) {
			$this->setMessage('error_username', L('error_username'));
			$pass = false;
		}

		if (!$this->request->post['pp_pro_password']) {
			$this->setMessage('error_password', L('error_password'));
			$pass = false;
		}

		if (!$this->request->post['pp_pro_signature']) {
			$this->setMessage('error_signature', L('error_signature'));
			$pass = false;
		}

		return $pass;
	}
}
?>