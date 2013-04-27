<?php 
class ControllerAccountSuccess extends Controller {  
	public function index() {
    	$this->language->load('account/success');
  
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
        	'text'      => L('text_success'),
			'href'      => U('account/success'),
        	'separator' => L('text_separator')
      	);

    	$this->data['heading_title'] = L('heading_title');

		M('account/customer_group');
		
		$customer_group = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

		if ($customer_group && !$customer_group['approval']) {
    		$this->data['text_message'] = sprintf(L('text_message'), U('page/contact'));
		} else {
			$this->data['text_message'] = sprintf(L('text_approval'), C('config_name'), U('page/contact'));
		}
		
    	$this->data['button_continue'] = L('button_continue');
		
		if ($this->cart->hasProducts()) {
			$this->data['continue'] = U('checkout/cart', '', 'SSL');
		} else {
			$this->data['continue'] = U('account/account', '', 'SSL');
		}
		
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
}
?>