<?php
class ControllerAccountTransaction extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/transaction', '', 'SSL');

	  		$this->redirect(U('account/login', '', 'SSL'));
    	}

		$this->language->load('account/transaction');

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
        	'text'      => L('text_transaction'),
			'href'      => U('account/transaction', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		M('account/transaction');

		$this->data['heading_title'] = L('heading_title');

		$this->data['column_date_added'] = L('column_date_added');
		$this->data['column_description'] = L('column_description');
		$this->data['column_amount'] = sprintf(L('column_amount'), C('config_currency'));

		$this->data['text_total'] = L('text_total');
		$this->data['text_empty'] = L('text_empty');

		$this->data['button_continue'] = L('button_continue');

		$page = $this->request->get('page', 1);

		$this->data['transactions'] = array();

		$data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$transaction_total = $this->model_account_transaction->getTotalTransactions($data);

		$results = $this->model_account_transaction->getTransactions($data);

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
		$pagination->url = U('account/transaction', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['total'] = $this->currency->format($this->customer->getBalance());

		$this->data['continue'] = U('account/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/transaction.tpl');
	}
}
?>