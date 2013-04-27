<?php
class ControllerCatalogSupplier extends Controller {

	public function __construct($registry) {
		parent::__construct($registry);
		$this->language->load('catalog/supplier');
		M('catalog/supplier');
	}

  	public function index() {
    	$this->getList();
  	}

  	public function insert() {
		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_supplier->addSupplier($this->request->post);
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/supplier'));
		}
    	$this->getForm();
  	}

  	public function update() {
    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_supplier->editSupplier($this->request->get['id'], $this->request->post);
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/supplier'));
		}
    	$this->getForm();
  	}

  	public function delete() {
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_catalog_supplier->deleteSupplier($supplier_id);
			}
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/supplier'));
    	}

    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$keyword = $qf->get('keyword', '');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['keyword'] = $keyword;
		$this->data['order'] = strtolower($order);

		$limit = C('config_admin_limit');
		$offset = ($page - 1) * $limit;

		$filters = array(
			'keyword'  => $keyword,
			'order' => $order
		);
		$suppliers = $this->model_catalog_supplier->getSuppliers($offset, $limit, $total, $filters);
		$this->data['suppliers'] = $suppliers;

		$this->data['success'] = $this->session->flashdata('success');

		$this->data['sort_url'] = UA('catalog/supplier', $order == 'ASC' ? 'order=DESC' : 'order=ASC');


		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/supplier', 'page={page}');
		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('catalog/supplier_list.tpl');
	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');
		
		$supplier_id = G('supplier_id');
		if ($supplier_id) {
			$this->data['action'] = UA('catalog/supplier/update', "supplier_id=$supplier_id");
			$this->data['supplier'] = $this->model_catalog_supplier->getSupplier($supplier_id);
		} else {
			$this->data['action'] = UA('catalog/supplier/insert');
		}

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('catalog/supplier_form.tpl');
	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/supplier')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return FALSE;
    	}
		return TRUE;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/supplier')) {
			$this->setMessage('error_warning', L('error_permission'));
			return FALSE;
    	}

		foreach ($this->request->post['selected'] as $supplier_id) {
  			$product_total = $this->model_catalog_supplier->getTotalProductBySupplierId($supplier_id);
			if ($product_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return FALSE;
			}
	  	}

		return TRUE;
  	}
}
?>