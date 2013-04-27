<?php
class ControllerLocalisationReturnStatus extends Controller {

  	public function index() {
		$this->language->load('localisation/return_status');

    	M('localisation/return_status');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('localisation/return_status');

		M('localisation/return_status');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_localisation_return_status->addReturnStatus($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/return_status'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('localisation/return_status');

		M('localisation/return_status');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_localisation_return_status->editReturnStatus($this->request->get['return_status_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/return_status'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('localisation/return_status');

		M('localisation/return_status');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $return_status_id) {
				$this->model_localisation_return_status->deleteReturnStatus($return_status_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/return_status'));
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

		$this->data['insert'] = UA('localisation/return_status/insert');
		$this->data['delete'] = UA('localisation/return_status/delete');

		$this->data['return_statuses'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$return_status_total = $this->model_localisation_return_status->getTotalReturnStatuses();

		$results = $this->model_localisation_return_status->getReturnStatuses($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/return_status/update', 'return_status_id=' . $result['return_status_id'])
			);

			$this->data['return_statuses'][] = array(
				'return_status_id' => $result['return_status_id'],
				'name'          => $result['name'] . (($result['return_status_id'] == C('config_return_status_id')) ? L('text_default') : null),
				'selected'      => isset($this->request->post['selected']) && in_array($result['return_status_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/return_status', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $return_status_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/return_status', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/return_status_list.tpl');
  	}

  	protected function getForm() {
     	$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['return_status_id'])) {
			$this->data['action'] = UA('localisation/return_status/update', 'return_status_id=' . $this->request->get['return_status_id']);
		} else {
			$this->data['action'] = UA('localisation/return_status/insert');
		}

		$this->data['cancel'] = UA('localisation/return_status');

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['return_status'])) {
			$this->data['return_status'] = $this->request->post['return_status'];
		} elseif (isset($this->request->get['return_status_id'])) {
			$this->data['return_status'] = $this->model_localisation_return_status->getReturnStatusDescriptions($this->request->get['return_status_id']);
		} else {
			$this->data['return_status'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/return_status_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/return_status')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$pass = true;

    	foreach ($this->request->post['return_status'] as $language_id => $value) {
      		if (!range_length($value['name'], 3, 32)) {
        		$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
      		}
    	}

		return $pass;
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/return_status')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('sale/return');

		foreach ($this->request->post['selected'] as $return_status_id) {
    		if (C('config_return_status_id') == $return_status_id) {
	  			$this->setMessage('error_warning', L('error_default'));
				return false;
			}

			$return_total = $this->model_sale_return->getTotalReturnsByReturnStatusId($return_status_id);

			if ($return_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_return'), $return_total));
				return false;
			}

			$return_total = $this->model_sale_return->getTotalReturnHistoriesByReturnStatusId($return_status_id);

			if ($return_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_return'), $return_total));
				return false;
			}
	  	}

		return true;
  	}
}
?>