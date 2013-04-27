<?php
class ControllerSaleVoucherTheme extends Controller {

  	public function index() {
		$this->language->load('sale/voucher_theme');

		M('sale/voucher_theme');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('sale/voucher_theme');

		M('sale/voucher_theme');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_sale_voucher_theme->addVoucherTheme($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('sale/voucher_theme'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('sale/voucher_theme');

		M('sale/voucher_theme');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_sale_voucher_theme->editVoucherTheme($this->request->get['voucher_theme_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('sale/voucher_theme'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('sale/voucher_theme');

		M('sale/voucher_theme');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $voucher_theme_id) {
				$this->model_sale_voucher_theme->deleteVoucherTheme($voucher_theme_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('sale/voucher_theme'));
   		}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['voucher_themes'] = array();

		$data = array(
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$voucher_theme_total = $this->model_sale_voucher_theme->getTotalVoucherThemes();

		$results = $this->model_sale_voucher_theme->getVoucherThemes($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/voucher_theme/update', 'voucher_theme_id=' . $result['voucher_theme_id'])
			);

			$this->data['voucher_themes'][] = array(
				'voucher_theme_id' => $result['voucher_theme_id'],
				'name'             => $result['name'],
				'selected'         => isset($this->request->post['selected']) && in_array($result['voucher_theme_id'], $this->request->post['selected']),
				'action'           => $action
			);
		}

 		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('sale/voucher_theme', 'order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $voucher_theme_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/voucher_theme', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/voucher_theme_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['voucher_theme_id'])) {
			$this->data['action'] = UA('sale/voucher_theme/update', 'voucher_theme_id=' . $this->request->get['voucher_theme_id']);
		} else {
			$this->data['action'] = UA('sale/voucher_theme/insert');
		}

		if (isset($this->request->get['voucher_theme_id']) && !$this->request->isPost()) {
      		$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($this->request->get['voucher_theme_id']);
    	}

		$this->data['token'] = $this->session->data['token'];

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['voucher_theme_description'])) {
			$this->data['voucher_theme_description'] = $this->request->post['voucher_theme_description'];
		} elseif (isset($this->request->get['voucher_theme_id'])) {
			$this->data['voucher_theme_description'] = $this->model_sale_voucher_theme->getVoucherThemeDescriptions($this->request->get['voucher_theme_id']);
		} else {
			$this->data['voucher_theme_description'] = array();
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($voucher_theme_info)) {
			$this->data['image'] = $voucher_theme_info['image'];
		} else {
			$this->data['image'] = '';
		}

		M('tool/image');

		if (isset($voucher_theme_info) && $voucher_theme_info['image'] && file_exists(DIR_IMAGE . $voucher_theme_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($voucher_theme_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/voucher_theme_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$pass = true;
    	foreach ($this->request->post['voucher_theme_description'] as $language_id => $value) {
      		if (!range_length($value['name'], 3, 32)) {
        		$this->setMessage('error_name_' . $language_id, L('error_name'));
				$pass = false;
      		}
    	}

		if (!$this->request->post['image']) {
			$this->setMessage('error_image', L('error_image'));
			$pass = false;
		}
		return $pass;
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('sale/voucher');

		foreach ($this->request->post['selected'] as $voucher_theme_id) {
			$voucher_total = $this->model_sale_voucher->getTotalVouchersByVoucherThemeId($voucher_theme_id);

			if ($voucher_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_voucher'), $voucher_total));
				return false;
			}
	  	}

  		return true;
  	}
}
?>