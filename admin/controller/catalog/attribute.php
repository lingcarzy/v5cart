<?php
class ControllerCatalogAttribute extends Controller {

  	public function index() {
		$this->language->load('catalog/attribute');
		M('catalog/attribute');
    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('catalog/attribute');

		M('catalog/attribute');

		if ($this->request->isPost() && $this->validateForm()) {
      		$this->model_catalog_attribute->addAttribute($this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
      		$this->redirect(UA('catalog/attribute'));
		}
    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('catalog/attribute');

		M('catalog/attribute');

    	if ($this->request->isPost() && $this->validateForm()) {
	  		$this->model_catalog_attribute->editAttribute(G('attribute_id'), $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/attribute'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('catalog/attribute');

		M('catalog/attribute');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/attribute'));
   		}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$filter_attribute_group_id = $qf->get('filter_attribute_group_id');
		$sort = $qf->get('sort', 'ad.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$data = array(
			'filter_attribute_group_id' => $filter_attribute_group_id,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);
		
		M('catalog/attribute_group');
		
		$this->data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();
		
		$attribute_total = $this->model_catalog_attribute->getTotalAttributes($data);

		$results = $this->model_catalog_attribute->getAttributes($data);
		
		$this->data['attributes'] = array();
		
		$attribute_types = L('attribute_types');
		
    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/attribute/update', 'attribute_id=' . $result['attribute_id'])
			);

			$this->data['attributes'][] = array(
				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'type'            => isset($attribute_types[$result['type']]) ? $attribute_types[$result['type']] :  $result['type'],
				'value'           => $result['value'],
				'attribute_group' => $result['attribute_group'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['attribute_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';
		
		$this->data['sort_name'] = UA('catalog/attribute', 'sort=ad.name&order=' . $url);
		$this->data['sort_attribute_group'] = UA('catalog/attribute', 'sort=attribute_group&order=' . $url);
		$this->data['sort_sort_order'] = UA('catalog/attribute', 'sort=a.sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $attribute_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/attribute', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_attribute_group_id'] = $filter_attribute_group_id;
		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/attribute_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$attribute_id = G('attribute_id');
		
		if ($attribute_id) {
			$this->data['action'] = UA('catalog/attribute/update', 'attribute_id=' . $attribute_id);
		} else {
			$this->data['action'] = UA('catalog/attribute/insert');
		}

		if ($attribute_id && !$this->request->isPost()) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($attribute_id);
		}

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['attribute_description'])) {
			$this->data['attribute_description'] = $this->request->post['attribute_description'];
		} elseif ($attribute_id) {
			$this->data['attribute_description'] = $this->model_catalog_attribute->getAttributeDescriptions($attribute_id);
		} else {
			$this->data['attribute_description'] = array();
		}

		if (!empty($attribute_info)) {
			$this->data['attribute_group_id'] = $attribute_info['attribute_group_id'];
			$this->data['type'] = $attribute_info['type'];
			$this->data['extend'] = $attribute_info['extend'];
			$this->data['sort_order'] = $attribute_info['sort_order'];
		} else {
			$this->data['attribute_group_id'] = P('attribute_group_id');
			$this->data['type'] = P('type', 'text');
			$this->data['extend'] = P('extend');
			$this->data['sort_order'] = P('sort_order');
		}

		M('catalog/attribute_group');
		$this->data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/attribute_form.tpl');
  	}

	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$this->load->library('form_validation', true);
    	foreach ($this->request->post['attribute_description'] as $language_id => $value) {
			$this->form_validation->set_rules("attribute_description[$language_id][name]", '', 'required|range_length[3,64]', L('error_name'));
    	}
		if ($this->form_validation->run()) {
			return true;
		}
		else {
			$this->setMessage('error_warning', L('error_warning'));
			return false;
		}
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('catalog/product');

		foreach ($this->request->post['selected'] as $attribute_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByAttributeId($attribute_id);

			if ($product_total) {
				$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}
	  	}
		return true;
  	}
	
	public function attribute_form() {
		$attribute_group_id = G('attribute_group_id', 0);
		if ($attribute_group_id) {
			M('catalog/attribute');
			$data = array(
				'filter_attribute_group_id' => $attribute_group_id
			);
			$this->data['attribute_row'] = G('attribute_row', 0);
			$this->data['attributes'] = $this->model_catalog_attribute->getAttributes($data);
			$this->response->setOutput($this->render('catalog/attribute_input_form.tpl'));
		}
		
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			M('catalog/attribute');
			
			
			$data = array(
				'filter_attribute_group_id' => G('attribute_group_id', 0),
				'filter_name' => G('filter_name'),
				'filter_type' => G('filter_type'),
				'start'       => 0,
				'limit'       => 20
			);

			$json = array();

			$results = $this->model_catalog_attribute->getAttributes($data);
			$attribute_row = G('attribute_row', 0);
			
			foreach ($results as $result) {
				$json[] = array(
					'attribute_id'    => $result['attribute_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'html'            => $this->model_catalog_attribute->getAttributeFormField($result['attribute_id'], $attribute_row),
					'attribute_group' => $result['attribute_group']
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}
}
?>