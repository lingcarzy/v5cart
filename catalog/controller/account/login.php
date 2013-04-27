<?php
class ControllerAccountLogin extends Controller {

	public function index() {
		M('account/customer');

		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->customer->logout();

			$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_country_id']);
			unset($this->session->data['shipping_zone_id']);
			unset($this->session->data['shipping_postcode']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address_id']);
			unset($this->session->data['payment_country_id']);
			unset($this->session->data['payment_zone_id']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

		 	if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
				// Default Addresses
				M('account/address');

				$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

				if ($address_info) {
					if (C('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_country_id'] = $address_info['country_id'];
						$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
						$this->session->data['shipping_postcode'] = $address_info['postcode'];
					}

					if (C('config_tax_customer') == 'payment') {
						$this->session->data['payment_country_id'] = $address_info['country_id'];
						$this->session->data['payment_zone_id'] = $address_info['zone_id'];
					}
				} else {
					unset($this->session->data['shipping_country_id']);
					unset($this->session->data['shipping_zone_id']);
					unset($this->session->data['shipping_postcode']);
					unset($this->session->data['payment_country_id']);
					unset($this->session->data['payment_zone_id']);
				}

				$this->redirect(U('account/account', '', 'SSL'));
			}
		}

		if ($this->customer->isLogged()) {
      		$this->redirect(U('account/account', '', 'SSL'));
    	}

    	$this->language->load('account/login');

    	$this->document->setTitle(L('heading_title'));

		if ($this->request->isPost() && $this->validate()) {
			unset($this->session->data['guest']);

			// Default Shipping Address
			M('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

			if ($address_info) {
				if (C('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_country_id'] = $address_info['country_id'];
					$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
					$this->session->data['shipping_postcode'] = $address_info['postcode'];
				}

				if (C('config_tax_customer') == 'payment') {
					$this->session->data['payment_country_id'] = $address_info['country_id'];
					$this->session->data['payment_zone_id'] = $address_info['zone_id'];
				}
			} else {
				unset($this->session->data['shipping_country_id']);
				unset($this->session->data['shipping_zone_id']);
				unset($this->session->data['shipping_postcode']);
				unset($this->session->data['payment_country_id']);
				unset($this->session->data['payment_zone_id']);
			}

			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], C('config_url')) !== false || strpos($this->request->post['redirect'], C('config_ssl')) !== false)) {
				$this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->redirect(U('account/account', '', 'SSL'));
			}
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
        	'text'      => L('text_login'),
			'href'      => U('account/login', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		$this->data['heading_title'] = L('heading_title');
		$this->document->addScript('catalog/view/javascript/jquery/jquery.validate.js');

    	$this->data['text_new_customer'] = L('text_new_customer');
    	$this->data['text_register'] = L('text_register');
    	$this->data['text_register_account'] = L('text_register_account');
		$this->data['text_returning_customer'] = L('text_returning_customer');
		$this->data['text_i_am_returning_customer'] = L('text_i_am_returning_customer');
    	$this->data['text_forgotten'] = L('text_forgotten');

    	$this->data['entry_email'] = L('entry_email');
    	$this->data['entry_password'] = L('entry_password');

    	$this->data['button_continue'] = L('button_continue');
		$this->data['button_login'] = L('button_login');

		$this->data['action'] = U('account/login', '', 'SSL');
		$this->data['register'] = U('account/register', '', 'SSL');
		$this->data['forgotten'] = U('account/forgotten', '', 'SSL');

    	// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], C('config_url')) !== false || strpos($this->request->post['redirect'], C('config_ssl')) !== false)) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
      		$this->data['redirect'] = $this->session->data['redirect'];
			unset($this->session->data['redirect']);
    	} else {
			$this->data['redirect'] = '';
		}

		$this->data['success'] = $this->session->flashdata('success');

		$this->data['email'] = P('email');
		$this->data['password'] = P('password');

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->display('account/login.tpl');
  	}

  	protected function validate() {
		if (!range_length($this->request->post['email'], 5, 96) 
			|| !range_length($this->request->post['password'], 4, 20)
			|| !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->request->post['email'])
		) {
			$this->setMessage('error_warning', L('error_login'));
			return false;
		}
		
    	if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
      		$this->setMessage('error_warning', L('error_login'));
			return false;
    	}

		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

    	if ($customer_info && !$customer_info['approved']) {
      		$this->setMessage('error_warning', L('error_approved'));
			return false;
    	}

   		return true;
  	}
}
?>