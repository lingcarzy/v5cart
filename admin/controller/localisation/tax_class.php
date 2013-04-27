<?php
class ControllerLocalisationTaxClass extends Controller {

	public function index() {
		$this->language->load('localisation/tax_class');

		M('localisation/tax_class');

		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/tax_class');

		M('localisation/tax_class');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_tax_class->addTaxClass($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/tax_class'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/tax_class');

		M('localisation/tax_class');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_tax_class->editTaxClass($this->request->get['tax_class_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/tax_class'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/tax_class');

		M('localisation/tax_class');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tax_class_id) {
				$this->model_localisation_tax_class->deleteTaxClass($tax_class_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/tax_class'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$sort = $qf->get('sort', 'title');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['insert'] = UA('localisation/tax_class/insert');
		$this->data['delete'] = UA('localisation/tax_class/delete');

		$this->data['tax_classes'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$tax_class_total = $this->model_localisation_tax_class->getTotalTaxClasses();

		$results = $this->model_localisation_tax_class->getTaxClasses($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/tax_class/update', 'tax_class_id=' . $result['tax_class_id'])
			);

			$this->data['tax_classes'][] = array(
				'tax_class_id' => $result['tax_class_id'],
				'title'        => $result['title'],
				'selected'     => isset($this->request->post['selected']) && in_array($result['tax_class_id'], $this->request->post['selected']),
				'action'       => $action
			);
		}


		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_title'] = UA('localisation/tax_class', 'sort=title&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $tax_class_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/tax_class', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('localisation/tax_class_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['tax_class_id'])) {
			$this->data['action'] = UA('localisation/tax_class/update', 'tax_class_id=' . $this->request->get['tax_class_id']);
		} else {
			$this->data['action'] = UA('localisation/tax_class/insert');
		}

		if (isset($this->request->get['tax_class_id']) && !$this->request->isPost()) {
			$tax_class_info = $this->model_localisation_tax_class->getTaxClass($this->request->get['tax_class_id']);
		}

		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (!empty($tax_class_info)) {
			$this->data['title'] = $tax_class_info['title'];
		} else {
			$this->data['title'] = '';
		}

		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (!empty($tax_class_info)) {
			$this->data['description'] = $tax_class_info['description'];
		} else {
			$this->data['description'] = '';
		}

		M('localisation/tax_rate');

		$this->data['tax_rates'] = $this->model_localisation_tax_rate->getTaxRates();

		if (isset($this->request->post['tax_rule'])) {
			$this->data['tax_rules'] = $this->request->post['tax_rule'];
		} elseif (isset($this->request->get['tax_class_id'])) {
			$this->data['tax_rules'] = $this->model_localisation_tax_class->getTaxRules($this->request->get['tax_class_id']);
		} else {
			$this->data['tax_rules'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('localisation/tax_class_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		if (!range_length($this->request->post['title'], 3, 32)) {
			$this->setMessage('error_title', L('error_title'));
			$pass = false;
		}

		if (!range_length($this->request->post['description'], 3, 255)) {
			$this->setMessage('error_description', L('error_description'));
			$pass = false;
		}

		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('catalog/product');

		foreach ($this->request->post['selected'] as $tax_class_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByTaxClassId($tax_class_id);

			if ($product_total) {
				$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}
		}

		return true;
	}
}
?>