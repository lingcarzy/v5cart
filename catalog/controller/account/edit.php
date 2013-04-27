<?php
class ControllerAccountEdit extends Controller {

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/edit', '', 'SSL');

			$this->redirect(U('account/login', '', 'SSL'));
		}

		$this->language->load('account/edit');
		
		$this->document->setTitle(L('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');
		
		M('account/customer');
		
		if ($this->request->isPost() && $this->validate()) {
			$this->model_account_customer->editCustomer($this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));

			$this->redirect(U('account/account', '', 'SSL'));
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
        	'text'      => L('text_edit'),
			'href'      => U('account/edit', '', 'SSL'),       	
        	'separator' => L('text_separator')
      	);
		
		$this->data['heading_title'] = L('heading_title');

		$this->data['text_your_details'] = L('text_your_details');

		$this->data['entry_firstname'] = L('entry_firstname');
		$this->data['entry_lastname'] = L('entry_lastname');
		$this->data['entry_email'] = L('entry_email');
		$this->data['entry_telephone'] = L('entry_telephone');
		$this->data['entry_fax'] = L('entry_fax');

		$this->data['button_continue'] = L('button_continue');
		$this->data['button_back'] = L('button_back');
		
		$this->data['action'] = U('account/edit', '', 'SSL');

		if (!$this->request->isPost()) {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$this->data['firstname'] = $customer_info['firstname'];
			$this->data['lastname'] = $customer_info['lastname'];
			$this->data['email'] = $customer_info['email'];
			$this->data['telephone'] = $customer_info['telephone'];
			$this->data['fax'] = $customer_info['fax'];
		}
		else {
			$this->data['firstname'] = $this->request->post['firstname'];
			$this->data['lastname'] = $this->request->post['lastname'];
			$this->data['email'] = $this->request->post['email'];
			$this->data['telephone'] = $this->request->post['telephone'];
			$this->data['fax'] = $this->request->post['fax'];
		}

		$this->data['back'] = U('account/account', '', 'SSL');
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);

		$this->display('account/edit.tpl');
	}

	protected function validate() {
		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->setMessage('error_warning', L('error_exists'));
			return false;
		}
		
		$this->load->library('form_validation', true);
		
		$this->form_validation->set_rules('firstname', '', 'required|range_length[1,32]', L('error_firstname'));
		
		$this->form_validation->set_rules('lastname', '', 'required|range_length[1,32]', L('error_lastname'));
		
		$this->form_validation->set_rules('email', '', 'required|range_length[5,96]|email', L('error_email'));

		$this->form_validation->set_rules('telephone', '', 'required|range_length[3,32]', L('error_telephone'));
		
		return $this->form_validation->run();
	}
}
?>