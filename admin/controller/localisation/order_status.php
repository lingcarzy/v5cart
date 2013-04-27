<?php
class ControllerLocalisationOrderStatus extends Controller {

  	public function index() {
		$this->language->load('localisation/order_status');

		M('localisation/order_status');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('localisation/order_status');

		M('localisation/order_status');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_localisation_order_status->addOrderStatus($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/order_status'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('localisation/order_status');

		M('localisation/order_status');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_localisation_order_status->editOrderStatus($this->request->get['order_status_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/order_status'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('localisation/order_status');

		M('localisation/order_status');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $order_status_id) {
				$this->model_localisation_order_status->deleteOrderStatus($order_status_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/order_status'));
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

		$this->data['insert'] = UA('localisation/order_status/insert');
		$this->data['delete'] = UA('localisation/order_status/delete');

		$this->data['order_statuses'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$order_status_total = $this->model_localisation_order_status->getTotalOrderStatuses();

		$results = $this->model_localisation_order_status->getOrderStatuses($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/order_status/update', 'order_status_id=' . $result['order_status_id'])
			);

			$this->data['order_statuses'][] = array(
				'order_status_id' => $result['order_status_id'],
				'name'            => $result['name'] . (($result['order_status_id'] == C('config_order_status_id')) ? L('text_default') : null),
				'selected'        => isset($this->request->post['selected']) && in_array($result['order_status_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/order_status', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $order_status_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/order_status', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/order_status_list.tpl');
  	}

  	protected function getForm() {
     	$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['order_status_id'])) {
			$this->data['action'] = UA('localisation/order_status/update', 'order_status_id=' . $this->request->get['order_status_id']);
		} else {
			$this->data['action'] = UA('localisation/order_status/insert');
		}

		$this->data['cancel'] = UA('localisation/order_status');

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['order_status'])) {
			$this->data['order_status'] = $this->request->post['order_status'];
		} elseif (isset($this->request->get['order_status_id'])) {
			$this->data['order_status'] = $this->model_localisation_order_status->getOrderStatusDescriptions($this->request->get['order_status_id']);
		} else {
			$this->data['order_status'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/order_status_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$pass = true;
    	foreach ($this->request->post['order_status'] as $language_id => $value) {
      		if (!range_length($value['name'], 3, 32)) {
        		$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
      		}
    	}

  		return $pass;
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('setting/store');
		M('sale/order');

		foreach ($this->request->post['selected'] as $order_status_id) {
    		if (C('config_order_status_id') == $order_status_id) {
	  			$this->setMessage('error_warning', L('error_default'));
				return false;
			}

    		if (C('config_download_status_id') == $order_status_id) {
	  			$this->setMessage('error_warning', L('error_download'));
				return false;
			}

			$store_total = $this->model_setting_store->getTotalStoresByOrderStatusId($order_status_id);

			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}

			$order_total = $this->model_sale_order->getTotalOrderHistoriesByOrderStatusId($order_status_id);

			if ($order_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_order'), $order_total));
				return false;
			}
	  	}

		return true;
  	}
}
?>