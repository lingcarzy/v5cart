<?php
class ControllerAccountRegister extends Controller {
	
  	public function index() {
		if ($this->customer->isLogged()) {
	  		$this->redirect(U('account/account', '', 'SSL'));
    	}

    	$this->language->load('account/register');

		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
		M('account/customer');

    	if ($this->request->isPost() && $this->validate()) {
			$this->model_account_customer->addCustomer($this->request->post);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);

			// Default Shipping Address
			if (C('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
			}

			// Default Payment Address
			if (C('config_tax_customer') == 'payment') {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
			}

	  		$this->redirect(U('account/success'));
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
        	'text'      => L('text_register'),
			'href'      => U('account/register', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');
		
		$this->data['register_address'] = C('config_register_address');
		
		$this->data['text_account_already'] = sprintf(L('text_account_already'), U('account/login', '', 'SSL'));
		$this->data['text_your_details'] = L('text_your_details');
    	$this->data['text_your_address'] = L('text_your_address');
    	$this->data['text_your_password'] = L('text_your_password');
		$this->data['text_newsletter'] = L('text_newsletter');
		$this->data['text_yes'] = L('text_yes');
		$this->data['text_no'] = L('text_no');
		$this->data['text_select'] = L('text_select');
		$this->data['text_none'] = L('text_none');

    	$this->data['entry_firstname'] = L('entry_firstname');
    	$this->data['entry_lastname'] = L('entry_lastname');
    	$this->data['entry_email'] = L('entry_email');
    	$this->data['entry_telephone'] = L('entry_telephone');
    	$this->data['entry_fax'] = L('entry_fax');
		$this->data['entry_company'] = L('entry_company');
		$this->data['entry_customer_group'] = L('entry_customer_group');
		$this->data['entry_company_id'] = L('entry_company_id');
		$this->data['entry_tax_id'] = L('entry_tax_id');
    	$this->data['entry_address_1'] = L('entry_address_1');
    	$this->data['entry_address_2'] = L('entry_address_2');
    	$this->data['entry_postcode'] = L('entry_postcode');
    	$this->data['entry_city'] = L('entry_city');
    	$this->data['entry_country'] = L('entry_country');
    	$this->data['entry_zone'] = L('entry_zone');
		$this->data['entry_newsletter'] = L('entry_newsletter');
    	$this->data['entry_password'] = L('entry_password');
    	$this->data['entry_confirm'] = L('entry_confirm');

		$this->data['button_continue'] = L('button_continue');

    	$this->data['action'] = U('account/register', '', 'SSL');

		$this->data['firstname'] = P('firstname');
		$this->data['lastname'] = P('lastname');
		$this->data['email'] = P('email');
		$this->data['telephone'] = P('telephone');
		$this->data['fax'] = P('fax');
		$this->data['company'] = P('company');
		

		M('account/customer_group');

		$this->data['customer_groups'] = array();

		if (is_array(C('config_customer_group_display'))) {
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], C('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		$this->data['customer_group_id'] = P('customer_group_id', C('config_customer_group_id'));

		// Company ID
		$this->data['company_id'] = P('company_id');

		// Tax ID
		$this->data['tax_id'] = P('tax_id');
		
		$this->data['address_1'] = P('address_1');
		$this->data['address_2'] = P('address_2');

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];
		} else {
			$this->data['postcode'] = '';
		}

		$this->data['city'] = P('city', '');

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];
		} else {
      		$this->data['country_id'] = C('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];
		} else {
      		$this->data['zone_id'] = '';
    	}

    	$this->data['countries'] = cache_read('country.php');

		$this->data['password'] = P('password');
		$this->data['confirm'] = P('confirm');
		$this->data['newsletter'] = P('newsletter');

		if (C('config_account_id')) {
			M('catalog/page');

			$page_info = $this->model_catalog_page->getPage(C('config_account_id'));

			if ($page_info) {
				$this->data['text_agree'] = sprintf(L('text_agree'), U('page/index/info', 'page_id=' . C('config_account_id'), 'SSL'), $page_info['title'], $page_info['title']);
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

		$this->display('account/register.tpl');
  	}

  	protected function validate() {
		$this->load->library('form_validation', true);
		
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));
    	
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));

    	$this->form_validation->set_rules('email', '', 'required|range_length[5,96]|email', L('error_email'));
    	
		$this->form_validation->set_rules('telephone', '', 'required|range_length[3,32]', L('error_telephone'));
		
		if (C('config_register_address')) {
			$this->form_validation->set_rules('address_1', '', 'required|range_length[3,128]', L('error_address_1'));
			
			$this->form_validation->set_rules('city', '', 'required|range_length[2,128]', L('error_city'));
			
			$this->form_validation->set_rules('country_id', '', 'required|integer', L('error_country'));
			
			$this->form_validation->set_rules('zone_id', '', 'required|integer', L('error_zone'));		
		}
		
		$this->form_validation->set_rules('password', '', 'required|range_length[4,20]', L('error_password'));
		
		$this->form_validation->set_rules('confirm', '', 'required|matches[password]', L('error_confirm'));

    	$p = true;
		
    	if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
      		$this->setMessage('error_warning', L('error_exists'));
			$p = false;
    	}

		// Customer Group
		if (C('config_register_address')) {
			M('account/customer_group');

			if (isset($this->request->post['customer_group_id']) && is_array(C('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], C('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = C('config_customer_group_id');
			}

			$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group) {
				// Company ID
				if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
					$this->setMessage('error_company_id', L('error_company_id'));
					$p = false;
				}

				// Tax ID
				if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
					$this->setMessage('error_tax_id', L('error_tax_id'));
					$p = false;
				}
			}

			

			M('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info) {
				if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
					$this->setMessage('error_postcode', L('error_postcode'));
					$p = false;
				}

				// VAT Validation
				$this->load->helper('vat');

				if (C('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
					$this->setMessage('error_tax_id', L('error_vat'));
					$p = false;
				}
			}

    	}

		if (C('config_account_id')) {
			M('catalog/page');

			$page_info = $this->model_catalog_page->getPage(C('config_account_id'));

			if ($page_info && !isset($this->request->post['agree'])) {
      			$this->setMessage('error_warning', sprintf(L('error_agree'), $page_info['title']));
				$p = false;
			}
		}
		
		$fv = $this->form_validation->run();
		
		return ($fv && $p);
  	}
}
?>