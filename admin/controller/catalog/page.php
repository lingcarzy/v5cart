<?php
class ControllerCatalogPage extends Controller { 

	public function index() {
		$this->language->load('catalog/page');		
		M('catalog/page', 'page');		
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/page');		
		M('catalog/page', 'page');
				
		if ($this->request->isPost() && $this->validateForm()) {
			$page_id = $this->page->addPage($this->request->post);
			$this->_toHTML($page_id, $this->request->post['seo_url']);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/page'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/page');
		
		M('catalog/page', 'page');
		
		if ($this->request->isPost() && $this->validateForm()) {
			
			$page_info = $this->page->getPage($this->request->get['page_id']);
			
			$filename = dirname(DIR_APPLICATION) . '/page/' . ($page_info['seo_url'] ? $page_info['seo_url'] : $page_info['page_id']) . '.html';			
			if (file_exists($filename)) unlink($filename);
			
			$this->page->editPage($page_info['page_id'], $this->request->post);
			
			$this->_toHTML($page_info['page_id'], $this->request->post['seo_url']);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/page'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->language->load('catalog/page');		
		M('catalog/page', 'page');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $page_id) {
				$page_info = $this->page->getPage($page_id);
				$filename = dirname(DIR_APPLICATION) . '/page/' . ($page_info['seo_url'] ? $page_info['seo_url'] : $page_info['page_id']) . '.html';
				if (file_exists($filename)) unlink($filename);				
				$this->page->deletePage($page_id);
			}
			
			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(UA('catalog/page'));
		}

		$this->getList();
	}
	
	public function html() {
		$this->language->load('catalog/page');
		M('catalog/page', 'page');
		$pages = $this->page->getPages();
		foreach ($pages as $page) {
			$this->_toHTML($page['page_id'], $page['seo_url']);
		}
		$this->session->set_flashdata('success', L('text_html_success'));
		$this->redirect(UA('catalog/page'));
	}
	
	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'id.title');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);		

		$this->data['pages'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);
		
		$page_total = $this->page->getTotalPages();
	
		$results = $this->page->getPages($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/page/update', 'page_id=' . $result['page_id'])
			);
						
			$this->data['pages'][] = array(
				'page_id' => $result['page_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'href'           => HTTP_CATALOG . $result['link'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['page_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
		
		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';
		
		$this->data['sort_title'] = UA('catalog/page', 'sort=id.title&order=' . $url);
		$this->data['sort_sort_order'] = UA('catalog/page', 'sort=i.sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $page_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/page', 'page={page}');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->display('catalog/page_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$page_id = G('page_id');
		
		if (!$page_id) {
			$this->data['action'] = UA('catalog/page/insert');
		} else {
			$this->data['action'] = UA('catalog/page/update', 'page_id=' . $page_id);
		}

		if ($page_id && !$this->request->isPost()) {
			$page = $this->page->getPage($page_id);
		}
		
		$this->data['languages'] = C('cache_language');
		
		
		if (isset($this->request->post['page_content'])) {
			$this->data['page_content'] = $this->request->post['page_content'];
		} elseif ($page_id) {
			$this->data['page_content'] = $this->page->getPageContents($page_id);
		} else {
			$this->data['page_content'] = array();
		}

		M('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['page_store'])) {
			$this->data['page_store'] = $this->request->post['page_store'];
		} elseif ($page_id) {
			$this->data['page_store'] = $this->page->getPageStores($page_id);
		} else {
			$this->data['page_store'] = array(0);
		}		
		if (!empty($page)) {
			$this->data['seo_url'] = $page['seo_url'];
			$this->data['bottom'] = $page['bottom'];
			$this->data['status'] = $page['status'];
			$this->data['sort_order'] = $page['sort_order'];
		}
		else {
			$this->data['seo_url'] = P('seo_url');
			$this->data['bottom'] = P('bottom', 0);
			$this->data['status'] = P('status', 1);
			$this->data['sort_order'] = P('sort_order');
		}
		
		if (isset($this->request->post['page_layout'])) {
			$this->data['page_layout'] = $this->request->post['page_layout'];
		} elseif ($page_id) {
			$this->data['page_layout'] = $this->page->getPageLayouts($page_id);
		} else {
			$this->data['page_layout'] = array();
		}

		M('design/layout');		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('catalog/page_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/page')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$this->load->library('form_validation', true);
		foreach ($this->request->post['page_content'] as $language_id => $value) {
			$this->form_validation->set_rules("page_content[$language_id][title]", '', 'required|range_length[3,64]', L('error_title'));
			$this->form_validation->set_rules("page_content[$language_id][content]", '', 'required|min_length[3]', L('error_content'));
		}
		
		if (!$this->form_validation->run()) {
			$this->setMessage('error_warning', L('error_warning'));
			return false;
		}
		
		return true;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/page')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		
		foreach ($this->request->post['selected'] as $page_id) {
			if (C('config_account_id') == $page_id) {
				$this->setMessage('error_warning', L('error_account'));
				return false;
			}
			
			if (C('config_checkout_id') == $page_id) {
				$this->setMessage('error_warning', L('error_checkout'));
				return false;
			}
			
			if (C('config_affiliate_id') == $page_id) {
				$this->setMessage('error_warning', L('error_affiliate'));
				return false;
			}
						
			$store_total = $this->model_setting_store->getTotalStoresByInformationId($page_id);

			if ($store_total) {
				$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
				return false;
			}
		}
		return true;
	}
	
	protected function _toHTML($page_id, $filename = '') {
		$url = U('page/index', "page_id=$page_id");
		if (empty($filename)) $filename = $page_id;
		copy($url, dirname(DIR_APPLICATION) . "/page/{$filename}.html");
	}
}
?>