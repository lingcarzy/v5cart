<?php
class ControllerUserUserPermission extends Controller {

	public function index() {
		$this->language->load('user/user_group');

		M('user/user_group');
		$this->getList();
	}

	public function insert() {
		$this->language->load('user/user_group');
		M('user/user_group');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_user_user_group->addUserGroup($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('user/user_permission'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('user/user_group');

		M('user/user_group');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_user_user_group->editUserGroup($this->request->get['user_group_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('user/user_permission'));
		}
		$this->getForm();
	}

	public function delete() {
		$this->language->load('user/user_group');

		M('user/user_group');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_group_id) {
				$this->model_user_user_group->deleteUserGroup($user_group_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('user/user_permission'));
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

		$this->data['user_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$user_group_total = $this->model_user_user_group->getTotalUserGroups();

		$results = $this->model_user_user_group->getUserGroups($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('user/user_permission/update', 'user_group_id=' . $result['user_group_id'])
			);

			$this->data['user_groups'][] = array(
				'user_group_id' => $result['user_group_id'],
				'name'          => $result['name'],
				'selected'      => isset($this->request->post['selected']) && in_array($result['user_group_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('user/user_permission', 'sort=name&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $user_group_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('user/user_permission', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('user/user_group_list.tpl');
 	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (!isset($this->request->get['user_group_id'])) {
			$this->data['action'] = UA('user/user_permission/insert');
		} else {
			$this->data['action'] = UA('user/user_permission/update', 'user_group_id=' . $this->request->get['user_group_id']);
		}


		if (isset($this->request->get['user_group_id']) && !$this->request->isPost()) {
			$user_group_info = $this->model_user_user_group->getUserGroup($this->request->get['user_group_id']);
		}

		if (!empty($user_group_info)) {
			$this->data['name'] = $user_group_info['name'];
		} else {
			$this->data['name'] = P('name');
		}

		$ignore = array(
			'common/home',
			'common/startup',
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/reset',
			'error/not_found',
			'error/permission',
			'common/footer',
			'common/header'
		);

		$this->data['permissions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/*/*.php');

		foreach ($files as $file) {
			$data = explode('/', dirname($file));

			$permission = end($data) . '/' . basename($file, '.php');

			if (!in_array($permission, $ignore)) {
				$this->data['permissions'][] = $permission;
			}
		}

		if (isset($this->request->post['permission']['access'])) {
			$this->data['access'] = $this->request->post['permission']['access'];
		} elseif (isset($user_group_info['permission']['access'])) {
			$this->data['access'] = $user_group_info['permission']['access'];
		} else {
			$this->data['access'] = array();
		}

		if (isset($this->request->post['permission']['modify'])) {
			$this->data['modify'] = $this->request->post['permission']['modify'];
		} elseif (isset($user_group_info['permission']['modify'])) {
			$this->data['modify'] = $user_group_info['permission']['modify'];
		} else {
			$this->data['modify'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('user/user_group_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'user/user_permission')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->setMessage('error_name', L('error_name'));
			return false;
		}

		return true;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'user/user_permission')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('user/user');

		foreach ($this->request->post['selected'] as $user_group_id) {
			$user_total = $this->model_user_user->getTotalUsersByGroupId($user_group_id);

			if ($user_total) {
				$this->setMessage('error_warning', sprintf(L('error_user'), $user_total));
				return false;
			}
		}
		return true;
	}
}
?>