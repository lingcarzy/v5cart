<?php
class ControllerReportProductViewed extends Controller {
	public function index() {
		$this->language->load('report/product_viewed');

		$this->document->setTitle(L('heading_title'));

		$page = $this->request->get('page', 1);

		M('report/product');

		$data = array(
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$product_viewed_total = $this->model_report_product->getTotalProductsViewed($data);

		$product_views_total = $this->model_report_product->getTotalProductViews();

		$this->data['products'] = array();

		$results = $this->model_report_product->getProductsViewed($data);

		foreach ($results as $result) {
			if ($result['viewed']) {
				$percent = round($result['viewed'] / $product_views_total * 100, 2);
			} else {
				$percent = 0;
			}

			$this->data['products'][] = array(
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => $percent . '%'
			);
		}

		$this->data['reset'] = UA('report/product_viewed/reset');

		$this->data['success'] = $this->session->flashdata('success');

		$pagination = new Pagination();
		$pagination->total = $product_viewed_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('report/product_viewed', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('report/product_viewed.tpl');
	}

	public function reset() {
		$this->language->load('report/product_viewed');

		M('report/product');

		$this->model_report_product->reset();

		$this->session->set_flashdata('success', L('text_success'));

		$this->redirect(UA('report/product_viewed'));
	}
}
?>