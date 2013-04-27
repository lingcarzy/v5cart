<?php
class ControllerShippingFree extends Controller {

	public function index() {
		$this->language->load('shipping/free');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('free', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/shipping'));
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_shipping'),
			'href'      => UA('extension/shipping'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('shipping/free'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['free_total'])) {
			$this->data['free_total'] = $this->request->post['free_total'];
		} else {
			$this->data['free_total'] = C('free_total');
		}

		if (isset($this->request->post['free_geo_zone_id'])) {
			$this->data['free_geo_zone_id'] = $this->request->post['free_geo_zone_id'];
		} else {
			$this->data['free_geo_zone_id'] = C('free_geo_zone_id');
		}

		if (isset($this->request->post['free_status'])) {
			$this->data['free_status'] = $this->request->post['free_status'];
		} else {
			$this->data['free_status'] = C('free_status');
		}

		if (isset($this->request->post['free_sort_order'])) {
			$this->data['free_sort_order'] = $this->request->post['free_sort_order'];
		} else {
			$this->data['free_sort_order'] = C('free_sort_order');
		}

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('shipping/free.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/free')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		return true;
	}
}
?>