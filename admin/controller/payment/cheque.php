<?php
class ControllerPaymentCheque extends Controller {

	public function index() {
		$this->language->load('payment/cheque');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('cheque', $this->request->post);

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
			'href'      => UA('payment/cheque'),
      		'separator' => ' :: '
   		);


		if ($this->request->isPost()) {
			$this->data['cheque_payable'] = $this->request->post['cheque_payable'];
			$this->data['cheque_total'] = $this->request->post['cheque_total'];
			$this->data['cheque_order_status_id'] = $this->request->post['cheque_order_status_id'];
			$this->data['cheque_geo_zone_id'] = $this->request->post['cheque_geo_zone_id'];
			$this->data['cheque_status'] = $this->request->post['cheque_status'];
			$this->data['cheque_sort_order'] = $this->request->post['cheque_sort_order'];
		} else {
			$this->data['cheque_payable'] = C('cheque_payable');
			$this->data['cheque_total'] = C('cheque_total');
			$this->data['cheque_order_status_id'] = C('cheque_order_status_id');
			$this->data['cheque_geo_zone_id'] = C('cheque_geo_zone_id');
			$this->data['cheque_status'] = C('cheque_status');
			$this->data['cheque_sort_order'] = C('cheque_sort_order');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/cheque.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cheque')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!$this->request->post['cheque_payable']) {
			$this->setMessage('error_payable', L('error_payable'));
			return false;
		}

		return true;
	}
}
?>