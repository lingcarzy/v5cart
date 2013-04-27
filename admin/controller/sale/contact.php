<?php
class ControllerSaleContact extends Controller {

	public function index() {
		$this->language->load('sale/contact');

		$this->document->setTitle(L('heading_title'));

    	$this->data['cancel'] = UA('sale/contact');

		M('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		M('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('sale/contact.tpl');
	}

	public function send() {
		$this->language->load('sale/contact');

		$json = array();

		if ($this->request->isPost()) {
			if (!$this->user->hasPermission('modify', 'sale/contact')) {
				$json['error']['warning'] = L('error_permission');
			}

			if (!$this->request->post['subject']) {
				$json['error']['subject'] = L('error_subject');
			}

			if (!$this->request->post['message']) {
				$json['error']['message'] = L('error_message');
			}

			if (!$json) {
				M('setting/store');

				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = C('config_name');
				}

				M('sale/customer');
				M('sale/customer_group');
				M('sale/affiliate');
				M('sale/order');

				$page = G('page', 1);
				$email_total = 0;

				$emails = array();

				switch ($this->request->post['to']) {
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);

						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_all':
						$customer_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 10
						);

						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);

						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_sale_customer->getCustomer($customer_id);

								if ($customer_info) {
									$emails[] = $customer_info['email'];
								}
							}
						}
						break;
					case 'affiliate_all':
						$affiliate_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 10
						);

						$email_total = $this->model_sale_affiliate->getTotalAffiliates($affiliate_data);

						$results = $this->model_sale_affiliate->getAffiliates($affiliate_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'affiliate':
						if (!empty($this->request->post['affiliate'])) {
							foreach ($this->request->post['affiliate'] as $affiliate_id) {
								$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

								if ($affiliate_info) {
									$emails[] = $affiliate_info['email'];
								}
							}
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

							foreach ($results as $result) {
								$emails[] = $result['email'];
							}
						}
						break;
				}

				if ($emails) {
					$start = ($page - 1) * 10;
					$end = $start + 10;

					if ($end < $email_total) {
						$json['success'] = sprintf(L('text_sent'), $start, $email_total);
					} else {
						$json['success'] = L('text_success');
					}

					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', UA('sale/contact/send', 'page=' . ($page + 1)));
					} else {
						$json['next'] = '';
					}

					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";

					foreach ($emails as $email) {
						$mail = new Mail();
						$mail->protocol = C('config_mail_protocol');
						$mail->parameter = C('config_mail_parameter');
						$mail->hostname = C('config_smtp_host');
						$mail->username = C('config_smtp_username');
						$mail->password = C('config_smtp_password');
						$mail->port = C('config_smtp_port');
						$mail->timeout = C('config_smtp_timeout');
						$mail->setTo($email);
						$mail->setFrom(C('config_email'));
						$mail->setSender($store_name);
						$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
						$mail->setHtml($message);
						$mail->send();
					}
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>