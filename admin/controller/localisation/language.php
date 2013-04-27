<?php
class ControllerLocalisationLanguage extends Controller {

	public function index() {
		$this->language->load('localisation/language');

		M('localisation/language');

		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/language');

		M('localisation/language');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_language->addLanguage($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/language'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/language');

		M('localisation/language');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_language->editLanguage($this->request->get['language_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/language'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/language');

		M('localisation/language');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $language_id) {
				$this->model_localisation_language->deleteLanguage($language_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/language'));
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

		$this->data['insert'] = UA('localisation/language/insert');
		$this->data['delete'] = UA('localisation/language/delete');

		$this->data['languages'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$language_total = $this->model_localisation_language->getTotalLanguages();

		$results = $this->model_localisation_language->getLanguages($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/language/update', 'language_id=' . $result['language_id'])
			);

			$this->data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == C('config_language')) ? L('text_default') : null),
				'code'        => $result['code'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['language_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('localisation/language', 'sort=name&order=' . $url);
		$this->data['sort_code'] = UA('localisation/language', 'sort=code&order=' . $url);
		$this->data['sort_sort_order'] = UA('localisation/language', 'sort=sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $language_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/language', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/language_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['language_id'])) {
			$this->data['action'] = UA('localisation/language/update', 'language_id=' . $this->request->get['language_id']);
		} else {
			$this->data['action'] = UA('localisation/language/insert');
		}

		$this->data['cancel'] = UA('localisation/language');

		if (isset($this->request->get['language_id']) && !$this->request->isPost()) {
			$language_info = $this->model_localisation_language->getLanguage($this->request->get['language_id']);
		}

		if (!empty($language_info)) {
			$this->data['name'] = $language_info['name'];
			$this->data['code'] = $language_info['code'];
			$this->data['locale'] = $language_info['locale'];
			$this->data['image'] = $language_info['image'];
			$this->data['directory'] = $language_info['directory'];
			$this->data['filename'] = $language_info['filename'];
			$this->data['sort_order'] = $language_info['sort_order'];
			$this->data['status'] = $language_info['status'];
		} else {
			$this->data['name'] = P('name');
			$this->data['code'] = P('code');
			$this->data['locale'] = P('locale');
			$this->data['image'] = P('image');
			$this->data['directory'] = P('directory');
			$this->data['filename'] = P('filename');
			$this->data['sort_order'] = P('sort_order');
			$this->data['status'] = P('status', 1);
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('localisation/language_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$pass = true;
		if (!range_length($this->request->post['name'], 3, 32)) {
			$this->setMessage('error_name', L('error_name'));
			$pass = false;
		}

		if (utf8_strlen($this->request->post['code']) < 2) {
			$this->setMessage('error_code', L('error_code'));
			$pass = false;
		}

		if (!$this->request->post['locale']) {
			$this->setMessage('error_locale', L('error_locale'));
			$pass = false;
		}

		if (!$this->request->post['directory']) {
			$this->setMessage('error_directory', L('error_directory'));
			$pass = false;
		}

		if (!$this->request->post['filename']) {
			$this->setMessage('error_filename', L('error_filename'));
			$pass = false;
		}

		if (!range_length($this->request->post['image'], 3, 32)) {
			$this->setMessage('error_image', L('error_image'));
			$pass = false;
		}

		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		M('sale/order');

		foreach ($this->request->post['selected'] as $language_id) {
			$language_info = $this->model_localisation_language->getLanguage($language_id);

			if ($language_info) {
				if (C('config_language') == $language_info['code']) {
					$this->setMessage('error_warning', L('error_default'));
					return false;
				}

				if (C('config_admin_language') == $language_info['code']) {
					$this->setMessage('error_warning', L('error_admin'));
					return false;
				}

				$store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);

				if ($store_total) {
					$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
					return false;
				}
			}

			$order_total = $this->model_sale_order->getTotalOrdersByLanguageId($language_id);

			if ($order_total) {
				$this->setMessage('error_warning', sprintf(L('error_order'), $order_total));
				return false;
			}
		}

		return true;
	}
}
?>