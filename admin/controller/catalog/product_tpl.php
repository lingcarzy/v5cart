<?php
class ControllerCatalogProductTpl extends Controller {
	public function index() {
		$this->language->load('catalog/product_tpl');
		$this->document->setTitle(L('heading_title'));
		
		$page = G('page', 1);
		$start = ($page - 1) * C('config_admin_limit');
		$limit= C('config_admin_limit');

		M("catalog/product_tpl");
		$this->data['templates'] = $this->model_catalog_product_tpl->getTemplates($start, $limit, $total);

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/product_tpl','page={page}');
		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('catalog/product_tpl_list.tpl');
	}

	public function edit() {
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');
		$template_id = G('template_id', 0);
		M("catalog/product_tpl");
		
		if ($this->request->isPost()) {			
			if ($template_id) {
				$this->model_catalog_product_tpl->updateTemplate($template_id, $this->request->post);
			}
			else {
				$this->model_catalog_product_tpl->addTemplate($this->request->post);
			}
			$this->redirect(UA('catalog/product_tpl'));
		}

		$this->language->load('catalog/product_tpl');
		$this->document->setTitle(L('heading_title'));

		if ($template_id) {
			$tpl = $this->model_catalog_product_tpl->getTemplate($template_id);
			$this->data['status'] = $tpl['status'];
			$this->data['title'] = $tpl['title'];
			$this->data['content'] = $tpl['content'];
			$this->data['action'] = UA('catalog/product_tpl/edit', 'template_id=' . $template_id);
		}
		else {
			$this->data['title'] = '';
			$this->data['content'] = '';
			$this->data['status'] = 1;
			$this->data['action'] = UA('catalog/product_tpl/edit');
		}
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('catalog/product_tpl_form.tpl');
	}

	public function delete() {
		if ($this->request->isPost()) {
			$ids = P('selected');
			if ($ids) {
				M("catalog/product_tpl");
				$this->model_catalog_product_tpl->deleteTemplate($ids);
			}
		}
		$this->redirect(UA("catalog/product_tpl"));
	}
	
	public function ls() {
		M("catalog/product_tpl");
		$templates = $this->model_catalog_product_tpl->getTemplates(0, 0, $total, 'status=1');
		$this->response->setOutput(json_encode($templates));
	}

	public function get() {
		$template_id = G('template_id', 0);
		M("catalog/product_tpl");
		$template = $this->model_catalog_product_tpl->getTemplate($template_id);
		$this->response->setOutput(html_entity_decode($template['content']));
	}
}