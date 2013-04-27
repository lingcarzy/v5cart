<?php
class ControllerAccountReturn extends Controller {
	
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = U('account/return', '', 'SSL');

	  		$this->redirect(U('account/login', '', 'SSL'));
    	}

    	$this->language->load('account/return');

    	$this->document->setTitle(L('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_account'),
			'href'      => U('account/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('account/return', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_return_id'] = L('text_return_id');
		$this->data['text_order_id'] = L('text_order_id');
		$this->data['text_status'] = L('text_status');
		$this->data['text_date_added'] = L('text_date_added');
		$this->data['text_customer'] = L('text_customer');
		$this->data['text_empty'] = L('text_empty');

		$this->data['button_view'] = L('button_view');
		$this->data['button_continue'] = L('button_continue');

		M('account/return');

		$page = $this->request->get('page', 1);

		$this->data['returns'] = array();

		$return_total = $this->model_account_return->getTotalReturns();

		$results = $this->model_account_return->getReturns(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['returns'][] = array(
				'return_id'  => $result['return_id'],
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
				'href'       => U('account/return/info', 'return_id=' . $result['return_id'], 'SSL')
			);
		}

		$pagination = new Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = C('config_catalog_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = U('account/history', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['continue'] = U('account/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/return_list.tpl');
	}

	public function info() {
		$this->language->load('account/return');

		$return_id = $this->request->get('return_id', 0);

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/return/info', 'return_id=' . $return_id, 'SSL');
			$this->redirect(U('account/login', '', 'SSL'));
    	}

		M('account/return');

		$return_info = $this->model_account_return->getReturn($return_id);

		if ($return_info) {
			$this->document->setTitle(L('text_return'));

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => HTTP_SERVER,
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_account'),
				'href'      => U('account/account', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => L('heading_title'),
				'href'      => U('account/return', $url, 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_return'),
				'href'      => U('account/return/info', 'return_id=' . $this->request->get['return_id'] . $url, 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['heading_title'] = L('text_return');

			$this->data['text_return_detail'] = L('text_return_detail');
			$this->data['text_return_id'] = L('text_return_id');
			$this->data['text_order_id'] = L('text_order_id');
			$this->data['text_date_ordered'] = L('text_date_ordered');
			$this->data['text_customer'] = L('text_customer');
			$this->data['text_email'] = L('text_email');
			$this->data['text_telephone'] = L('text_telephone');
			$this->data['text_status'] = L('text_status');
			$this->data['text_date_added'] = L('text_date_added');
			$this->data['text_product'] = L('text_product');
			$this->data['text_comment'] = L('text_comment');
      		$this->data['text_history'] = L('text_history');

      		$this->data['column_product'] = L('column_product');
      		$this->data['column_model'] = L('column_model');
      		$this->data['column_quantity'] = L('column_quantity');
      		$this->data['column_opened'] = L('column_opened');
			$this->data['column_reason'] = L('column_reason');
			$this->data['column_action'] = L('column_action');
			$this->data['column_date_added'] = L('column_date_added');
      		$this->data['column_status'] = L('column_status');
      		$this->data['column_comment'] = L('column_comment');

			$this->data['button_continue'] = L('button_continue');

			$this->data['return_id'] = $return_info['return_id'];
			$this->data['order_id'] = $return_info['order_id'];
			$this->data['date_ordered'] = date(L('date_format_short'), strtotime($return_info['date_ordered']));
			$this->data['date_added'] = date(L('date_format_short'), strtotime($return_info['date_added']));
			$this->data['firstname'] = $return_info['firstname'];
			$this->data['lastname'] = $return_info['lastname'];
			$this->data['email'] = $return_info['email'];
			$this->data['telephone'] = $return_info['telephone'];
			$this->data['product'] = $return_info['product'];
			$this->data['model'] = $return_info['model'];
			$this->data['quantity'] = $return_info['quantity'];
			$this->data['reason'] = $return_info['reason'];
			$this->data['opened'] = $return_info['opened'] ? L('text_yes') : L('text_no');
			$this->data['comment'] = nl2br($return_info['comment']);
			$this->data['action'] = $return_info['action'];

			$this->data['histories'] = array();

			$results = $this->model_account_return->getReturnHistories($this->request->get['return_id']);

      		foreach ($results as $result) {
        		$this->data['histories'][] = array(
          			'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => nl2br($result['comment'])
        		);
      		}

			$this->data['continue'] = U('account/return', $url, 'SSL');

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->display('account/return_info.tpl');
		} else {
			$this->document->setTitle(L('text_return'));

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => HTTP_SERVER,
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_account'),
				'href'      => U('account/account', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('heading_title'),
				'href'      => U('account/return', '', 'SSL'),
				'separator' => L('text_separator')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_return'),
				'href'      => U('account/return/info', 'return_id=' . $return_id . $url, 'SSL'),
				'separator' => L('text_separator')
			);

			$this->data['heading_title'] = L('text_return');
			$this->data['text_error'] = L('text_error');
			$this->data['button_continue'] = L('button_continue');

			$this->data['continue'] = U('account/return', '', 'SSL');

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->display('error/not_found.tpl');
		}
	}

	public function insert() {
		$this->language->load('account/return');

		M('account/return');

    	if ($this->request->isPost() && $this->validate()) {
			$this->model_account_return->addReturn($this->request->post);
			$this->redirect(U('account/return/success', '', 'SSL'));
    	}

		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_account'),
			'href'      => U('account/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('account/return/insert', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_description'] = L('text_description');
		$this->data['text_order'] = L('text_order');
		$this->data['text_product'] = L('text_product');
		$this->data['text_yes'] = L('text_yes');
		$this->data['text_no'] = L('text_no');

		$this->data['entry_order_id'] = L('entry_order_id');
		$this->data['entry_date_ordered'] = L('entry_date_ordered');
		$this->data['entry_firstname'] = L('entry_firstname');
    	$this->data['entry_lastname'] = L('entry_lastname');
    	$this->data['entry_email'] = L('entry_email');
    	$this->data['entry_telephone'] = L('entry_telephone');
		$this->data['entry_product'] = L('entry_product');
		$this->data['entry_model'] = L('entry_model');
		$this->data['entry_quantity'] = L('entry_quantity');
		$this->data['entry_reason'] = L('entry_reason');
		$this->data['entry_opened'] = L('entry_opened');
		$this->data['entry_fault_detail'] = L('entry_fault_detail');
		$this->data['entry_captcha'] = L('entry_captcha');

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_back'] = L('button_back');

		$this->data['action'] = U('account/return/insert', '', 'SSL');

		M('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		}

		M('catalog/product');

		if (isset($this->request->get['product_id'])) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

    	if (isset($this->request->post['order_id'])) {
      		$this->data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($order_info)) {
			$this->data['order_id'] = $order_info['order_id'];
		} else {
      		$this->data['order_id'] = '';
    	}

    	if (isset($this->request->post['date_ordered'])) {
      		$this->data['date_ordered'] = $this->request->post['date_ordered'];
		} elseif (!empty($order_info)) {
			$this->data['date_ordered'] = date('Y-m-d', strtotime($order_info['date_added']));
		} else {
      		$this->data['date_ordered'] = '';
    	}

		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($order_info)) {
			$this->data['firstname'] = $order_info['firstname'];
		} else {
			$this->data['firstname'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($order_info)) {
			$this->data['lastname'] = $order_info['lastname'];
		} else {
			$this->data['lastname'] = $this->customer->getLastName();
		}

		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} elseif (!empty($order_info)) {
			$this->data['email'] = $order_info['email'];
		} else {
			$this->data['email'] = $this->customer->getEmail();
		}

		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($order_info)) {
			$this->data['telephone'] = $order_info['telephone'];
		} else {
			$this->data['telephone'] = $this->customer->getTelephone();
		}

		if (isset($this->request->post['product'])) {
    		$this->data['product'] = $this->request->post['product'];
		} elseif (!empty($product_info)) {
			$this->data['product'] = $product_info['name'];
		} else {
			$this->data['product'] = '';
		}

		if (isset($this->request->post['model'])) {
    		$this->data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$this->data['model'] = $product_info['model'];
		} else {
			$this->data['model'] = '';
		}
		
		$this->data['quantity'] = P('quantity', 1);
		$this->data['opened'] = P('opened', false);
		$this->data['return_reason_id'] = P('return_reason_id');
		
		M('localisation/return_reason');
    	$this->data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

   		$this->data['comment'] = P('comment');
		$this->data['captcha'] = P('captcha');
		
		$this->data['back'] = U('account/account', '', 'SSL');
		
		if (C('config_return_id')) {
			M('catalog/page');
			
			$page_info = $this->model_catalog_page->getPage(C('config_return_id'));
			
			if ($page_info) {
				$this->data['text_agree'] = sprintf(L('text_agree'), U('page/index/info', 'page_id=' . C('config_return_id'), 'SSL'), $page_info['title'], $page_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		$this->data['agree'] = P('agree', false);
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/return_form.tpl');
  	}

  	public function success() {
		$this->language->load('account/return');

		$this->document->setTitle(L('heading_title'));

	  	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('account/return', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

    	$this->data['text_message'] = L('text_message');

    	$this->data['button_continue'] = L('button_continue');

    	$this->data['continue'] = U('account/return', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

 		$this->display('common/success.tpl');
	}

  	protected function validate() {
		if (C('config_return_id')) {
			M('catalog/page');
			
			$page_info = $this->model_catalog_page->getPage(C('config_return_id'));
			
			if ($page_info && !isset($this->request->post['agree'])) {
      			$this->setMessage('error_warning', sprintf(L('error_agree'), $page_info['title']));
				return false;
			}
		}
		
		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('order_id', '', 'required', L('error_order_id'));
    	
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));
		
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));

    	$this->form_validation->set_rules('email', '', 'required|range_length[5,96]|email', L('error_email'));


    	$this->form_validation->set_rules('telephone', '', 'required|range_length[3,32]', L('error_telephone'));

		$this->form_validation->set_rules('product', '', 'required|range_length[1,255]', L('error_product'));

		$this->form_validation->set_rules('model', '', 'required|range_length[1,64]', L('error_model'));

		$this->form_validation->set_rules('return_reason_id', '', 'required', L('error_reason'));

		$this->form_validation->set_rules('captcha', '', 'required|equals['.$this->session->data['captcha'].']', L('error_captcha'));
		
		return $this->form_validation->run();
  	}

	public function captcha() {
		$this->load->library('captcha');
		$captcha = new Captcha();
		$this->session->data['captcha'] = $captcha->getCode();
		$captcha->showImage();
	}
}
?>
