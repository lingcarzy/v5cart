<?php
class ControllerShippingItem extends Controller {

	public function index() {
		$this->language->load('shipping/item');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('item', $this->request->post);

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
			'href'      => UA('shipping/item'),
      		'separator' => ' :: '
   		);

		if (isset($this->request->post['item_cost'])) {
			$this->data['item_cost'] = $this->request->post['item_cost'];
		} else {
			$this->data['item_cost'] = C('item_cost');
		}

		if (isset($this->request->post['item_tax_class_id'])) {
			$this->data['item_tax_class_id'] = $this->request->post['item_tax_class_id'];
		} else {
			$this->data['item_tax_class_id'] = C('item_tax_class_id');
		}

		if (isset($this->request->post['item_geo_zone_id'])) {
			$this->data['item_geo_zone_id'] = $this->request->post['item_geo_zone_id'];
		} else {
			$this->data['item_geo_zone_id'] = C('item_geo_zone_id');
		}

		if (isset($this->request->post['item_status'])) {
			$this->data['item_status'] = $this->request->post['item_status'];
		} else {
			$this->data['item_status'] = C('item_status');
		}

		if (isset($this->request->post['item_sort_order'])) {
			$this->data['item_sort_order'] = $this->request->post['item_sort_order'];
		} else {
			$this->data['item_sort_order'] = C('item_sort_order');
		}

		M('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		M('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('shipping/item.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/item')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		return true;
	}
}
?>