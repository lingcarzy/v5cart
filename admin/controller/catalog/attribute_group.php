<?php
class ControllerCatalogAttributeGroup extends Controller {

  	public function index() {
		$this->language->load('catalog/attribute_group');

		M('catalog/attribute_group');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('catalog/attribute_group');
		
		M('catalog/attribute_group');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_catalog_attribute_group->addAttributeGroup($this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));		
      		$this->redirect(UA('catalog/attribute_group'));
		}
    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('catalog/attribute_group');
		
		M('catalog/attribute_group');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_catalog_attribute_group->editAttributeGroup($this->request->get['attribute_group_id'], $this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/attribute_group'));
    	}
    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('catalog/attribute_group');

		M('catalog/attribute_group');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_attribute_group->deleteAttributeGroup($attribute_group_id);
			}
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/attribute_group'));
   		}
    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'agd.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['attribute_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$attribute_group_total = $this->model_catalog_attribute_group->getTotalAttributeGroups();

		$results = $this->model_catalog_attribute_group->getAttributeGroups($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/attribute_group/update', 'attribute_group_id=' . $result['attribute_group_id'])
			);

			$this->data['attribute_groups'][] = array(
				'attribute_group_id' => $result['attribute_group_id'],
				'name'               => $result['name'],
				'sort_order'         => $result['sort_order'],
				'selected'           => isset($this->request->post['selected']) && in_array($result['attribute_group_id'], $this->request->post['selected']),
				'action'             => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('catalog/attribute_group', 'sort=agd.name&order=' . $url);
		$this->data['sort_sort_order'] = UA('catalog/attribute_group', 'sort=ag.sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $attribute_group_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/attribute_group', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('catalog/attribute_group_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$attribute_group_id = G('attribute_group_id');
		
		if ($attribute_group_id) {
			$this->data['action'] = UA('catalog/attribute_group/update', 'attribute_group_id=' . $attribute_group_id);
		} else {
			$this->data['action'] = UA('catalog/attribute_group/insert');
		}

		if ($attribute_group_id && !$this->request->isPost()) {
			$attribute_group_info = $this->model_catalog_attribute_group->getAttributeGroup($attribute_group_id);
		}

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['attribute_group_description'])) {
			$this->data['attribute_group_description'] = $this->request->post['attribute_group_description'];
		} elseif ($attribute_group_id) {
			$this->data['attribute_group_description'] = $this->model_catalog_attribute_group->getAttributeGroupDescriptions($attribute_group_id);
		} else {
			$this->data['attribute_group_description'] = array();
		}

		if (!empty($attribute_group_info)) {
			$this->data['sort_order'] = $attribute_group_info['sort_order'];
		} else {
			$this->data['sort_order'] = P('sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/attribute_group_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$this->load->library('form_validation', true);
    	foreach ($this->request->post['attribute_group_description'] as $language_id => $value) {
      		$this->form_validation->set_rules("attribute_group_description[$language_id][name]", '', 'required|range_length[3,64]', L('error_name'));
    	}

		return $this->form_validation->run();
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('catalog/attribute');

		foreach ($this->request->post['selected'] as $attribute_group_id) {
			$attribute_total = $this->model_catalog_attribute->getTotalAttributesByAttributeGroupId($attribute_group_id);

			if ($attribute_total) {
				$this->setMessage('error_warning', sprintf(L('error_attribute'), $attribute_total));
				return false;
			}
	  	}

		return true;
  	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			M('catalog/attribute_group');
			
			
			$data = array(
				'filter_name' => G('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);

			$json = array();

			$results = $this->model_catalog_attribute_group->getAttributeGroups($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'attribute_group_id'    => $result['attribute_group_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>