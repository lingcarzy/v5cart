<?php
class ControllerAffiliateTracking extends Controller {
	public function index() {
		if (!$this->affiliate->isLogged()) {
	  		$this->session->data['redirect'] = U('affiliate/tracking', '', 'SSL');

	  		$this->redirect(U('affiliate/login', '', 'SSL'));
    	}

		$this->language->load('affiliate/tracking');

		$this->document->setTitle(L('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_account'),
			'href'      => U('affiliate/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('affiliate/tracking', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_description'] = sprintf(L('text_description'), C('config_name'));
		$this->data['text_code'] = L('text_code');
		$this->data['text_generator'] = L('text_generator');
		$this->data['text_link'] = L('text_link');

		$this->data['button_continue'] = L('button_continue');

    	$this->data['code'] = $this->affiliate->getCode();

		$this->data['continue'] = U('affiliate/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('affiliate/tracking.tpl');
  	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			M('catalog/product');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_catalog_product->getProducts($data);

			foreach ($results as $result) {
				$json[] = array(
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'link' => str_replace('&amp;', '&', U('product/product', 'product_id=' . $result['product_id'] . '&tracking=' . $this->affiliate->getCode()))
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>