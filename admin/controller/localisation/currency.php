<?php 
class ControllerLocalisationCurrency extends Controller {
 
	public function index() {
		$this->language->load('localisation/currency');	
		
		M('localisation/currency');
		
		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/currency');
		
		M('localisation/currency');
		
		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_currency->addCurrency($this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/currency'));
		}
		
		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/currency');
		
		M('localisation/currency');
		
		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_localisation_currency->editCurrency($this->request->get['currency_id'], $this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/currency'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/currency');
		
		M('localisation/currency');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $currency_id) {
				$this->model_localisation_currency->deleteCurrency($currency_id);
			}
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('localisation/currency'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
		$this->load->helper('query_filter');
		$qf = new Query_filter();
		
		$sort = $qf->get('sort', 'title');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);
		
		$this->data['insert'] = UA('localisation/currency/insert');
		$this->data['delete'] = UA('localisation/currency/delete');
		
		$this->data['currencies'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);
		
		$currency_total = $this->model_localisation_currency->getTotalCurrencies();

		$results = $this->model_localisation_currency->getCurrencies($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('localisation/currency/update', 'currency_id=' . $result['currency_id'])
			);
						
			$this->data['currencies'][] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'] . (($result['code'] == C('config_currency')) ? L('text_default') : null),
				'code'          => $result['code'],
				'value'         => $result['value'],
				'date_modified' => date(L('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['currency_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}	
	
		$this->data['success'] = $this->session->flashdata('success');

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_title'] = UA('localisation/currency', 'sort=title&order=' . $url);
		$this->data['sort_code'] = UA('localisation/currency', 'sort=code&order=' . $url);
		$this->data['sort_value'] = UA('localisation/currency', 'sort=value&order=' . $url);
		$this->data['sort_date_modified'] = UA('localisation/currency', 'sort=date_modified&order=' . $url);
		
		$pagination = new Pagination();
		$pagination->total = $currency_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('localisation/currency', 'page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('localisation/currency_list.tpl');
	}

	protected function getForm() {
		$this->document->setTitle(L('heading_title'));
		
		if (isset($this->request->get['currency_id'])) {
			$this->data['action'] = UA('localisation/currency/update', 'currency_id=' . $this->request->get['currency_id']);
		} else {
			$this->data['action'] = UA('localisation/currency/insert');
		}
		
		$this->data['cancel'] = UA('localisation/currency');

		if (isset($this->request->get['currency_id']) && !$this->request->isPost()) {
			$currency_info = $this->model_localisation_currency->getCurrency($this->request->get['currency_id']);
		}

		if (!empty($currency_info)) {
			$this->data['title'] = $currency_info['title'];
			$this->data['code'] = $currency_info['code'];
			$this->data['symbol_left'] = $currency_info['symbol_left'];
			$this->data['symbol_right'] = $currency_info['symbol_right'];
			$this->data['decimal_place'] = $currency_info['decimal_place'];
			$this->data['value'] = $currency_info['value'];
			$this->data['status'] = $currency_info['status'];
		} else {
			$this->data['title'] = P('title', '');
			$this->data['code'] = P('code', '');
			$this->data['symbol_left'] = P('symbol_left', '');
			$this->data['symbol_right'] = P('symbol_right', '');
			$this->data['decimal_place'] = P('decimal_place', '');
			$this->data['value'] = P('value', '');
			$this->data['status'] = P('status', 1);
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->display('localisation/currency_form.tpl');
	}
	
	protected function validateForm() { 
		if (!$this->user->hasPermission('modify', 'localisation/currency')) { 
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		} 
		
		$pass = true;
		
		if (!range_length($this->request->post['title'], 3, 32)) {
			$this->setMessage('error_title', L('error_title'));
			$pass = false;
		}

		if (utf8_strlen($this->request->post['code']) != 3) {
			$this->setMessage('error_code', L('error_code'));
			$pass = false;
		}
		
		return $pass;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}

		M('setting/store');
		M('sale/order');
		
		foreach ($this->request->post['selected'] as $currency_id) {
			$currency_info = $this->model_localisation_currency->getCurrency($currency_id);

			if ($currency_info) {
				if (C('config_currency') == $currency_info['code']) {
					$this->setMessage('error_warning', L('error_default'));
					return false;
				}
				
				$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);
	
				if ($store_total) {
					$this->setMessage('error_warning', sprintf(L('error_store'), $store_total));
					return false;
				}					
			}
			
			$order_total = $this->model_sale_order->getTotalOrdersByCurrencyId($currency_id);

			if ($order_total) {
				$this->setMessage('error_warning', sprintf(L('error_order'), $order_total));
				return false;
			}					
		}
		return true;
	}	
}
?>