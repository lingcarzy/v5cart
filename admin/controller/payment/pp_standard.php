<?php
class ControllerPaymentPPStandard extends Controller {

	public function index() {
		$this->language->load('payment/pp_standard');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_standard', $this->request->post);

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
			'href'      => UA('payment/pp_standard'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['pp_standard_email'])) {
			$this->data['pp_standard_email'] = $this->request->post['pp_standard_email'];
		} else {
			$this->data['pp_standard_email'] = C('pp_standard_email');
		}

		if (isset($this->request->post['pp_standard_test'])) {
			$this->data['pp_standard_test'] = $this->request->post['pp_standard_test'];
		} else {
			$this->data['pp_standard_test'] = C('pp_standard_test');
		}

		if (isset($this->request->post['pp_standard_transaction'])) {
			$this->data['pp_standard_transaction'] = $this->request->post['pp_standard_transaction'];
		} else {
			$this->data['pp_standard_transaction'] = C('pp_standard_transaction');
		}

		if (isset($this->request->post['pp_standard_debug'])) {
			$this->data['pp_standard_debug'] = $this->request->post['pp_standard_debug'];
		} else {
			$this->data['pp_standard_debug'] = C('pp_standard_debug');
		}

		if (isset($this->request->post['pp_standard_total'])) {
			$this->data['pp_standard_total'] = $this->request->post['pp_standard_total'];
		} else {
			$this->data['pp_standard_total'] = C('pp_standard_total');
		}

		if (isset($this->request->post['pp_standard_canceled_reversal_status_id'])) {
			$this->data['pp_standard_canceled_reversal_status_id'] = $this->request->post['pp_standard_canceled_reversal_status_id'];
		} else {
			$this->data['pp_standard_canceled_reversal_status_id'] = C('pp_standard_canceled_reversal_status_id');
		}

		if (isset($this->request->post['pp_standard_completed_status_id'])) {
			$this->data['pp_standard_completed_status_id'] = $this->request->post['pp_standard_completed_status_id'];
		} else {
			$this->data['pp_standard_completed_status_id'] = C('pp_standard_completed_status_id');
		}

		if (isset($this->request->post['pp_standard_denied_status_id'])) {
			$this->data['pp_standard_denied_status_id'] = $this->request->post['pp_standard_denied_status_id'];
		} else {
			$this->data['pp_standard_denied_status_id'] = C('pp_standard_denied_status_id');
		}

		if (isset($this->request->post['pp_standard_expired_status_id'])) {
			$this->data['pp_standard_expired_status_id'] = $this->request->post['pp_standard_expired_status_id'];
		} else {
			$this->data['pp_standard_expired_status_id'] = C('pp_standard_expired_status_id');
		}

		if (isset($this->request->post['pp_standard_failed_status_id'])) {
			$this->data['pp_standard_failed_status_id'] = $this->request->post['pp_standard_failed_status_id'];
		} else {
			$this->data['pp_standard_failed_status_id'] = C('pp_standard_failed_status_id');
		}

		if (isset($this->request->post['pp_standard_pending_status_id'])) {
			$this->data['pp_standard_pending_status_id'] = $this->request->post['pp_standard_pending_status_id'];
		} else {
			$this->data['pp_standard_pending_status_id'] = C('pp_standard_pending_status_id');
		}

		if (isset($this->request->post['pp_standard_processed_status_id'])) {
			$this->data['pp_standard_processed_status_id'] = $this->request->post['pp_standard_processed_status_id'];
		} else {
			$this->data['pp_standard_processed_status_id'] = C('pp_standard_processed_status_id');
		}

		if (isset($this->request->post['pp_standard_refunded_status_id'])) {
			$this->data['pp_standard_refunded_status_id'] = $this->request->post['pp_standard_refunded_status_id'];
		} else {
			$this->data['pp_standard_refunded_status_id'] = C('pp_standard_refunded_status_id');
		}

		if (isset($this->request->post['pp_standard_reversed_status_id'])) {
			$this->data['pp_standard_reversed_status_id'] = $this->request->post['pp_standard_reversed_status_id'];
		} else {
			$this->data['pp_standard_reversed_status_id'] = C('pp_standard_reversed_status_id');
		}

		if (isset($this->request->post['pp_standard_voided_status_id'])) {
			$this->data['pp_standard_voided_status_id'] = $this->request->post['pp_standard_voided_status_id'];
		} else {
			$this->data['pp_standard_voided_status_id'] = C('pp_standard_voided_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_standard_geo_zone_id'])) {
			$this->data['pp_standard_geo_zone_id'] = $this->request->post['pp_standard_geo_zone_id'];
		} else {
			$this->data['pp_standard_geo_zone_id'] = C('pp_standard_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_standard_status'])) {
			$this->data['pp_standard_status'] = $this->request->post['pp_standard_status'];
		} else {
			$this->data['pp_standard_status'] = C('pp_standard_status');
		}

		if (isset($this->request->post['pp_standard_sort_order'])) {
			$this->data['pp_standard_sort_order'] = $this->request->post['pp_standard_sort_order'];
		} else {
			$this->data['pp_standard_sort_order'] = C('pp_standard_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/pp_standard.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_standard')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!$this->request->post['pp_standard_email']) {
			$this->setMessage('error_email', L('error_email'));
		}

		return true;
	}
}
?>