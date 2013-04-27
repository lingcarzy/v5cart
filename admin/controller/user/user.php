<?php
class ControllerUserUser extends Controller {

  	public function index() {
    	$this->language->load('user/user');

		M('user/user');

    	$this->getList();
  	}

  	public function insert() {
    	$this->language->load('user/user');
		M('user/user');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_user_user->addUser($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('user/user'));
    	}
    	$this->getForm();
  	}

  	public function update() {
    	$this->language->load('user/user');
		M('user/user');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('user/user'));
		}
    	$this->getForm();
  	}

  	public function delete() {
    	$this->language->load('user/user');
		M('user/user');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_user->deleteUser($user_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('user/user'));
    	}
    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();

		$sort = $qf->get('sort', 'username');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);


    	$this->data['users'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$user_total = $this->model_user_user->getTotalUsers();

		$results = $this->model_user_user->getUsers($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('user/user/update', 'user_id=' . $result['user_id'])
			);

      		$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				'status'     => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_username'] = UA('user/user', 'sort=username&order=' . $url);
		$this->data['sort_status'] = UA('user/user', 'sort=status&order=' . $url);
		$this->data['sort_date_added'] = UA('user/user', 'sort=date_added&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('user/user', 'page={page}');

		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('user/user_list.tpl');
  	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['user_id'])) {
			$this->data['action'] = UA('user/user/update', 'user_id=' . $this->request->get['user_id']);
		} else {
			$this->data['action'] = UA('user/user/insert');
		}

    	if (isset($this->request->get['user_id']) && !$this->request->isPost()) {
      		$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
    	}

    	if (!empty($user_info)) {
			$this->data['username'] = $user_info['username'];
			$this->data['firstname'] = $user_info['firstname'];
			$this->data['lastname'] = $user_info['lastname'];
			$this->data['email'] = $user_info['email'];
			$this->data['user_group_id'] = $user_info['user_group_id'];
			$this->data['status'] = $user_info['status'];
		} else {
      		$this->data['username'] = P('username');
			$this->data['firstname'] = P('firstname');
			$this->data['lastname'] = P('lastname');
			$this->data['email'] = P('email');
			$this->data['user_group_id'] = P('user_group_id', 0);
			$this->data['status'] = P('status', 0);
    	}
		$this->data['password'] = P('password');
		$this->data['confirm'] = P('confirm');

		M('user/user_group');
    	$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('user/user_form.tpl');
  	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);
		if ($user_info && (!isset($this->request->get['user_id'])
			|| ($this->request->get['user_id'] != $user_info['user_id']))) {
			$this->setMessage('error_warning', L('error_exists'));
			return false;
		}

		$this->load->library('form_validation', true);
    	$this->form_validation->set_rules('username', '', 'required|range_length[3,20]', L('error_username'));
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));

    	if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
			$this->form_validation->set_rules('password', '', 'required|range_length[4,20]', L('error_password'));
			$this->form_validation->set_rules('confirm', '', 'required|matches[password]', L('error_confirm'));
    	}
		return $this->form_validation->run();
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->setMessage('error_warning', L('error_account'));
				return false;
			}
		}

		return true;
  	}
}
?>