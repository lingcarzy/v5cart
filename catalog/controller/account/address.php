<?php
class ControllerAccountAddress extends Controller {
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = U('account/address', '', 'SSL');
	  		$this->redirect(U('account/login', '', 'SSL'));
    	}
		
		$this->language->load('account/address');
		M('account/address');
	}
	
  	public function index() {		
		$this->getList();
  	}

  	public function insert() {

    	if ($this->request->isPost() && $this->validateForm()) {
			$this->model_account_address->addAddress($this->request->post);

      		$this->session->set_flashdata('success', L('text_insert'));
	  		$this->redirect(U('account/address', '', 'SSL'));
    	}

		$this->getForm();
  	}

  	public function update() {

    	if ($this->request->isPost() && $this->validateForm()) {
       		$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);

			// Default Shipping Address
			if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->set_flashdata('success', L('text_update'));
	  		$this->redirect(U('account/address', '', 'SSL'));
    	}

		$this->getForm();
  	}

  	public function delete() {

    	if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->get['address_id']);

			// Default Shipping Address
			if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
				unset($this->session->data['shipping_address_id']);
				unset($this->session->data['shipping_country_id']);
				unset($this->session->data['shipping_zone_id']);
				unset($this->session->data['shipping_postcode']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
				unset($this->session->data['payment_address_id']);
				unset($this->session->data['payment_country_id']);
				unset($this->session->data['payment_zone_id']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->set_flashdata('success', L('text_delete'));
	  		$this->redirect(U('account/address', '', 'SSL'));
    	}

		$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));
		
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
			'href'      => U('account/address', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

    	$this->data['text_address_book'] = L('text_address_book');
   
    	$this->data['button_new_address'] = L('button_new_address');
    	$this->data['button_edit'] = L('button_edit');
    	$this->data['button_delete'] = L('button_delete');
		$this->data['button_back'] = L('button_back');
		
		$this->data['success'] = $this->session->flashdata('success');

    	$this->data['addresses'] = array();

		$results = $this->model_account_address->getAddresses();

    	foreach ($results as $result) {
			if ($result['address_format']) {
      			$format = $result['address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);

			$replace = array(
	  			'firstname' => $result['firstname'],
	  			'lastname'  => $result['lastname'],
	  			'company'   => $result['company'],
      			'address_1' => $result['address_1'],
      			'address_2' => $result['address_2'],
      			'city'      => $result['city'],
      			'postcode'  => $result['postcode'],
      			'zone'      => $result['zone'],
				'zone_code' => $result['zone_code'],
      			'country'   => $result['country']
			);

      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
        		'update'     => U('account/address/update', 'address_id=' . $result['address_id'], 'SSL'),
				'delete'     => U('account/address/delete', 'address_id=' . $result['address_id'], 'SSL')
      		);
    	}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/address_list.tpl');
  	}

  	protected function getForm() {
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
			'href'      => U('account/address', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		if (!isset($this->request->get['address_id'])) {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => L('text_edit_address'),
				'href'      => U('account/address/insert', '', 'SSL'),
        		'separator' => L('text_separator')
      		);
		} else {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => L('text_edit_address'),
				'href'      => U('account/address/update', 'address_id=' . $this->request->get['address_id'], 'SSL'),
        		'separator' => L('text_separator')
      		);
		}
		
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
		$this->data['heading_title'] = L('heading_title');
    	
		$this->data['text_edit_address'] = L('text_edit_address');
    	$this->data['text_yes'] = L('text_yes');
    	$this->data['text_no'] = L('text_no');
		$this->data['text_select'] = L('text_select');
		$this->data['text_none'] = L('text_none');
		
    	$this->data['entry_firstname'] = L('entry_firstname');
    	$this->data['entry_lastname'] = L('entry_lastname');
    	$this->data['entry_company'] = L('entry_company');
		$this->data['entry_company_id'] = L('entry_company_id');
		$this->data['entry_tax_id'] = L('entry_tax_id');		
    	$this->data['entry_address_1'] = L('entry_address_1');
    	$this->data['entry_address_2'] = L('entry_address_2');
    	$this->data['entry_postcode'] = L('entry_postcode');
    	$this->data['entry_city'] = L('entry_city');
    	$this->data['entry_country'] = L('entry_country');
    	$this->data['entry_zone'] = L('entry_zone');
    	$this->data['entry_default'] = L('entry_default');

    	$this->data['button_continue'] = L('button_continue');
    	$this->data['button_back'] = L('button_back');
		
		$address_id = (int) G('address_id', 0);
		
		if ($address_id) {
    		$this->data['action'] = U('account/address/update', 'address_id=' . $address_id, 'SSL');
		} else {
    		$this->data['action'] = U('account/address/insert', '', 'SSL');
		}

    	if ($address_id && !$this->request->isPost()) {
			$address_info = $this->model_account_address->getAddress($address_id);
		}

		if (!empty($address_info)) {
			$this->data['firstname'] = $address_info['firstname'];
			$this->data['lastname'] = $address_info['lastname'];
			$this->data['company'] = $address_info['company'];
			$this->data['company_id'] = $address_info['company_id'];
			$this->data['tax_id'] = $address_info['tax_id'];
			$this->data['address_1'] = $address_info['address_1'];
			$this->data['address_2'] = $address_info['address_2'];
			$this->data['postcode'] = $address_info['postcode'];
			$this->data['city'] = $address_info['city'];
			$this->data['country_id'] = $address_info['country_id'];
			$this->data['zone_id'] = $address_info['zone_id'];
		}
		else {
			$this->data['firstname'] = P('firstname');
			$this->data['lastname'] = P('lastname');
			$this->data['company'] = P('company');
			$this->data['company_id'] = P('company_id');
			$this->data['tax_id'] = P('tax_id');
			$this->data['address_1'] = P('address_1');
			$this->data['address_2'] = P('address_2');
			$this->data['postcode'] = P('postcode');
			$this->data['city'] = P('city');
			$this->data['country_id'] = P('country_id', C('config_country_id'));
			$this->data['zone_id'] = P('zone_id');
		}

		M('account/customer_group', 'customer_group');
		$customer_group_info = $this->customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

		if ($customer_group_info) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['company_id_display'] = '';
			$this->data['tax_id_display'] = '';
		}

    	$this->data['countries'] = cache_read('country.php');

    	if (isset($this->request->post['default'])) {
      		$this->data['default'] = $this->request->post['default'];
    	} elseif ($address_id) {
      		$this->data['default'] = $this->customer->getAddressId() == $address_id;
    	} else {
			$this->data['default'] = false;
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		$this->display('account/address_form.tpl');
  	}

  	protected function validateForm() {
		$this->load->library('form_validation', true);
		
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));
		
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));
		
		$this->form_validation->set_rules('address_1', '', 'required|range_length[3,128]', L('error_address_1'));
		
		$this->form_validation->set_rules('city', '', 'required|range_length[2,128]', L('error_city'));

		M('localisation/country', 'country');

		$country_info = $this->country->getCountry($this->request->post['country_id']);

		if ($country_info) {
			if ($country_info['postcode_required']) {
				$this->form_validation->set_rules('postcode', '', 'required|range_length[2,10]', L('error_postcode'));
			}

			// VAT Validation
			$this->load->helper('vat');

			if (C('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
				$this->form_validation->set_rules('tax_id', '', 'required');
				$this->form_validation->set_error('tax_id', L('error_vat'));
			}
		}

    	$this->form_validation->set_rules('country_id', '', 'required', L('error_country'));
		$this->form_validation->set_rules('zone_id', '', 'required', L('error_zone'));

    	return $this->form_validation->run();
  	}

  	protected function validateDelete() {
    	if ($this->model_account_address->getTotalAddresses() == 1) {
      		$this->setMessage('error_warning', L('error_delete'));
			return false;
    	}

    	if ($this->customer->getAddressId() == $this->request->get['address_id']) {
      		$this->setMessage('error_warning', L('error_default'));
			return false;
    	}
		
		return true;
  	}
}
?>