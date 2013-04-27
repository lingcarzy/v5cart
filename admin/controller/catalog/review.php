<?php
class ControllerCatalogReview extends Controller {

	public function index() {
		$this->language->load('catalog/review');
		M('catalog/review');
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/review');

		M('catalog/review');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_review->addReview($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/review'));
		}
		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/review');

		M('catalog/review');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/review'));
		}
		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/review');
		
		M('catalog/review');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $review_id) {
				$this->model_catalog_review->deleteReview($review_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/review'));
		}
		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		$sort = $qf->get('sort', 'r.date_added');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);


		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$review_total = $this->model_catalog_review->getTotalReviews();
		$results = $this->model_catalog_review->getReviews($data);

		$this->data['reviews'] = array();
		
    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/review/update', 'review_id=' . $result['review_id'])
			);

			$this->data['reviews'][] = array(
				'review_id'  => $result['review_id'],
				'name'       => $result['name'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'status'     => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['review_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_product'] = UA('catalog/review', 'sort=pd.name&order=' . $url);
		$this->data['sort_author'] = UA('catalog/review', 'sort=r.author&order=' . $url);
		$this->data['sort_rating'] = UA('catalog/review', 'sort=r.rating&order=' . $url);
		$this->data['sort_status'] = UA('catalog/review', 'sort=r.status&order=' . $url);
		$this->data['sort_date_added'] = UA('catalog/review', 'sort=r.date_added&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/review', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('catalog/review_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$review_id = G('review_id');
		
		if ($review_id) {
			$this->data['action'] = UA('catalog/review/update', 'review_id=' . $review_id);
		} else {
			$this->data['action'] = UA('catalog/review/insert');
		}

		if ($review_id && !$this->request->isPost()) {
			$review_info = $this->model_catalog_review->getReview($review_id);
		}

		M('catalog/product');

		if (!empty($review_info)) {
			$this->data['product_id'] = $review_info['product_id'];
			$this->data['product'] = $review_info['product'];
			$this->data['author'] = $review_info['author'];
			$this->data['text'] = $review_info['text'];
			$this->data['rating'] = $review_info['rating'];
			$this->data['status'] = $review_info['status'];
		} else {
			$this->data['product_id'] = P('product_id', '');
			$this->data['product'] = P('product');
			$this->data['author'] = P('author');
			$this->data['text'] = P('text');
			$this->data['rating'] = P('rating', 5);
			$this->data['status'] = P('status', 1);
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/review_form.tpl');
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('product_id', '', 'required', L('error_product'));
		$this->form_validation->set_rules('author', '', 'required|range_length[3,64]', L('error_author'));
		$this->form_validation->set_rules('text', '', 'required|min_length[5]', L('error_text'));
		$this->form_validation->set_rules('rating', '', 'required', L('error_rating'));
		return $this->form_validation->run();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>