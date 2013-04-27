<?php
class ControllerLocalisationReturnAction extends Controller {

  	public function index() {
		$this->language->load('localisation/return_action');

		M('localisation/return_action');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('localisation/return_action');

    	M('localisation/return_action');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_localisation_return_action->addReturnAction($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/return_action'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('localisation/return_action');

		M('localisation/return_action');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_localisation_return_action->editReturnAction($this->request->get['return_action_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/return_action'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('localisation/return_action');

		M('localisation/return_action');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $return_action_id) {
				$this->model_localisation_return_action->deleteReturnAction($return_action_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('localisation/return_action'));
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

		$this->data['insert'] = UA('localisation/return_action/insert');
		$this->data['delete'] = UA('localisation/return_action/delete');

		$this->data['return_actions'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$return_action_total = $this->model_localisation_return_action->getTotalReturnActions();

		$results = $this->model_localisation_return_action->getReturnActions($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/return_action/update', 'return_action_id=' . $result['return_action_id'])
			);

			$this->data['return_actions'][] = array(
				'return_action_id' => $result['return_action_id'],
				'name'             => $result['name'],
				'selected'         => isset($this->request->post['selected']) && in_array($result['return_action_id'], $this->request->post['selected']),
				'action'           => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/return_action', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $return_action_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/return_action', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/return_action_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['return_action_id'])) {
			$this->data['action'] = UA('localisation/return_action/update', 'return_action_id=' . $this->request->get['return_action_id']);
		} else {
			$this->data['action'] = UA('localisation/return_action/insert');
		}

		$this->data['cancel'] = UA('localisation/return_action');

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['return_action'])) {
			$this->data['return_action'] = $this->request->post['return_action'];
		} elseif (isset($this->request->get['return_action_id'])) {
			$this->data['return_action'] = $this->model_localisation_return_action->getReturnActionDescriptions($this->request->get['return_action_id']);
		} else {
			$this->data['return_action'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/return_action_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/return_action')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$pass = true;

    	foreach ($this->request->post['return_action'] as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
        		$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
      		}
    	}

		return $pass;
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/return_action')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('sale/return');

		foreach ($this->request->post['selected'] as $return_action_id) {
			$return_total = $this->model_sale_return->getTotalReturnsByReturnActionId($return_action_id);

			if ($return_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_return'), $return_total));
				return false;
			}
	  	}

		return true;
  	}
}
?>