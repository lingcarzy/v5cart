<?php
class ControllerPaymentBankTransfer extends Controller {

	public function index() {
		$this->language->load('payment/bank_transfer');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('bank_transfer', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/payment'));
		}

		$languages = C('cache_language');

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
			'href'      => UA('payment/bank_transfer'),
      		'separator' => ' :: '
   		);

		M('localisation/language');

		foreach ($languages as $language) {
			if (isset($this->request->post['bank_transfer_bank_' . $language['language_id']])) {
				$this->data['bank_transfer_bank_' . $language['language_id']] = $this->request->post['bank_transfer_bank_' . $language['language_id']];
			} else {
				$this->data['bank_transfer_bank_' . $language['language_id']] = C('bank_transfer_bank_' . $language['language_id']);
			}
		}

		$this->data['languages'] = $languages;

		if (isset($this->request->post['bank_transfer_total'])) {
			$this->data['bank_transfer_total'] = $this->request->post['bank_transfer_total'];
		} else {
			$this->data['bank_transfer_total'] = C('bank_transfer_total');
		}

		if (isset($this->request->post['bank_transfer_order_status_id'])) {
			$this->data['bank_transfer_order_status_id'] = $this->request->post['bank_transfer_order_status_id'];
		} else {
			$this->data['bank_transfer_order_status_id'] = C('bank_transfer_order_status_id');
		}

		M('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['bank_transfer_geo_zone_id'])) {
			$this->data['bank_transfer_geo_zone_id'] = $this->request->post['bank_transfer_geo_zone_id'];
		} else {
			$this->data['bank_transfer_geo_zone_id'] = C('bank_transfer_geo_zone_id');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['bank_transfer_status'])) {
			$this->data['bank_transfer_status'] = $this->request->post['bank_transfer_status'];
		} else {
			$this->data['bank_transfer_status'] = C('bank_transfer_status');
		}

		if (isset($this->request->post['bank_transfer_sort_order'])) {
			$this->data['bank_transfer_sort_order'] = $this->request->post['bank_transfer_sort_order'];
		} else {
			$this->data['bank_transfer_sort_order'] = C('bank_transfer_sort_order');
		}


		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('payment/bank_transfer.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bank_transfer')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$languages = C('cache_language');

		$pass = true;

		foreach ($languages as $language) {
			if (!$this->request->post['bank_transfer_bank_' . $language['language_id']]) {
				$this->setMessage('error_bank_' .  $language['language_id'], L('error_bank'));
				$pass = false;
			}
		}

		return $pass;
	}
}
?>