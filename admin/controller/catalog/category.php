<?php
class ControllerCatalogCategory extends Controller {

	public function index() {
		$this->language->load('catalog/category');
		M('catalog/category');
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/category');

		M('catalog/category');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_category->addCategory($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/category'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/category');

		M('catalog/category');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(UA('catalog/category'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/category');

		M('catalog/category');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/category'));
		}
		$this->getList();
	}

	public function update_cache() {
		M('catalog/category');
		$this->language->load('catalog/category');
		//call twice
		$this->model_catalog_category->cache();
		$this->model_catalog_category->cache();
		$this->session->set_flashdata('success', L('text_cache_success'));
		$this->redirect(UA('catalog/category'));
	}

	protected function getList() {
		global $CATEGORIES;

		$this->document->setTitle(L('heading_title'));
		$category_id = G('category_id', 0);

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
			'text'      => L('text_home'),
			'href'      => UA('common/home'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
			'text'      => L('heading_title'),
			'href'      => UA('catalog/category'),
			'separator' => ' :: '
   		);

		if ($category_id && isset($CATEGORIES[$category_id])) {
			foreach(explode(',', $CATEGORIES[$category_id]['path']) as $id) {
				$this->data['breadcrumbs'][] = array(
					'text'      => $CATEGORIES[$id]['name'],
					'href'      => UA('catalog/category', 'category_id=' . $id),
					'separator' => ' :: '
				);
			}
		}

		$this->data['categories'] = array();



		$results = $this->model_catalog_category->getCategories($category_id, false);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/category/update', 'category_id=' . $result['category_id'])
			);
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'total'       => isset($CATEGORIES[$result['category_id']]) ? $CATEGORIES[$result['category_id']]['total']: 0,
				'href' => U('product/category', 'cate_id=' . $result['category_id']),
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/category_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');

		$this->data['token'] = $this->session->data['token'];

		$category_id = G('category_id', 0);

		if ($category_id) {
			$this->data['action'] = UA('catalog/category/update', 'category_id=' . $category_id);
		} else {
			$this->data['action'] = UA('catalog/category/insert');
		}

		if ($category_id && !$this->request->isPost()) {
      		$category_info = $this->model_catalog_category->getCategory($category_id);
    	}

		$this->data['languages'] = C('cache_language');

		M('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		M('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$categories = $this->model_catalog_category->getCategories(0);

		if (!empty($category_info)) {
			$this->extract[] = $category_info;

			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($category_id);

			$this->data['category_store'] = $this->model_catalog_category->getCategoryStores($category_id);

			$this->data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($category_id);

			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['category_id']) {
					unset($categories[$key]);
				}
			}

			$attribute_ids = $category_info['attribute_ids'];
		}
		else {
			$this->data['parent_id'] = P('parent_id', 0);
			$this->data['seo_url'] = P('seo_url');
			$this->data['image'] = P('image');
			$this->data['top'] = P('top', 0);
			$this->data['category_description'] = P('category_description', array());
			$this->data['category_store'] = P('category_store', array(0));
			$this->data['status'] = P('status', 1);
			$this->data['sort_order'] = P('sort_order', 0);
			$this->data['column'] = P('column', 1);
			$this->data['category_layout'] = P('category_layout', array());
			$this->data['attribute_ids'] = P('attribute_ids');
			$attribute_ids = $this->data['attribute_ids'];
		}

		$this->data['category_attribute'] = array();
		
		if ($attribute_ids) {
				M('catalog/attribute');
				foreach(explode(',', $attribute_ids) as $attribute_id) {
					$attribute_description = $this->model_catalog_attribute->getAttributeDescriptions($attribute_id);
					$this->data['category_attribute'][] = array(
						'attribute_id' => $attribute_id,
						'name' => $attribute_description[C('config_language_id')]['name']
					);
				}
		}

		M('tool/image');
		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($category_info) && $category_info['image']
			&& file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->data['categories'] = $categories;
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/category_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$this->load->library('form_validation', true);
		foreach ($this->request->post['category_description'] as $language_id => $value) {
			$this->form_validation->set_rules("category_description[$language_id][name]", '', 'required|range_length[3,255]', L('error_name'));
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
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}

	public function subCategories() {
		$output = '<option value="0">' . L('text_none') . '</option>';

		M('catalog/category');
		$categories = $this->model_catalog_category->getCategories($this->request->get['parent_id'], false);
		$output .= form_select_option($categories, null, null, 'category_id', 'name');
		$this->response->setOutput($output);
	}
}
?>