<?php
class ControllerLocalisationStockStatus extends Controller {

  	public function index() {
		$this->language->load('localisation/stock_status');

		M('localisation/stock_status');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('localisation/stock_status');

		M('localisation/stock_status');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_localisation_stock_status->addStockStatus($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/stock_status'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('localisation/stock_status');

		M('localisation/stock_status');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_localisation_stock_status->editStockStatus($this->request->get['stock_status_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/stock_status'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('localisation/stock_status');

		M('localisation/stock_status');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $stock_status_id) {
				$this->model_localisation_stock_status->deleteStockStatus($stock_status_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/stock_status'));
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

		$this->data['insert'] = UA('localisation/stock_status/insert');
		$this->data['delete'] = UA('localisation/stock_status/delete');

		$this->data['stock_statuses'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$stock_status_total = $this->model_localisation_stock_status->getTotalStockStatuses();

		$results = $this->model_localisation_stock_status->getStockStatuses($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/stock_status/update', 'stock_status_id=' . $result['stock_status_id'])
			);

			$this->data['stock_statuses'][] = array(
				'stock_status_id' => $result['stock_status_id'],
				'name'            => $result['name'] . (($result['stock_status_id'] == C('config_stock_status_id')) ? L('text_default') : null),
				'selected'        => isset($this->request->post['selected']) && in_array($result['stock_status_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/stock_status', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $stock_status_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/stock_status', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/stock_status_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['stock_status_id'])) {
			$this->data['action'] = UA('localisation/stock_status/update', 'stock_status_id=' . $this->request->get['stock_status_id']);
		} else {
			$this->data['action'] = UA('localisation/stock_status/insert');
		}

		$this->data['cancel'] = UA('localisation/stock_status');

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['stock_status'])) {
			$this->data['stock_status'] = $this->request->post['stock_status'];
		} elseif (isset($this->request->get['stock_status_id'])) {
			$this->data['stock_status'] = $this->model_localisation_stock_status->getStockStatusDescriptions($this->request->get['stock_status_id']);
		} else {
			$this->data['stock_status'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/stock_status_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/stock_status')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$pass = true;

    	foreach ($this->request->post['stock_status'] as $language_id => $value) {
      		if (!range_length($value['name'], 3, 32)) {
        		$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
      		}
    	}

		return $pass;
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/stock_status')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('setting/store');
		M('catalog/product');

		foreach ($this->request->post['selected'] as $stock_status_id) {
			if (C('config_stock_status_id') == $stock_status_id) {
				$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$product_total = $this->model_catalog_product->getTotalProductsByStockStatusId($stock_status_id);

			if ($product_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}
	  	}

		return true;
  	}
}
?>