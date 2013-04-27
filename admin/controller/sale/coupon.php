<?php  
class ControllerSaleCoupon extends Controller {
	 
  	public function index() {
		$this->language->load('sale/coupon');
		M('sale/coupon');
		
		$this->getList();
  	}
  
  	public function insert() {
    	$this->language->load('sale/coupon');		
		M('sale/coupon');
		
    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_coupon->addCoupon($this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/coupon'));
    	}
    
    	$this->getForm();
  	}

  	public function update() {
    	$this->language->load('sale/coupon');
		
		M('sale/coupon');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_coupon->editCoupon($this->request->get['coupon_id'], $this->request->post);
      		
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/coupon'));
		}
    
    	$this->getForm();
  	}

  	public function delete() {
    	$this->language->load('sale/coupon');
		
		M('sale/coupon');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) { 
			foreach ($this->request->post['selected'] as $coupon_id) {
				$this->model_sale_coupon->deleteCoupon($coupon_id);
			}
      		
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/coupon'));
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
		
		$url = ($order == 'ASC') ? '&order=DESC' : '&order=ASC';
		
		$this->data['sort_name'] = UA('sale/coupon', 'sort=name' . $url);
		$this->data['sort_code'] = UA('sale/coupon', 'sort=code' . $url);
		$this->data['sort_discount'] = UA('sale/coupon', 'sort=discount' . $url);
		$this->data['sort_date_start'] = UA('sale/coupon', 'sort=date_start' . $url);
		$this->data['sort_date_end'] = UA('sale/coupon', 'sort=date_end' . $url);
		$this->data['sort_status'] = UA('sale/coupon', 'sort=status' . $url);
		
		$this->data['success'] = $this->session->flashdata('success');
		
		$this->data['coupons'] = array();

		$filter = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);
		
		$total = $this->model_sale_coupon->getTotalCoupons();
		$coupons = $this->model_sale_coupon->getCoupons($filter);
 
    	foreach ($coupons as $coupon) {
			$action = array();
			
			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/coupon/update', 'coupon_id=' . $coupon['coupon_id'])
			);
			
			$date_start = ($coupon['date_start'] == '0000-00-00') ? '0000-00-00' : date(L('date_format_short'), strtotime($coupon['date_start']));
			
			$date_end = ($coupon['date_end'] == '0000-00-00') ? '0000-00-00' : date(L('date_format_short'), strtotime($coupon['date_end']));
			
			$this->data['coupons'][] = array(
				'coupon_id'  => $coupon['coupon_id'],
				'name'       => $coupon['name'],
				'code'       => $coupon['code'],
				'discount'   => $coupon['discount'],
				'date_start' => $date_start,
				'date_end'   => $date_end,
				'status'     => ($coupon['status'] ? L('text_enabled') : L('text_disabled')),
				'action'     => $action
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/coupon', 'page={page}');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);
		
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->display('sale/coupon_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		$coupon_id = G('coupon_id', 0);
		$this->data['coupon_id'] = $coupon_id;
		
		if ($coupon_id) {
			$this->data['action'] = UA('sale/coupon/update', 'coupon_id=' . $coupon_id);
		} else {
			$this->data['action'] = UA('sale/coupon/insert');
		}
  		
		if ($coupon_id && !$this->request->isPost()) {
      		$coupon_info = $this->model_sale_coupon->getCoupon($coupon_id);
    	}
		
		//Coupon info
    	if (!empty($coupon_info)) {
			$this->data['name'] = $coupon_info['name'];
			$this->data['code'] = $coupon_info['code'];
			$this->data['type'] = $coupon_info['type'];
			$this->data['discount'] = $coupon_info['discount'];
			$this->data['logged'] = $coupon_info['logged'];
			$this->data['shipping'] = $coupon_info['shipping'];
			$this->data['total'] = $coupon_info['total'];
			$this->data['date_start'] = date('Y-m-d', strtotime($coupon_info['date_start']));
			$this->data['date_end'] = date('Y-m-d', strtotime($coupon_info['date_end']));
			$this->data['uses_total'] = $coupon_info['uses_total'];
			$this->data['uses_customer'] = $coupon_info['uses_customer'];
			$this->data['status'] = $coupon_info['status'];
		} else {
      		$this->data['name'] = P('name');
			$this->data['code'] = P('code');
			$this->data['type'] = P('type');
			$this->data['discount'] = P('discount');
			$this->data['logged'] = P('logged');
			$this->data['shipping'] = P('shipping');
			$this->data['total'] = P('total');
			$this->data['date_start'] = P('date_start');
			$this->data['date_end'] = P('date_end');
			$this->data['uses_total'] = P('uses_total', 1);
			$this->data['uses_customer'] = P('uses_customer', 1);
			$this->data['status'] = P('status', 1);
    	}
		
		//Coupon products
		if (isset($this->request->post['coupon_product'])) {
			$products = $this->request->post['coupon_product'];
		} elseif ($coupon_id) {		
			$products = $this->model_sale_coupon->getCouponProducts($coupon_id);
		} else {
			$products = array();
		}
		
		M('catalog/product');		
		$this->data['coupon_product'] = array();
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);			
			if ($product_info) {
				$this->data['coupon_product'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		
		//Coupon Categories
		
		$this->document->addScript('view/javascript/jquery/jquery.checkboxtree.min.js');
		$this->document->addStyle('view/stylesheet/jquery.checkboxtree.min.css');
		
		M('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategoryTree();
		
		if (isset($this->request->post['coupon_category'])) {
			$this->data['coupon_category'] = $this->request->post['coupon_category'];
		} elseif ($coupon_id) {
			$this->data['coupon_category'] = $this->model_sale_coupon->getCouponCategories($coupon_id);
		} else {
			$this->data['coupon_category'] = array();
		}
		
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->display('sale/coupon_form.tpl');
  	}
	
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/coupon')) {
      		$this->setMessage('error_warning', L('error_permission'));  
			return false;
    	}
      	$pass = true;
		if (!range_length($this->request->post['name'], 3, 128)) {
        	$this->setMessage('error_name', L('error_name'));
			$pass = false;
      	}
		
    	if (!range_length($this->request->post['code'], 3, 10)) {
      		$this->setMessage('error_code', L('error_code'));
			$pass = false;
    	}
		
		$coupon_info = $this->model_sale_coupon->getCouponByCode($this->request->post['code']);
		
		if ($coupon_info) {
			if (!isset($this->request->get['coupon_id'])
				|| $coupon_info['coupon_id'] != $this->request->get['coupon_id']) {
				$this->setMessage('error_warning', L('error_exists'));
				$pass = false;
			}
		}
	
    	return $pass;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/coupon')) {
      		$this->setMessage('error_warning', L('error_permission'));  
			return false;
    	}
	  	return true;
  	}	
	
	public function history() {
    	$this->language->load('sale/coupon');
		
		M('sale/coupon');
		
		$page = G('page', 1);
		$coupon_id = G('coupon_id');
		
		$this->data['histories'] = array();
			
		$results = $this->model_sale_coupon->getCouponHistories($coupon_id, ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
        		'date_added' => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_coupon->getTotalCouponHistories($coupon_id);
			
		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = UA('sale/coupon/history', "coupon_id=$coupon_id&page={page}");
			
		$this->data['pagination'] = $pagination->render();
		
		$this->display('sale/coupon_history.tpl');
  	}		
}
?>