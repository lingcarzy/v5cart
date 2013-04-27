<?php
class ControllerAccountVoucher extends Controller {
	
	public function index() {
		$this->language->load('account/voucher');

		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
		if (!isset($this->session->data['vouchers'])) {
			$this->session->data['vouchers'] = array();
		}

    	if ($this->request->isPost() && $this->validate()) {
			$this->session->data['vouchers'][mt_rand()] = array(
				'description'      => sprintf(L('text_for'), $this->currency->format($this->currency->convert($this->request->post['amount'], $this->currency->getCode(), C('config_currency'))), $this->request->post['to_name']),
				'to_name'          => $this->request->post['to_name'],
				'to_email'         => $this->request->post['to_email'],
				'from_name'        => $this->request->post['from_name'],
				'from_email'       => $this->request->post['from_email'],
				'voucher_theme_id' => $this->request->post['voucher_theme_id'],
				'message'          => $this->request->post['message'],
				'amount'           => $this->currency->convert($this->request->post['amount'], $this->currency->getCode(), C('config_currency'))
			);

	  		$this->redirect(U('account/voucher/success'));
    	}

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
        	'text'      => L('text_voucher'),
			'href'      => U('account/voucher', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_description'] = L('text_description');
		$this->data['text_agree'] = L('text_agree');

		$this->data['entry_to_name'] = L('entry_to_name');
		$this->data['entry_to_email'] = L('entry_to_email');
		$this->data['entry_from_name'] = L('entry_from_name');
		$this->data['entry_from_email'] = L('entry_from_email');
		$this->data['entry_theme'] = L('entry_theme');
		$this->data['entry_message'] = L('entry_message');
		$this->data['entry_amount'] = sprintf(L('entry_amount'), $this->currency->format(C('config_voucher_min')), $this->currency->format(C('config_voucher_max')));

		$this->data['button_continue'] = L('button_continue');
		
		$this->data['action'] = U('account/voucher', '', 'SSL');

		$this->data['to_name'] = P('to_name');
		$this->data['to_email'] = P('to_email');

		if (isset($this->request->post['from_name'])) {
			$this->data['from_name'] = $this->request->post['from_name'];
		} elseif ($this->customer->isLogged()) {
			$this->data['from_name'] = $this->customer->getFirstName() . ' '  . $this->customer->getLastName();
		} else {
			$this->data['from_name'] = '';
		}

		if (isset($this->request->post['from_email'])) {
			$this->data['from_email'] = $this->request->post['from_email'];
		} elseif ($this->customer->isLogged()) {
			$this->data['from_email'] = $this->customer->getEmail();
		} else {
			$this->data['from_email'] = '';
		}

 		M('checkout/voucher_theme');

		$this->data['voucher_themes'] = $this->model_checkout_voucher_theme->getVoucherThemes();

    	$this->data['voucher_theme_id'] = P('voucher_theme_id');
		$this->data['message'] = P('message');

		if (isset($this->request->post['amount'])) {
			$this->data['amount'] = P('amount');
		} else {
			$this->data['amount'] = $this->currency->format(25, C('config_currency'), false, false);
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

		$this->display('account/voucher.tpl');
  	}

  	public function success() {
		$this->language->load('account/voucher');

		$this->document->setTitle(L('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('heading_title'),
			'href'      => U('account/voucher'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

    	$this->data['text_message'] = L('text_message');

    	$this->data['button_continue'] = L('button_continue');

    	$this->data['continue'] = U('checkout/cart');

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
    	$p = true;
		
		if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
      		$this->setMessage('error_to_name', L('error_to_name'));
			$p = false;
    	}

		if ((utf8_strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['to_email'])) {
      		$this->setMessage('error_to_email', L('error_email'));
			$p = false;
    	}

    	if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
      		$this->setMessage('error_from_name', L('error_from_name'));
			$p = false;
    	}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['from_email'])) {
      		$this->setMessage('error_from_email', L('error_email'));
			$p = false;
    	}

		if (!isset($this->request->post['voucher_theme_id'])) {
      		$this->setMessage('error_theme', L('error_theme'));
			$p = false;
    	}

		if (($this->currency->convert($this->request->post['amount'], $this->currency->getCode(), C('config_currency')) < C('config_voucher_min')) || ($this->currency->convert($this->request->post['amount'], $this->currency->getCode(), C('config_currency')) > C('config_voucher_max'))) {
      		$this->setMessage('error_amount', sprintf(L('error_amount'), $this->currency->format(C('config_voucher_min')), $this->currency->format(C('config_voucher_max')) . ' ' . $this->currency->getCode()));
			$p = false;
    	}

		if (!isset($this->request->post['agree'])) {
      		$this->setMessage('error_warning', L('error_agree'));
			$p = false;
		}

    	return;
	}
}
?>