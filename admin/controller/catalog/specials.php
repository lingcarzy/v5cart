<?php
class ControllerCatalogSpecials extends Controller {
	private $error = array();

	public function __construct($reg) {
		parent::__construct($reg);
		M('catalog/specials');
		$this->language->load('catalog/specials');
	}

	public function index() {
		$this->getList();
	}

	public function update() {
		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_specials->editSpecial($this->request->get['product_special_id'], $this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/specials'));
		}
		$this->getForm();
	}

	public function delete() {
		M('catalog/specials');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$ids = $this->request->post['selected'];
			$ids = implode(',', $ids);
			$this->model_catalog_specials->deleteSpecials($ids);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/specials'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		$this->data['cur_time'] = time();

		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'ps.date_end');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['specials'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$specials_total = $this->model_catalog_specials->getTotalSpecials();

		$results = $this->model_catalog_specials->getSpecials($data);

		M('tool/image');

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/specials/update', 'product_special_id=' . $result['product_special_id'])
			);

			$result['action'] = $action;

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 100, 100);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 100, 100);
			}
			$result['image'] = $image;
			$this->data['specials'][] = $result;
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_customer_group'] = UA('catalog/specials', 'sort=ps.customer_group_id&order=' . $url);
		$this->data['sort_date_start'] = UA('catalog/specials', 'sort=ps.date_start&order=' . $url);
		$this->data['sort_date_end'] = UA('catalog/specials', 'sort=ps.date_end&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $specials_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/specials', 'page={page}');
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('catalog/special_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		$this->data['action'] = UA('catalog/specials/update', 'product_special_id=' . $this->request->get['product_special_id']);

		$this->data['special'] = $this->model_catalog_specials->getSpecial($this->request->get['product_special_id']);

		M('sale/customer_group');
		$this->data['customer_group'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->display('catalog/special_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/specials')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/specials')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>