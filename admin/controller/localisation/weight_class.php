<?php
class ControllerLocalisationWeightClass extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('localisation/weight_class');

		M('localisation/weight_class');

		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/weight_class');

		M('localisation/weight_class');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_weight_class->addWeightClass($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/weight_class'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/weight_class');

		M('localisation/weight_class');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_weight_class->editWeightClass($this->request->get['weight_class_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/weight_class'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/weight_class');

		M('localisation/weight_class');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $weight_class_id) {
				$this->model_localisation_weight_class->deleteWeightClass($weight_class_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/weight_class'));
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

		$this->data['weight_classes'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$weight_class_total = $this->model_localisation_weight_class->getTotalWeightClasses();

		$results = $this->model_localisation_weight_class->getWeightClasses($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/weight_class/update', 'weight_class_id=' . $result['weight_class_id'])
			);

			$this->data['weight_classes'][] = array(
				'weight_class_id' => $result['weight_class_id'],
				'title'           => $result['title'] . (($result['unit'] == C('config_weight_class')) ? L('text_default') : null),
				'unit'            => $result['unit'],
				'value'           => $result['value'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['weight_class_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');


		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_title'] = UA('localisation/weight_class', 'sort=title&order=' . $url);
		$this->data['sort_unit'] = UA('localisation/weight_class', 'sort=unit&order=' . $url);
		$this->data['sort_value'] = UA('localisation/weight_class', 'sort=value&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $weight_class_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/weight_class', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/weight_class_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['weight_class_id'])) {
			$this->data['action'] = UA('localisation/weight_class/update', 'weight_class_id=' . $this->request->get['weight_class_id']);
		} else {
			$this->data['action'] = UA('localisation/weight_class/insert');
		}

		if (isset($this->request->get['weight_class_id']) && !$this->request->isPost()) {
      		$weight_class_info = $this->model_localisation_weight_class->getWeightClass($this->request->get['weight_class_id']);
    	}

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['weight_class_description'])) {
			$this->data['weight_class_description'] = $this->request->post['weight_class_description'];
		} elseif (isset($this->request->get['weight_class_id'])) {
			$this->data['weight_class_description'] = $this->model_localisation_weight_class->getWeightClassDescriptions($this->request->get['weight_class_id']);
		} else {
			$this->data['weight_class_description'] = array();
		}

		if (isset($this->request->post['value'])) {
			$this->data['value'] = $this->request->post['value'];
		} elseif (!empty($weight_class_info)) {
			$this->data['value'] = $weight_class_info['value'];
		} else {
			$this->data['value'] = '';
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/weight_class_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		$pass = true;

		foreach ($this->request->post['weight_class_description'] as $language_id => $value) {
			if (!range_length($value['title'],  3, 32)) {
				$this->setMessage('error_title_'.$language_id, L('error_title'));
				$pass = false;
			}

			if (!$value['unit'] || (utf8_strlen($value['unit']) > 4)) {
				$this->setMessage('error_unit_'.$language_id, L('error_unit'));
				$pass = false;
			}
		}

		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('catalog/product');

		foreach ($this->request->post['selected'] as $weight_class_id) {
			if (C('config_weight_class_id') == $weight_class_id) {
				$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$product_total = $this->model_catalog_product->getTotalProductsByWeightClassId($weight_class_id);

			if ($product_total) {
				$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}
		}

		return true;
	}
}
?>