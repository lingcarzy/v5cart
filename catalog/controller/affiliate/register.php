<?php
class ControllerAffiliateRegister extends Controller {
	
  	public function index() {
		if ($this->affiliate->isLogged()) {
	  		$this->redirect(U('affiliate/account', '', 'SSL'));
    	}

    	$this->language->load('affiliate/register');

		$this->document->setTitle(L('heading_title'));

		M('affiliate/affiliate');

    	if ($this->request->isPost() && $this->validate()) {
			$this->model_affiliate_affiliate->addAffiliate($this->request->post);

			$this->affiliate->login($this->request->post['email'], $this->request->post['password']);

	  		$this->redirect(U('affiliate/success'));
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
        	'text'      => L('text_register'),
			'href'      => U('affiliate/register', '', 'SSL'),
        	'separator' => L('text_separator')
      	);
		
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
    	$this->data['heading_title'] = L('heading_title');

		$this->data['text_select'] = L('text_select');
		$this->data['text_none'] = L('text_none');
		$this->data['text_account_already'] = sprintf(L('text_account_already'), U('affiliate/login', '', 'SSL'));
    	$this->data['text_signup'] = L('text_signup');
		$this->data['text_your_details'] = L('text_your_details');
    	$this->data['text_your_address'] = L('text_your_address');
		$this->data['text_payment'] = L('text_payment');
    	$this->data['text_your_password'] = L('text_your_password');
		$this->data['text_cheque'] = L('text_cheque');
		$this->data['text_paypal'] = L('text_paypal');
		$this->data['text_bank'] = L('text_bank');

    	$this->data['entry_firstname'] = L('entry_firstname');
    	$this->data['entry_lastname'] = L('entry_lastname');
    	$this->data['entry_email'] = L('entry_email');
    	$this->data['entry_telephone'] = L('entry_telephone');
    	$this->data['entry_fax'] = L('entry_fax');
    	$this->data['entry_company'] = L('entry_company');
		$this->data['entry_website'] = L('entry_website');
    	$this->data['entry_address_1'] = L('entry_address_1');
    	$this->data['entry_address_2'] = L('entry_address_2');
    	$this->data['entry_postcode'] = L('entry_postcode');
    	$this->data['entry_city'] = L('entry_city');
    	$this->data['entry_country'] = L('entry_country');
    	$this->data['entry_zone'] = L('entry_zone');
		$this->data['entry_tax'] = L('entry_tax');
		$this->data['entry_payment'] = L('entry_payment');
		$this->data['entry_cheque'] = L('entry_cheque');
		$this->data['entry_paypal'] = L('entry_paypal');
		$this->data['entry_bank_name'] = L('entry_bank_name');
		$this->data['entry_bank_branch_number'] = L('entry_bank_branch_number');
		$this->data['entry_bank_swift_code'] = L('entry_bank_swift_code');
		$this->data['entry_bank_account_name'] = L('entry_bank_account_name');
		$this->data['entry_bank_account_number'] = L('entry_bank_account_number');
    	$this->data['entry_password'] = L('entry_password');
    	$this->data['entry_confirm'] = L('entry_confirm');

		$this->data['button_continue'] = L('button_continue');

    	$this->data['action'] = U('affiliate/register', '', 'SSL');

   		$this->data['firstname'] = P('firstname');
		$this->data['lastname'] = P('lastname');
		$this->data['email'] = P('email');
		$this->data['telephone'] = P('telephone');
		$this->data['fax'] = P('fax');
		$this->data['company'] = P('company');
		$this->data['website'] = P('website');
		$this->data['address_1'] = P('address_1');
		$this->data['address_2'] = P('address_2');
		$this->data['postcode'] = P('postcode');
		$this->data['city'] = P('city');
		$this->data['country_id'] = P('country_id');
		$this->data['zone_id'] = P('zone_id');
		$this->data['tax'] = P('tax');
		$this->data['payment'] = P('payment', 'cheque');
		$this->data['cheque'] = P('cheque');
		$this->data['paypal'] = P('paypal');
		$this->data['bank_name'] = P('bank_name');
		$this->data['bank_branch_number'] = P('bank_branch_number');
		$this->data['bank_swift_code'] = P('bank_swift_code');
		$this->data['bank_account_name'] = P('bank_account_name');
		$this->data['bank_account_number'] = P('bank_account_number');
		$this->data['password'] = P('password');
		$this->data['confirm'] = P('confirm');
		
    	$this->data['countries'] = cache_read('country.php');

		if (C('config_affiliate_id')) {
			M('catalog/page');

			$page_info = $this->model_catalog_page->getPage(C('config_affiliate_id'));

			if ($page_info) {
				$this->data['text_agree'] = sprintf(L('text_agree'), U('page/index/info', 'page_id=' . C('config_affiliate_id'), 'SSL'), $page_info['title'], $page_info['title']);
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

		$this->display('affiliate/register.tpl');
  	}

  	protected function validate() {
		if ($this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->post['email'])) {
      		$this->setMessage('error_warning', L('error_exists'));
			return false;
    	}
		
		if (C('config_affiliate_id')) {
			M('catalog/page');
			$page_info = $this->model_catalog_page->getPage(C('config_affiliate_id'));
			
			if ($page_info && !isset($this->request->post['agree'])) {
      			$this->setMessage('error_warning', sprintf(L('error_agree'), $page_info['title']));
				return false;
			}
		}
		
		$this->load->library('form_validation', true);
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));		
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));
		
		$this->form_validation->set_rules('email', '', 'required|range_length[5,96]|email', L('error_email'));
		
		$this->form_validation->set_rules('telephone', '', 'required|range_length[3,32]', L('error_telephone'));
		
		$this->form_validation->set_rules('address_1', '', 'required|range_length[3,128]', L('error_address_1'));
		
		$this->form_validation->set_rules('city', '', 'required|range_length[2,128]', L('error_city'));
		
		
		M('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required']) {
			$this->form_validation->set_rules('postcode', '', 'required|range_length[2,10]', L('error_postcode'));	
		}
		
		$this->form_validation->set_rules('country_id', '', 'required', L('error_country'));
		$this->form_validation->set_rules('zone_id', '', 'required', L('error_zone'));

		$this->form_validation->set_rules('password', '', 'required|range_length[4,20]', L('error_password'));
		$this->form_validation->set_rules('confirm', '', 'required|matches[password]', L('error_confirm'));

    	return $this->form_validation->run();
  	}
}
?>