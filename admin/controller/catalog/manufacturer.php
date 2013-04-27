<?php
class ControllerCatalogManufacturer extends Controller {

  	public function index() {
		$this->language->load('catalog/manufacturer');
		M('catalog/manufacturer');
    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('catalog/manufacturer');

		M('catalog/manufacturer');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_manufacturer->addManufacturer($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/manufacturer'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('catalog/manufacturer');

		M('catalog/manufacturer');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_manufacturer->editManufacturer($this->request->get['manufacturer_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/manufacturer'));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('catalog/manufacturer');

		M('catalog/manufacturer');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $manufacturer_id) {
				$this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/manufacturer'));
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

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();

		$results = $this->model_catalog_manufacturer->getManufacturers($data);

		$this->data['manufacturers'] = array();
    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/manufacturer/update', 'manufacturer_id=' . $result['manufacturer_id'])
			);

			$this->data['manufacturers'][] = array(
				'manufacturer_id' => $result['manufacturer_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['manufacturer_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('catalog/manufacturer', 'sort=name&order=' . $url);
		$this->data['sort_sort_order'] = UA('catalog/manufacturer', 'sort=sort_order&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $manufacturer_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/manufacturer', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/manufacturer_list.tpl');
	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$manufacturer_id = G('manufacturer_id', 0);
		
		if ($manufacturer_id) {
			$this->data['action'] = UA('catalog/manufacturer/update','manufacturer_id=' . $manufacturer_id);
		} else {
			$this->data['action'] = UA('catalog/manufacturer/insert');
		}

    	if ($manufacturer_id && !$this->request->isPost()) {
      		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
    	}

		$this->data['token'] = $this->session->data['token'];

    	if (!empty($manufacturer_info)) {
			$this->data['name'] = $manufacturer_info['name'];
			$this->data['seo_url'] = $manufacturer_info['seo_url'];
			$this->data['image'] = $manufacturer_info['image'];
			$this->data['sort_order'] = $manufacturer_info['sort_order'];
		} else {
      		$this->data['name'] = P('name');
			$this->data['seo_url'] = P('seo_url');
			$this->data['image'] = P('image');
			$this->data['sort_order'] = P('sort_order');
    	}

		M('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['manufacturer_store'])) {
			$this->data['manufacturer_store'] = $this->request->post['manufacturer_store'];
		} elseif ($manufacturer_id) {
			$this->data['manufacturer_store'] = $this->model_catalog_manufacturer->getManufacturerStores($manufacturer_id);
		} else {
			$this->data['manufacturer_store'] = array(0);
		}

		M('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($manufacturer_info) && $manufacturer_info['image'] && file_exists(DIR_IMAGE . $manufacturer_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/manufacturer_form.tpl');
	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('name', '', 'required|range_length[3,64]', L('error_name'));
		return $this->form_validation->run();

  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('catalog/product');

		foreach ($this->request->post['selected'] as $manufacturer_id) {
  			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($manufacturer_id);

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
			M('catalog/manufacturer');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_catalog_manufacturer->getManufacturers($data);

			foreach ($results as $result) {
				$json[] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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