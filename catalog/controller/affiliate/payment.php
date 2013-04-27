<?php
class ControllerAffiliatePayment extends Controller {

	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = U('affiliate/payment', '', 'SSL');

			$this->redirect(U('affiliate/login', '', 'SSL'));
		}

		$this->language->load('affiliate/payment');

		$this->document->setTitle(L('heading_title'));

		M('affiliate/affiliate');

		if ($this->request->isPost()) {
			$this->model_affiliate_affiliate->editPayment($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(U('affiliate/account', '', 'SSL'));
		}

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
        	'text'      => L('text_payment'),
			'href'      => U('affiliate/payment', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_your_payment'] = L('text_your_payment');
		$this->data['text_cheque'] = L('text_cheque');
		$this->data['text_paypal'] = L('text_paypal');
		$this->data['text_bank'] = L('text_bank');

		$this->data['entry_tax'] = L('entry_tax');
		$this->data['entry_payment'] = L('entry_payment');
		$this->data['entry_cheque'] = L('entry_cheque');
		$this->data['entry_paypal'] = L('entry_paypal');
		$this->data['entry_bank_name'] = L('entry_bank_name');
		$this->data['entry_bank_branch_number'] = L('entry_bank_branch_number');
		$this->data['entry_bank_swift_code'] = L('entry_bank_swift_code');
		$this->data['entry_bank_account_name'] = L('entry_bank_account_name');
		$this->data['entry_bank_account_number'] = L('entry_bank_account_number');

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_back'] = L('button_back');

		$this->data['action'] = U('affiliate/payment', '', 'SSL');

		if (!$this->request->isPost()) {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
		}

		if (!empty($affiliate_info)) {
			$this->data['tax'] = $affiliate_info['tax'];
			$this->data['payment'] = $affiliate_info['payment'];
			$this->data['cheque'] = $affiliate_info['cheque'];
			$this->data['paypal'] = $affiliate_info['paypal'];
			$this->data['bank_name'] = $affiliate_info['bank_name'];
			$this->data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
			$this->data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
			$this->data['bank_account_name'] = $affiliate_info['bank_account_name'];
			$this->data['bank_account_number'] = $affiliate_info['bank_account_number'];
		} else {
			$this->data['tax'] = P('tax');
			$this->data['payment'] = P('payment', 'cheque');
			$this->data['cheque'] = P('cheque');
			$this->data['paypal'] = P('paypal');
			$this->data['bank_name'] = P('bank_name');
			$this->data['bank_branch_number'] = P('bank_branch_number');
			$this->data['bank_swift_code'] = P('bank_swift_code');
			$this->data['bank_account_name'] = P('bank_account_name');
			$this->data['bank_account_number'] = P('bank_account_number');
		}

		$this->data['back'] = U('affiliate/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('affiliate/payment.tpl');
	}
}
?>