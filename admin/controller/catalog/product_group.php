<?php
class ControllerCatalogProductGroup extends Controller {
	public function index() {
		$this->language->load('catalog/product_group');
		$this->document->setTitle(L('heading_title'));

		$page = G('page', 1);
		$start = ($page - 1) * C('config_admin_limit');
		$limit= C('config_admin_limit');

		M("catalog/product_group");
		$this->data['product_groups'] = $this->model_catalog_product_group->getGroups($start, $limit, $total);

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/product_group','page={page}');
		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer',
		);
		
		$this->display('catalog/product_group_list.tpl');
	}
	
	public function edit() {
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');
		M("catalog/product_group");
		
		$product_group_id = G('product_group_id', 0);
		
		if ($this->request->isPost()) {
			if ($product_group_id) {
				$this->model_catalog_product_group->editGroup($product_group_id, $this->request->post);
			}
			else {
				$this->model_catalog_product_group->addGroup($this->request->post);
			}
			$this->redirect(UA('catalog/product_group'));
		}
		
		$this->language->load('catalog/product_group');		
		$this->document->setTitle(L('heading_title'));
		
		if ($product_group_id) {
			$product_group = $this->model_catalog_product_group->getGroup($product_group_id);
			$this->data['ref_id'] = $product_group['ref_id'];
			$this->data['title'] = $product_group['title'];
			$this->data['status'] = $product_group['status'];
			$this->data['action'] = UA('catalog/product_group/edit', 'product_group_id=' . $product_group_id);
		}
		else {
			$this->data['ref_id'] = '';
			$this->data['title'] = '';
			$this->data['status'] = 1;
			$this->data['action'] = UA('catalog/product_group/edit');
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('catalog/product_group_form.tpl');
	}
	
	public function delete() {
		if ($this->request->isPost() && !empty($this->request->post['selected'])) {
			M("catalog/product_group");
			foreach($this->request->post['selected'] as $product_group_id) {
				$this->model_catalog_product_group->deleteGroup($product_group_id);
			}
		}
		$this->redirect(UA("catalog/product_group"));
	}
	
	public function product() {
		$this->language->load('catalog/product_group');
		$this->document->setTitle(L('heading_title'));
		
		$product_group_id = $this->request->get('product_group_id');
		M("catalog/product_group");
		$products = $this->model_catalog_product_group->getGroupProducts($product_group_id);
		M('tool/image');
		foreach ($products as $idx => $product) {
			if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
				$image = $this->model_tool_image->resize($product['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
			$products[$idx]['image'] = $image;
		}
		$this->data['products'] = $products;
		$this->data['product_group_id'] = $product_group_id;
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('catalog/product_group_product.tpl');
	}
	
	public function product_delete() {
		$product_group_id = G('product_group_id');
		if ($this->request->isPost()) {
			$product_ids = P('selected');
			if ($product_ids) {
				$product_ids = implode(',', $product_ids);
				$this->db->runSql("DELETE FROM @@product_group_products WHERE product_group_id = $product_group_id AND product_id IN ($product_ids)");
			}
		}
		$this->redirect(UA("catalog/product_group/product", 'product_group_id=' . $product_group_id));
	}
	
	public function product_add() {
		$product_group_id = G('product_group_id');
		if ($this->request->isPost()) {
			$product_ids = P('product_ids');
			if ($product_ids) {
				M("catalog/product_group");
				$this->model_catalog_product_group->setGroupProducts($product_group_id, $product_ids);
			}
		}		
		$this->redirect(UA("catalog/product_group/product", 'product_group_id=' . $product_group_id));
	}
}
?>