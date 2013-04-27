<?php

class ControllerLocalisationCarrier extends Controller {
	public function index() {
		$this->language->load('localisation/carrier');
		$this->document->setTitle(L('heading_title'));

		M('localisation/carrier', 'carrier');

		$this->data['success'] = $this->session->flashdata('success');

		$this->data['carriers'] = $this->carrier->getCarriers();
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('localisation/carrier_list.tpl');
	}

	public function insert() {
		$this->language->load('localisation/carrier');
		$this->document->setTitle(L('heading_title'));

		if ($this->request->isPost() && $this->validateForm()) {

			M('localisation/carrier', 'carrier');
			$this->carrier->addCarrier($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/carrier'));
		}

		$this->data['action'] = UA('localisation/carrier/insert');

		$this->data['code'] = '';
		$this->data['name'] = '';
		$this->data['tracking_link'] = '';
		$this->data['description'] = '';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('localisation/carrier_form.tpl');
	}

	public function update() {
		$this->language->load('localisation/carrier');

		M('localisation/carrier', 'carrier');

		$carrier_id = $this->request->get['carrier_id'];
		if ($this->request->isPost() && $this->validateForm()) {

			$this->carrier->editCarrier($carrier_id, $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/carrier'));
		}

		$this->document->setTitle(L('heading_title'));

		$this->data['action'] = UA('localisation/carrier/update', "carrier_id=$carrier_id");

		$carrier = $this->carrier->getCarrier($carrier_id);
		$this->data['code'] = $carrier['code'];
		$this->data['name'] = $carrier['name'];
		$this->data['tracking_link'] = $carrier['tracking_link'];
		$this->data['description'] = $carrier['description'];

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('localisation/carrier_form.tpl');
	}

	public function delete() {
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			M('localisation/carrier', 'carrier');
			$this->language->load('localisation/carrier');
			$this->carrier->deleteCarrier($this->request->post['selected']);

			$this->session->set_flashdata('success', L('text_success'));
		}
		$this->redirect(UA('localisation/carrier'));
	}

	public function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/carrier')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('code', L('text_code'), 'required');
		$this->form_validation->set_rules('name', L('text_name'), 'required');
		return $this->form_validation->run();
	}

	public function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/carrier')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}