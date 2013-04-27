<?php
class ControllerLocalisationReturnReason extends Controller {

  	public function index() {
		$this->language->load('localisation/return_reason');

		M('localisation/return_reason');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('localisation/return_reason');

		M('localisation/return_reason');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_localisation_return_reason->addReturnReason($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/return_reason'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('localisation/return_reason');

		M('localisation/return_reason');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_localisation_return_reason->editReturnReason($this->request->get['return_reason_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/return_reason'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('localisation/return_reason');

		M('localisation/return_reason');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $return_reason_id) {
				$this->model_localisation_return_reason->deleteReturnReason($return_reason_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/return_reason'));
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


		$this->data['insert'] = UA('localisation/return_reason/insert');
		$this->data['delete'] = UA('localisation/return_reason/delete');

		$this->data['return_reasons'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$return_reason_total = $this->model_localisation_return_reason->getTotalReturnReasons();

		$results = $this->model_localisation_return_reason->getReturnReasons($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/return_reason/update', 'return_reason_id=' . $result['return_reason_id'])
			);

			$this->data['return_reasons'][] = array(
				'return_reason_id' => $result['return_reason_id'],
				'name'          => $result['name'],
				'selected'      => isset($this->request->post['selected']) && in_array($result['return_reason_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/return_reason', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $return_reason_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/return_reason', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/return_reason_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['return_reason_id'])) {
			$this->data['action'] = UA('localisation/return_reason/update', 'return_reason_id=' . $this->request->get['return_reason_id']);
		} else {
			$this->data['action'] = UA('localisation/return_reason/insert');
		}

		$this->data['cancel'] = UA('localisation/return_reason');

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['return_reason'])) {
			$this->data['return_reason'] = $this->request->post['return_reason'];
		} elseif (isset($this->request->get['return_reason_id'])) {
			$this->data['return_reason'] = $this->model_localisation_return_reason->getReturnReasonDescriptions($this->request->get['return_reason_id']);
		} else {
			$this->data['return_reason'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/return_reason_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/return_reason')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$pass = true;

    	foreach ($this->request->post['return_reason'] as $language_id => $value) {
      		if (!range_length($value['name'], 3, 32)) {
        		$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
      		}
    	}

		return $pass;
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/return_reason')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('sale/return');

		foreach ($this->request->post['selected'] as $return_reason_id) {
			$return_total = $this->model_sale_return->getTotalReturnsByReturnReasonId($return_reason_id);

			if ($return_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_return'), $return_total));
				return false;
			}
	  	}

		return true;
  	}
}
?>