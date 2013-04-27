<?php
class ControllerAffiliateTransaction extends Controller {
	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = U('affiliate/transaction', '', 'SSL');

	  		$this->redirect(U('affiliate/login', '', 'SSL'));
    	}

		$this->language->load('affiliate/transaction');

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
        	'text'      => L('text_transaction'),
			'href'      => U('affiliate/transaction', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		M('affiliate/transaction');

    	$this->data['heading_title'] = L('heading_title');

		$this->data['column_date_added'] = L('column_date_added');
		$this->data['column_description'] = L('column_description');
		$this->data['column_amount'] = sprintf(L('column_amount'), C('config_currency'));

		$this->data['text_balance'] = L('text_balance');
		$this->data['text_empty'] = L('text_empty');

		$this->data['button_continue'] = L('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['transactions'] = array();

		$data = array(
			'sort'  => 't.date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$transaction_total = $this->model_affiliate_transaction->getTotalTransactions($data);

		$results = $this->model_affiliate_transaction->getTransactions($data);

    	foreach ($results as $result) {
			$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], C('config_currency')),
				'description' => $result['description'],
				'date_added'  => date(L('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = L('text_pagination');
		$pagination->url = U('affiliate/transaction', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['balance'] = $this->currency->format($this->model_affiliate_transaction->getBalance());

		$this->data['continue'] = U('affiliate/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('affiliate/transaction.tpl');
	}
}
?>