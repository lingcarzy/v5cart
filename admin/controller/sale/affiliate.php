<?php
class ControllerSaleAffiliate extends Controller {

  	public function index() {
		$this->language->load('sale/affiliate');

		M('sale/affiliate');

    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('sale/affiliate');

		M('sale/affiliate');

		if ($this->request->isPost() && $this->validateForm()) {
      	  	$this->model_sale_affiliate->addAffiliate($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/affiliate'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('sale/affiliate');

		M('sale/affiliate');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_affiliate->editAffiliate($this->request->get['affiliate_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/affiliate'));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('sale/affiliate');

		M('sale/affiliate');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $affiliate_id) {
				$this->model_sale_affiliate->deleteAffiliate($affiliate_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/affiliate'));
    	}

    	$this->getList();
  	}

	public function approve() {
		$this->language->load('sale/affiliate');

		M('sale/affiliate');

		if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
			$this->error['warning'] = L('error_permission');
		} elseif (isset($this->request->post['selected'])) {
			$approved = 0;

			foreach ($this->request->post['selected'] as $affiliate_id) {
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

				if ($affiliate_info && !$affiliate_info['approved']) {
					$this->model_sale_affiliate->approve($affiliate_id);
					$approved++;
				}
			}

			$this->session->set_flashdata('success', sprintf(L('text_approved'), $approved));
			$this->redirect(UA('sale/affiliate'));
		}

		$this->getList();
	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter('affiliate_query_filter');
		$filter_name = $qf->get('filter_name');
		$filter_email = $qf->get('filter_email');
		$filter_status = $qf->get('filter_status');
		$filter_approved = $qf->get('filter_approved');
		$filter_date_added = $qf->get('filter_date_added');
		$sort = $qf->get('sort', 'name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['affiliates'] = array();

		$data = array(
			'filter_name'       => $filter_name,
			'filter_email'      => $filter_email,
			'filter_status'     => $filter_status,
			'filter_approved'   => $filter_approved,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * C('config_admin_limit'),
			'limit'             => C('config_admin_limit')
		);

		$affiliate_total = $this->model_sale_affiliate->getTotalAffiliates($data);

		$results = $this->model_sale_affiliate->getAffiliates($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/affiliate/update', 'affiliate_id=' . $result['affiliate_id'])
			);

			$this->data['affiliates'][] = array(
				'affiliate_id' => $result['affiliate_id'],
				'name'         => $result['name'],
				'email'        => $result['email'],
				'balance'      => $this->currency->format($result['balance'], C('config_currency')),
				'status'       => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'approved'     => ($result['approved'] ? L('text_yes') : L('text_no')),
				'date_added'   => date(L('date_format_short'), strtotime($result['date_added'])),
				'selected'     => isset($this->request->post['selected']) && in_array($result['affiliate_id'], $this->request->post['selected']),
				'action'       => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? '&order=DESC' : '&order=ASC';
		$this->data['sort_name'] = UA('sale/affiliate', 'sort=name' . $url);
		$this->data['sort_email'] = UA('sale/affiliate', 'sort=a.email' . $url);
		$this->data['sort_status'] = UA('sale/affiliate', 'sort=a.status' . $url);
		$this->data['sort_approved'] = UA('sale/affiliate', 'sort=a.approved' . $url);
		$this->data['sort_date_added'] = UA('sale/affiliate', 'sort=a.date_added' . $url);

		$pagination = new Pagination();
		$pagination->total = $affiliate_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/affiliate', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_date_added'] = $filter_date_added;

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/affiliate_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		if (isset($this->request->get['affiliate_id'])) {
			$this->data['action'] = UA('sale/affiliate/update', 'affiliate_id=' . $this->request->get['affiliate_id']);
		} else {
			$this->data['action'] = UA('sale/affiliate/insert');
		}

    	$this->data['cancel'] = UA('sale/affiliate');

    	if (isset($this->request->get['affiliate_id']) && !$this->request->isPost()) {
      		$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->get['affiliate_id']);
    	}

		$this->data['affiliate_id'] = G('affiliate_id', 0);

    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($affiliate_info)) {
			$this->data['firstname'] = $affiliate_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['lastname'] = $affiliate_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['email'] = $affiliate_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['telephone'] = $affiliate_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['fax'] = $affiliate_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}

    	if (isset($this->request->post['company'])) {
      		$this->data['company'] = $this->request->post['company'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['company'] = $affiliate_info['company'];
		} else {
      		$this->data['company'] = '';
    	}

    	if (isset($this->request->post['address_1'])) {
      		$this->data['address_1'] = $this->request->post['address_1'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['address_1'] = $affiliate_info['address_1'];
		} else {
      		$this->data['address_1'] = '';
    	}

    	if (isset($this->request->post['address_2'])) {
      		$this->data['address_2'] = $this->request->post['address_2'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['address_2'] = $affiliate_info['address_2'];
		} else {
      		$this->data['address_2'] = '';
    	}

    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['city'] = $affiliate_info['city'];
		} else {
      		$this->data['city'] = '';
    	}

    	if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['postcode'] = $affiliate_info['postcode'];
		} else {
      		$this->data['postcode'] = '';
    	}

		if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['country_id'] = $affiliate_info['country_id'];
		} else {
      		$this->data['country_id'] = '';
    	}

		$this->data['countries'] = cache_read('country.php');

		if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['zone_id'] = $affiliate_info['zone_id'];
		} else {
      		$this->data['zone_id'] = '';
    	}

		if (isset($this->request->post['code'])) {
      		$this->data['code'] = $this->request->post['code'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['code'] = $affiliate_info['code'];
		} else {
      		$this->data['code'] = uniqid();
    	}

		if (isset($this->request->post['commission'])) {
      		$this->data['commission'] = $this->request->post['commission'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['commission'] = $affiliate_info['commission'];
		} else {
      		$this->data['commission'] = C('config_commission');
    	}

		if (isset($this->request->post['tax'])) {
      		$this->data['tax'] = $this->request->post['tax'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['tax'] = $affiliate_info['tax'];
		} else {
      		$this->data['tax'] = '';
    	}

		if (isset($this->request->post['payment'])) {
      		$this->data['payment'] = $this->request->post['payment'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['payment'] = $affiliate_info['payment'];
		} else {
      		$this->data['payment'] = 'cheque';
    	}

		if (isset($this->request->post['cheque'])) {
      		$this->data['cheque'] = $this->request->post['cheque'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['cheque'] = $affiliate_info['cheque'];
		} else {
      		$this->data['cheque'] = '';
    	}

		if (isset($this->request->post['paypal'])) {
      		$this->data['paypal'] = $this->request->post['paypal'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['paypal'] = $affiliate_info['paypal'];
		} else {
      		$this->data['paypal'] = '';
    	}

		if (isset($this->request->post['bank_name'])) {
      		$this->data['bank_name'] = $this->request->post['bank_name'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['bank_name'] = $affiliate_info['bank_name'];
		} else {
      		$this->data['bank_name'] = '';
    	}

		if (isset($this->request->post['bank_branch_number'])) {
      		$this->data['bank_branch_number'] = $this->request->post['bank_branch_number'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
		} else {
      		$this->data['bank_branch_number'] = '';
    	}

		if (isset($this->request->post['bank_swift_code'])) {
      		$this->data['bank_swift_code'] = $this->request->post['bank_swift_code'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
		} else {
      		$this->data['bank_swift_code'] = '';
    	}

		if (isset($this->request->post['bank_account_name'])) {
      		$this->data['bank_account_name'] = $this->request->post['bank_account_name'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['bank_account_name'] = $affiliate_info['bank_account_name'];
		} else {
      		$this->data['bank_account_name'] = '';
    	}

		if (isset($this->request->post['bank_account_number'])) {
      		$this->data['bank_account_number'] = $this->request->post['bank_account_number'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['bank_account_number'] = $affiliate_info['bank_account_number'];
		} else {
      		$this->data['bank_account_number'] = '';
    	}

    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (!empty($affiliate_info)) {
			$this->data['status'] = $affiliate_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}

    	if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/affiliate_form.tpl');
	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		$pass = true;

    	if (!range_length($this->request->post['firstname'], 1, 32)) {
      		$this->setMessage('error_firstname', L('error_firstname'));
			$pass = false;
    	}

    	if (!range_length($this->request->post['lastname'], 1, 32)) {
      		$this->setMessage('error_lastname', L('error_lastname'));
			$pass = false;
    	}

		if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
      		$this->setMessage('error_email', L('error_email'));
			$pass = false;
    	}

		$affiliate_info = $this->model_sale_affiliate->getAffiliateByEmail($this->request->post['email']);

		if (!isset($this->request->get['affiliate_id'])) {
			if ($affiliate_info) {
				$this->setMessage('error_warning', L('error_exists'));
				$pass = false;
			}
		} else {
			if ($affiliate_info && ($this->request->get['affiliate_id'] != $affiliate_info['affiliate_id'])) {
				$this->setMessage('error_warning', L('error_exists'));
				$pass = false;
			}
		}

    	if (!range_length($this->request->post['telephone'], 3, 32)) {
      		$this->setMessage('error_telephone', L('error_telephone'));
			$pass = false;
    	}

    	if ($this->request->post['password'] || (!isset($this->request->get['affiliate_id']))) {
      		if (!range_length($this->request->post['password'], 4, 20)) {
        		$this->setMessage('error_password', L('error_password'));
				$pass = false;
      		}

	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->setMessage('error_confirm', L('error_confirm'));
				$pass = false;
	  		}
    	}

    	if (!range_length($this->request->post['address_1'], 3, 128)) {
      		$this->setMessage('error_address_1', L('error_address_1'));
			$pass = false;
    	}

    	if (!range_length($this->request->post['city'], 2, 128)) {
      		$this->setMessage('error_city', L('error_city'));
			$pass = false;
    	}

		M('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && !range_length($this->request->post['postcode'], 2, 10)) {
			$this->setMessage('error_postcode', L('error_postcode'));
			$pass = false;
		}

    	if ($this->request->post['country_id'] == '') {
      		$this->setMessage('error_country', L('error_country'));
			$pass = false;
    	}

    	if ($this->request->post['zone_id'] == '') {
      		$this->setMessage('error_zone', L('error_zone'));
			$pass = false;
    	}

    	if (!$this->request->post['code']) {
      		$this->setMessage('error_code', L('error_code'));
			$pass = false;
    	}
		return $pass;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
	  	return true;
  	}

	public function transaction() {
    	$this->language->load('sale/affiliate');

		M('sale/affiliate');

		if ($this->request->isPost() && $this->user->hasPermission('modify', 'sale/affiliate')) {
			$this->model_sale_affiliate->addTransaction($this->request->get['affiliate_id'], $this->request->post['description'], $this->request->post['amount']);

			$this->data['success'] = L('text_success');
		} else {
			$this->data['success'] = '';
		}

		if ($this->request->isPost() && !$this->user->hasPermission('modify', 'sale/affiliate')) {
			$this->data['error_warning'] = L('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}

		$page = G('page', 1);

		$this->data['transactions'] = array();

		$results = $this->model_sale_affiliate->getTransactions($this->request->get['affiliate_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], C('config_currency')),
				'description' => $result['description'],
        		'date_added'  => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$this->data['balance'] = $this->currency->format($this->model_sale_affiliate->getTransactionTotal($this->request->get['affiliate_id']), C('config_currency'));

		$transaction_total = $this->model_sale_affiliate->getTotalTransactions($this->request->get['affiliate_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/affiliate/transaction', 'affiliate_id=' . $this->request->get['affiliate_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->display('sale/affiliate_transaction.tpl');
	}

	public function autocomplete() {
		$affiliate_data = array();

		if (isset($this->request->get['filter_name'])) {
			M('sale/affiliate');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_sale_affiliate->getAffiliates($data);

			foreach ($results as $result) {
				$affiliate_data[] = array(
					'affiliate_id' => $result['affiliate_id'],
					'name'         => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')
				);
			}
		}

		$this->response->setOutput(json_encode($affiliate_data));
	}
}
?>