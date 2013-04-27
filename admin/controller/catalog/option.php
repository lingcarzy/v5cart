<?php
class ControllerCatalogOption extends Controller {

	public function index() {
		$this->language->load('catalog/option');
		
		M('catalog/option');
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/option');
		M('catalog/option');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_option->addOption($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(UA('catalog/option'));
		}
		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/option');

		M('catalog/option');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_option->editOption($this->request->get['option_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/option'));
		}
		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/option');
		M('catalog/option');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $option_id) {
				$this->model_catalog_option->deleteOption($option_id);
			}

			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(UA('catalog/option'));
		}
		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'od.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['options'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$option_total = $this->model_catalog_option->getTotalOptions();

		$results = $this->model_catalog_option->getOptions($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/option/update', 'option_id=' . $result['option_id'])
			);

			$action[] = array(
				'text' => L('text_bulk_update'),
				'href' => UA('catalog/option/bulk_update', 'option_id=' . $result['option_id'])
			);

			$this->data['options'][] = array(
				'option_id'  => $result['option_id'],
				'name'       => $result['name'],
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['option_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';
		
		$this->data['sort_name'] = UA('catalog/option', 'sort=od.name&order=' . $url);
		$this->data['sort_sort_order'] = UA('catalog/option', 'sort=o.sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $option_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/option', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('catalog/option_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		$this->data['token'] = $this->session->data['token'];
		
		$option_id = G('option_id');
		
		if ($option_id) {
			$this->data['action'] = UA('catalog/option/update', 'option_id=' . $option_id);
		} else {
			$this->data['action'] = UA('catalog/option/insert');
		}

		if ($option_id && !$this->request->isPost()) {
      		$option_info = $this->model_catalog_option->getOption($option_id);
    	}

		$this->data['languages'] = C('cache_language');

		if (isset($this->request->post['option_description'])) {
			$this->data['option_description'] = $this->request->post['option_description'];
		} elseif ($option_id) {
			$this->data['option_description'] = $this->model_catalog_option->getOptionDescriptions($option_id);
		} else {
			$this->data['option_description'] = array();
		}

		if (!empty($option_info)) {
			$this->data['type'] = $option_info['type'];
			$this->data['sort_order'] = $option_info['sort_order'];
		} else {
			$this->data['type'] = P('type');
			$this->data['sort_order'] = P('sort_order');
		}

		if (isset($this->request->post['option_value'])) {
			$option_values = $this->request->post['option_value'];
		} elseif (isset($this->request->get['option_id'])) {
			$option_values = $this->model_catalog_option->getOptionValueDescriptions($this->request->get['option_id']);
		} else {
			$option_values = array();
		}

		M('tool/image');

		$this->data['option_values'] = array();

		foreach ($option_values as $option_value) {
			if ($option_value['image'] && file_exists(DIR_IMAGE . $option_value['image'])) {
				$image = $option_value['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['option_values'][] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value['option_value_description'],
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($image, 100, 100),
				'sort_order'               => $option_value['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/option_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/option')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox') && !isset($this->request->post['option_value'])) {
			$this->setMessage('error_warning', L('error_type'));
			return false;
		}

		$this->load->library('form_validation', true);

		foreach ($this->request->post['option_description'] as $language_id => $value) {
			$this->form_validation->set_rules("option_description[$language_id][name]", '', 'required|range_length[1,128]', L('error_name'));
		}

		if (isset($this->request->post['option_value'])) {
			foreach ($this->request->post['option_value'] as $option_value_id => $option_value) {
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->form_validation->set_rules("option_value[$option_value_id][option_value_description][$language_id][name]", '', 'required|range_length[1,128]', L('error_option_value'));
				}
			}
		}

		return $this->form_validation->run();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/option')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('catalog/product');

		foreach ($this->request->post['selected'] as $option_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByOptionId($option_id);

			if ($product_total) {
				$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}
		}
		return true;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->language->load('catalog/option');

			M('catalog/option');

			M('tool/image');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$options = $this->model_catalog_option->getOptions($data);

			foreach ($options as $option) {
				$option_value_data = array();

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_values = $this->model_catalog_option->getOptionValues($option['option_id']);

					foreach ($option_values as $option_value) {
						if ($option_value['image'] && file_exists(DIR_IMAGE . $option_value['image'])) {
							$image = $this->model_tool_image->resize($option_value['image'], 50, 50);
						} else {
							$image = '';
						}

						$option_value_data[] = array(
							'option_value_id' => $option_value['option_value_id'],
							'name'            => html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8'),
							'image'           => $image
						);
					}

					$sort_order = array();

					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}

					array_multisort($sort_order, SORT_ASC, $option_value_data);
				}

				$type = '';

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$type = L('text_choose');
				}

				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = L('text_input');
				}

				if ($option['type'] == 'file') {
					$type = L('text_file');
				}

				if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$type = L('text_date');
				}

				$json[] = array(
					'option_id'    => $option['option_id'],
					'name'         => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
					'category'     => $type,
					'type'         => $option['type'],
					'option_value' => $option_value_data
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

	public function bulk_delete() {
		$this->language->load('catalog/option');
		$option_id = 0;
		if ($this->request->isPost()) {
			$option_id = P('option_id');
			$category = P('category');
			if (is_array($category)) {
				$category = implode(',', $category);
				$product_ids = $this->db->queryArray("SELECT DISTINCT product_id FROM @@product_to_category WHERE category_id IN ($category)");
				$chunks = array_chunk($product_ids, 100);
				foreach ($chunks as $chunk) {
					$ids = implode(',', $chunk);
					$this->db->runSql("DELETE FROM @@product_option_value WHERE option_id=$option_id AND product_id IN ($ids)");
					$this->db->runSql("DELETE FROM @@product_option WHERE option_id=$option_id AND product_id IN ($ids)");
				}
				$this->session->set_flashdata('success', L('bulk_delete_success'));
			}
			else $this->session->set_flashdata('warning', L('bulk_update_error'));
		}
		$this->redirect(UA('catalog/option/bulk_update', 'option_id=' . $option_id));
	}

	public function bulk_update() {
		$this->language->load('catalog/option');

		if ($this->request->isPost()) {
			$option_id = P('option_id');
			$required  = P('required');
			$category = P('category');
			$option_values = P('option_value');
			$category = P('category');
			if (is_array($category)) {
				$category = implode(',', $category);
				$product_ids = $this->db->queryArray("SELECT DISTINCT product_id FROM @@product_to_category WHERE category_id IN ($category)");

				$product_option = array(
					'product_id' => 0,
					'option_id' => $option_id,
					'option_value' => is_array($option_values) ? '' : $option_values,
					'required' => $required
				);
				foreach ($product_ids as $product_id) {
					$product_option['product_id'] = $product_id;
					$product_option_id = $this->db->queryOne("SELECT product_option_id FROM  @@product_option WHERE option_id='$option_id' AND product_id='$product_id'");
					if ($product_option_id) {
						$this->db->update('product_option', $product_option, "product_option_id=$product_option_id");
						$this->db->runSql("DELETE FROM @@product_option_value WHERE product_option_id = $product_option_id");
					}
					else {
						$this->db->insert('product_option', $product_option);
						$product_option_id = $this->db->getLastId();
					}
					if (is_array($option_values)) {
						foreach ($option_values as $option_value) {
							$option_value['product_option_id'] = $product_option_id;
							$option_value['product_id'] = $product_id;
							$option_value['option_id'] = $option_id;
							$this->db->insert('product_option_value', $option_value);
						}
					}
				}
				$this->session->set_flashdata('success', L('bulk_update_success'));
			}
			else $this->session->set_flashdata('warning', L('bulk_update_error'));
			$this->redirect(UA('catalog/option/bulk_update', 'option_id=' . $option_id));
		}
		
		$this->document->setTitle(L('bulk_update_title'));
		$option_id = G('option_id');
		
		$this->data['option_id'] = $option_id;

		M('catalog/option');
		
		$this->data['option'] = $this->model_catalog_option->getOption($option_id);
		$this->data['option_values'] = $this->model_catalog_option->getOptionValues($option_id);
		
		$this->document->addScript('view/javascript/jquery/jquery.checkboxtree.min.js');
		$this->document->addStyle('view/stylesheet/jquery.checkboxtree.min.css');
		
		M('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategoryTree();

		$this->data['warning'] = $this->session->flashdata('warning');
		$this->data['success'] = $this->session->flashdata('success');

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('catalog/option_bulk_update.tpl');
	}
}
?>