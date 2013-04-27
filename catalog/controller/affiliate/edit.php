<?php
class ControllerAffiliateEdit extends Controller {

	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = U('affiliate/edit', '', 'SSL');
			$this->redirect(U('affiliate/login', '', 'SSL'));
		}

		$this->language->load('affiliate/edit');

		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
		M('affiliate/affiliate');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_affiliate_affiliate->editAffiliate($this->request->post);
			
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
        	'text'      => L('text_edit'),
			'href'      => U('affiliate/edit', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');

		$this->data['text_select'] = L('text_select');
		$this->data['text_none'] = L('text_none');
		$this->data['text_your_details'] = L('text_your_details');
    	$this->data['text_your_address'] = L('text_your_address');

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

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_back'] = L('button_back');

		$this->data['action'] = U('affiliate/edit', '', 'SSL');

		if (!$this->request->isPost()) {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
		}

		if (!empty($affiliate_info)) {
			$this->data['firstname'] = $affiliate_info['firstname'];
			$this->data['lastname'] = $affiliate_info['lastname'];
			$this->data['email'] = $affiliate_info['email'];
			$this->data['telephone'] = $affiliate_info['telephone'];
			$this->data['fax'] = $affiliate_info['fax'];
			$this->data['company'] = $affiliate_info['company'];
			$this->data['website'] = $affiliate_info['website'];
			$this->data['address_1'] = $affiliate_info['address_1'];
			$this->data['address_2'] = $affiliate_info['address_2'];
			$this->data['postcode'] = $affiliate_info['postcode'];
			$this->data['city'] = $affiliate_info['city'];
			$this->data['country_id'] = $affiliate_info['country_id'];
			$this->data['zone_id'] = $affiliate_info['zone_id'];
		} else {
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
		}

    	$this->data['countries'] = cache_read('country.php');

		$this->data['back'] = U('affiliate/account', '', 'SSL');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('affiliate/edit.tpl');
	}

	protected function validate() {
		if (($this->affiliate->getEmail() != $this->request->post['email']) && $this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->post['email'])) {
			$this->setMessage('error_warning', L('error_exists'));
			return false;
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

		return $this->form_validation->run();
	}
}
?>