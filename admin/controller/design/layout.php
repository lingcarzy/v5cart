<?php
class ControllerDesignLayout extends Controller {

	public function index() {
		$this->language->load('design/layout');
		M('design/layout');
		$this->getList();
	}

	public function insert() {
		$this->language->load('design/layout');
		M('design/layout');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_design_layout->addLayout($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('design/layout'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('design/layout');
		M('design/layout');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_design_layout->editLayout($this->request->get['layout_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('design/layout'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('design/layout');

		M('design/layout');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $layout_id) {
				$this->model_design_layout->deleteLayout($layout_id);
			}

			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(UA('design/layout'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$sort = $qf->get('sort', 'name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['layouts'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$layout_total = $this->model_design_layout->getTotalLayouts();

		$results = $this->model_design_layout->getLayouts($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('design/layout/update', 'layout_id=' . $result['layout_id'])
			);

			$this->data['layouts'][] = array(
				'layout_id' => $result['layout_id'],
				'name'      => $result['name'],
				'selected'  => isset($this->request->post['selected']) && in_array($result['layout_id'], $this->request->post['selected']),
				'action'    => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('design/layout', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $layout_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('design/layout', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('design/layout_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

 		$layout_id = $this->request->get('layout_id');
		if ($layout_id) {
			$this->data['action'] = UA('design/layout/update', 'layout_id=' . $layout_id);
		} else {
			$this->data['action'] = UA('design/layout/insert');
		}

		if ($layout_id && !$this->request->isPost()) {
			$layout_info = $this->model_design_layout->getLayout($layout_id);
		}

		if(!empty($layout_info)) {
			$this->data['name'] = $layout_info['name'];
		} else {
			$this->data['name'] = P('name');
		}

		M('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		//Layout route
		if (isset($this->request->post['layout_route'])) {
			$this->data['layout_routes'] = $this->request->post['layout_route'];
		} elseif ($layout_id) {
			$this->data['layout_routes'] = $this->model_design_layout->getLayoutRoutes($layout_id);
		} else {
			$this->data['layout_routes'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('design/layout_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (!range_length($this->request->post['name'], 3, 64)) {
			$this->setMessage('error_name', L('error_name'));
			return false;
		}

		return true;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		M('catalog/product');
		M('catalog/category');
		M('catalog/page');

		foreach ($this->request->post['selected'] as $layout_id) {
			if (C('config_layout_id') == $layout_id) {
				$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$store_total = $this->model_setting_store->getTotalStoresByLayoutId($layout_id);

			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}

			$product_total = $this->model_catalog_product->getTotalProductsByLayoutId($layout_id);

			if ($product_total) {
				$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}

			$category_total = $this->model_catalog_category->getTotalCategoriesByLayoutId($layout_id);

			if ($category_total) {
				$this->setMessage('error_warning', sprintf(L('error_category'), $category_total));
				return false;
			}

			$page_total = $this->model_catalog_page->getTotalPagesByLayoutId($layout_id);

			if ($page_total) {
				$this->setMessage('error_warning', sprintf(L('error_page'), $page_total));
				return false;
			}
		}

		return true;
	}
}
?>