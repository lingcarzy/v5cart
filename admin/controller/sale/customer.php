<?php
class ControllerSaleCustomer extends Controller {

  	public function index() {
		$this->language->load('sale/customer');
		M('sale/customer');
    	$this->getList();
  	}

  	public function insert() {
		$this->language->load('sale/customer');

		M('sale/customer');

		if ($this->request->isPost() && $this->validateForm()) {
      	  	$this->model_sale_customer->addCustomer($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer'));
		}

    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('sale/customer');

		M('sale/customer');

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_sale_customer->editCustomer($this->request->get['customer_id'], $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer'));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('sale/customer');

		M('sale/customer');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_sale_customer->deleteCustomer($customer_id);
			}

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('sale/customer'));
    	}

    	$this->getList();
  	}

	public function approve() {
		$this->language->load('sale/customer');

		M('sale/customer');

		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = L('error_permission');
		} elseif (isset($this->request->post['selected'])) {
			$approved = 0;

			foreach ($this->request->post['selected'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);

				if ($customer_info && !$customer_info['approved']) {
					$this->model_sale_customer->approve($customer_id);
					$approved++;
				}
			}
			$this->session->set_flashdata('success', sprintf(L('text_approved'), $approved));
			$this->redirect(UA('sale/customer'));
		}

		$this->getList();
	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter('customer_query_filter');
		$filter_name = $qf->get('filter_name');
		$filter_email = $qf->get('filter_email');
		$filter_customer_group_id = $qf->get('filter_customer_group_id');
		$filter_status = $qf->get('filter_status');
		$filter_approved = $qf->get('filter_approved');
		$filter_ip = $qf->get('filter_ip');
		$filter_date_added = $qf->get('filter_date_added');
		$sort = $qf->get('sort', 'name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['customers'] = array();

		$data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status'            => $filter_status,
			'filter_approved'          => $filter_approved,
			'filter_date_added'        => $filter_date_added,
			'filter_ip'                => $filter_ip,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * C('config_admin_limit'),
			'limit'                    => C('config_admin_limit')
		);

		$customer_total = $this->model_sale_customer->getTotalCustomers($data);

		$results = $this->model_sale_customer->getCustomers($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('sale/customer/update', 'customer_id=' . $result['customer_id'])
			);

			$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? L('text_enabled') : L('text_disabled')),
				'approved'       => ($result['approved'] ? L('text_yes') : L('text_no')),
				'ip'             => $result['ip'],
				'date_added'     => date(L('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['customer_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}

		$this->data['success'] = $this->session->flashdata('success');


		$url = ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		$this->data['sort_name'] = UA('sale/customer', 'sort=name' . $url);
		$this->data['sort_email'] = UA('sale/customer', 'sort=c.email' . $url);
		$this->data['sort_customer_group'] = UA('sale/customer', 'sort=customer_group' . $url);
		$this->data['sort_status'] = UA('sale/customer', 'sort=c.status' . $url);
		$this->data['sort_approved'] = UA('sale/customer', 'sort=c.approved' . $url);
		$this->data['sort_ip'] = UA('sale/customer', 'sort=c.ip' . $url);
		$this->data['sort_date_added'] = UA('sale/customer', 'sort=c.date_added' . $url);

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/customer', 'page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_ip'] = $filter_ip;
		$this->data['filter_date_added'] = $filter_date_added;

		M('sale/customer_group');
    	$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		M('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/customer_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));		
		$this->document->addScript('view/javascript/jquery/jquery.validate.js');
		
		$customer_id = G('customer_id', 0);
		
		$this->data['customer_id'] = $customer_id;

		if ($customer_id) {
			$this->data['action'] = UA('sale/customer/update', 'customer_id=' . $customer_id);
		} else {
			$this->data['action'] = UA('sale/customer/insert');
		}

    	$this->data['cancel'] = UA('sale/customer');

    	if ($customer_id && !$this->request->isPost()) {
      		$customer_info = $this->model_sale_customer->getCustomer($customer_id);
    	}

    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($customer_info)) {
			$this->data['firstname'] = $customer_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($customer_info)) {
			$this->data['lastname'] = $customer_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($customer_info)) {
			$this->data['email'] = $customer_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($customer_info)) {
			$this->data['telephone'] = $customer_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($customer_info)) {
			$this->data['fax'] = $customer_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}

    	if (isset($this->request->post['newsletter'])) {
      		$this->data['newsletter'] = $this->request->post['newsletter'];
    	} elseif (!empty($customer_info)) {
			$this->data['newsletter'] = $customer_info['newsletter'];
		} else {
      		$this->data['newsletter'] = '';
    	}

		M('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

    	if (isset($this->request->post['customer_group_id'])) {
      		$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
    	} elseif (!empty($customer_info)) {
			$this->data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
      		$this->data['customer_group_id'] = C('config_customer_group_id');
    	}

    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (!empty($customer_info)) {
			$this->data['status'] = $customer_info['status'];
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

		$this->data['countries'] = cache_read('country.php');

		if (isset($this->request->post['address'])) {
      		$this->data['addresses'] = $this->request->post['address'];
		} elseif (isset($this->request->get['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
		} else {
			$this->data['addresses'] = array();
    	}

    	if (isset($this->request->post['address_id'])) {
      		$this->data['address_id'] = $this->request->post['address_id'];
    	} elseif (!empty($customer_info)) {
			$this->data['address_id'] = $customer_info['address_id'];
		} else {
      		$this->data['address_id'] = '';
    	}

		$this->data['ips'] = array();

		if (!empty($customer_info)) {
			$results = $this->model_sale_customer->getIpsByCustomerId($this->request->get['customer_id']);

			foreach ($results as $result) {
				$blacklist_total = $this->model_sale_customer->getTotalBlacklistsByIp($result['ip']);

				$this->data['ips'][] = array(
					'ip'         => $result['ip'],
					'total'      => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
					'date_added' => date('d/m/y', strtotime($result['date_added'])),
					'filter_ip'  => UA('sale/customer', 'filter_ip=' . $result['ip']),
					'blacklist'  => $blacklist_total
				);
			}
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/customer_form.tpl');
	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$pass = true;

    	if (!range_length($this->request->post['firstname'], 2, 32)) {
      		$this->setMessage('error_firstname', L('error_firstname'));
			$pass = false;
    	}

    	if (!range_length($this->request->post['lastname'], 2, 32)) {
      		$this->setMessage('error_lastname', L('error_lastname'));
			$pass = false;
    	}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->setMessage('error_email', L('error_email'));
			$pass = false;
    	}

		$customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->post['email']);

		if (!isset($this->request->get['customer_id'])) {
			if ($customer_info) {
				$this->setMessage('error_warning', L('error_exists'));
				$pass = false;
			}
		} else {
			if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
				$this->setMessage('error_warning', L('error_exists'));
				$pass = false;
			}
		}

    	if (!range_length($this->request->post['telephone'], 3, 32)) {
      		$this->setMessage('error_telephone', L('error_telephone'));
			$pass = false;
    	}

    	if ($this->request->post['password'] || (!isset($this->request->get['customer_id']))) {
      		if (!range_length($this->request->post['password'], 4, 20)) {
        		$this->setMessage('error_password', L('error_password'));
				$pass = false;
      		}

	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->setMessage('error_confirm', L('error_confirm'));
				$pass = false;
	  		}
    	}

		if (isset($this->request->post['address'])) {
			foreach ($this->request->post['address'] as $key => $value) {
				if (!range_length($value['firstname'], 1, 32)) {
					$this->setMessage('error_address_firstname_' . $key, L('error_firstname'));
					$pass = false;
				}

				if (!range_length($value['lastname'], 1, 32)) {
					$this->setMessage('error_address_lastname_' . $key, L('error_lastname'));
					$pass = false;
				}

				if (!range_length($value['address_1'], 3, 128)) {
					$this->setMessage('error_address_address_1_' . $key, L('error_address_1'));
					$pass = false;
				}

				if (!range_length($value['city'], 2, 128)) {
					$this->setMessage('error_address_city_' . $key, L('error_city'));
					$pass = false;
				}

				M('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($value['country_id']);

				if ($country_info) {
					if ($country_info['postcode_required'] && !range_length($value['postcode'], 2, 10)) {
						$this->setMessage('error_address_postcode_' . $key, L('error_postcode'));
						$pass = false;
					}

					// VAT Validation
					$this->load->helper('vat');

					if (C('config_vat') && $value['tax_id'] && (vat_validation($country_info['iso_code_2'], $value['tax_id']) != 'invalid')) {
						$this->setMessage('error_address_tax_id_' . $key, L('error_vat'));
						$pass = false;
					}
				}

				if ($value['country_id'] == '') {
					$this->setMessage('error_address_country_' . $key, L('error_country'));
					$pass = false;
				}

				if ($value['zone_id'] == '') {
					$this->setMessage('error_address_zone_' . $key, L('error_zone'));
					$pass = false;
				}
			}
		}

		if (!$pass) {
			$this->setMessage('error_warning', L('error_warning'));
		}
		return $pass;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		return true;
  	}

	public function login() {
		$json = array();

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		M('sale/customer');

		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if ($customer_info) {
			$token = md5(mt_rand());

			$this->model_sale_customer->editToken($customer_id, $token);

			if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			M('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$this->redirect($store_info['url'] . 'index.php?route=account/login&token=' . $token);
			} else {
				$this->redirect(HTTP_CATALOG . 'index.php?route=account/login&token=' . $token);
			}
		} else {
			$this->language->load('error/not_found');

			$this->document->setTitle(L('heading_title'));
			$this->data['heading_title'] = L('heading_title');
			$this->data['text_not_found'] = L('text_not_found');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => L('text_home'),
				'href'      => UA('common/home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => L('heading_title'),
				'href'      => UA('error/not_found'),
				'separator' => ' :: '
			);

			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->display('error/not_found.tpl');
		}
	}

	public function transaction() {
    	$this->language->load('sale/customer');

		M('sale/customer');

		if ($this->request->isPost() && $this->user->hasPermission('modify', 'sale/customer')) {
			$this->model_sale_customer->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount']);

			$this->data['success'] = L('text_success');
		}

		if ($this->request->isPost() && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = L('error_permission');
		}	

		$page = G('page', 1);

		$this->data['transactions'] = array();

		$results = $this->model_sale_customer->getTransactions($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], C('config_currency')),
				'description' => $result['description'],
        		'date_added'  => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$this->data['balance'] = $this->currency->format($this->model_sale_customer->getTransactionTotal($this->request->get['customer_id']), C('config_currency'));

		$transaction_total = $this->model_sale_customer->getTotalTransactions($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/customer/transaction', 'customer_id=' . $this->request->get['customer_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->display('sale/customer_transaction.tpl');
	}

	public function reward() {
    	$this->language->load('sale/customer');

		M('sale/customer');

		if ($this->request->isPost() && $this->user->hasPermission('modify', 'sale/customer')) {
			$this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

			$this->data['success'] = L('text_success');
		}

		if ($this->request->isPost() && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = L('error_permission');
		}

		$page = G('page', 1);

		$this->data['rewards'] = array();

		$results = $this->model_sale_customer->getRewards($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['rewards'][] = array(
				'points'      => $result['points'],
				'description' => $result['description'],
        		'date_added'  => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$this->data['balance'] = $this->model_sale_customer->getRewardTotal($this->request->get['customer_id']);

		$reward_total = $this->model_sale_customer->getTotalRewards($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/customer/reward', 'customer_id=' . $this->request->get['customer_id'] . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->display('sale/customer_reward.tpl');
	}

	public function addBlacklist() {
		$this->language->load('sale/customer');

		$json = array();

		if (isset($this->request->post['ip'])) {
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$json['error'] = L('error_permission');
			} else {
				M('sale/customer');

				$this->model_sale_customer->addBlacklist($this->request->post['ip']);

				$json['success'] = L('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function history() {
    	$this->language->load('sale/customer');

		M('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {
			$this->model_sale_customer->addHistory($this->request->get['customer_id'], $this->request->post['comment']);

			$this->data['success'] = L('text_success');
		} else {
			$this->data['success'] = '';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = L('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['text_no_results'] = L('text_no_results');

		$this->data['column_date_added'] = L('column_date_added');
		$this->data['column_comment'] = L('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['histories'] = array();

		$results = $this->model_sale_customer->getHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['histories'][] = array(
			'comment'     => $result['comment'],
			'date_added'  => date(L('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$transaction_total = $this->model_sale_customer->getTotalHistories($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = $this->url->link('sale/customer/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_history.tpl';

		$this->response->setOutput($this->render());
	}

	public function removeBlacklist() {
		$this->language->load('sale/customer');

		$json = array();

		if (isset($this->request->post['ip'])) {
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$json['error'] = L('error_permission');
			} else {
				M('sale/customer');

				$this->model_sale_customer->deleteBlacklist($this->request->post['ip']);

				$json['success'] = L('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			M('sale/customer');

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_sale_customer->getCustomers($data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'fax'               => $result['fax'],
					'address'           => $this->model_sale_customer->getAddresses($result['customer_id'])
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}

	public function address() {
		$json = array();

		if (!empty($this->request->get['address_id'])) {
			M('sale/customer');

			$json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>