<?php
class ControllerPaymentPPProUK extends Controller {

	public function index() {
		$this->language->load('payment/pp_pro_uk');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro_uk', $this->request->post);

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
			'href'      => UA('payment/pp_pro_uk'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['pp_pro_uk_vendor'])) {
			$this->data['pp_pro_uk_vendor'] = $this->request->post['pp_pro_uk_vendor'];
		} else {
			$this->data['pp_pro_uk_vendor'] = C('pp_pro_uk_vendor');
		}

		if (isset($this->request->post['pp_pro_uk_user'])) {
			$this->data['pp_pro_uk_user'] = $this->request->post['pp_pro_uk_user'];
		} else {
			$this->data['pp_pro_uk_user'] = C('pp_pro_uk_user');
		}

		if (isset($this->request->post['pp_pro_uk_password'])) {
			$this->data['pp_pro_uk_password'] = $this->request->post['pp_pro_uk_password'];
		} else {
			$this->data['pp_pro_uk_password'] = C('pp_pro_uk_password');
		}

		if (isset($this->request->post['pp_pro_uk_partner'])) {
			$this->data['pp_pro_uk_partner'] = $this->request->post['pp_pro_uk_partner'];
		} elseif ($this->config->has('pp_pro_uk_partner')) {
			$this->data['pp_pro_uk_partner'] = C('pp_pro_uk_partner');
		} else {
			$this->data['pp_pro_uk_partner'] = 'PayPal';
		}

		if (isset($this->request->post['pp_pro_uk_test'])) {
			$this->data['pp_pro_uk_test'] = $this->request->post['pp_pro_uk_test'];
		} else {
			$this->data['pp_pro_uk_test'] = C('pp_pro_uk_test');
		}

		if (isset($this->request->post['pp_pro_uk_method'])) {
			$this->data['pp_pro_uk_transaction'] = $this->request->post['pp_pro_uk_transaction'];
		} else {
			$this->data['pp_pro_uk_transaction'] = C('pp_pro_uk_transaction');
		}

		if (isset($this->request->post['pp_pro_uk_total'])) {
			$this->data['pp_pro_uk_total'] = $this->request->post['pp_pro_uk_total'];
		} else {
			$this->data['pp_pro_uk_total'] = C('pp_pro_uk_total');
		}

		if (isset($this->request->post['pp_pro_uk_order_status_id'])) {
			$this->data['pp_pro_uk_order_status_id'] = $this->request->post['pp_pro_uk_order_status_id'];
		} else {
			$this->data['pp_pro_uk_order_status_id'] = C('pp_pro_uk_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_uk_geo_zone_id'])) {
			$this->data['pp_pro_uk_geo_zone_id'] = $this->request->post['pp_pro_uk_geo_zone_id'];
		} else {
			$this->data['pp_pro_uk_geo_zone_id'] = C('pp_pro_uk_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_uk_status'])) {
			$this->data['pp_pro_uk_status'] = $this->request->post['pp_pro_uk_status'];
		} else {
			$this->data['pp_pro_uk_status'] = C('pp_pro_uk_status');
		}

		if (isset($this->request->post['pp_pro_uk_sort_order'])) {
			$this->data['pp_pro_uk_sort_order'] = $this->request->post['pp_pro_uk_sort_order'];
		} else {
			$this->data['pp_pro_uk_sort_order'] = C('pp_pro_uk_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/pp_pro_uk.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro_uk')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$this->load->library('form_validation', true);

		$this->form_validation->set_rules('pp_pro_uk_vendor', '', 'required', L('error_vendor'));
		$this->form_validation->set_rules('pp_pro_uk_user', '', 'required', L('error_user'));
		$this->form_validation->set_rules('pp_pro_uk_password', '', 'required', L('error_password'));
		$this->form_validation->set_rules('pp_pro_uk_partner', '', 'required', L('error_partner'));

		return $this->form_validation->run();
	}
}
?>