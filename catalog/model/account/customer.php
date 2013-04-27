<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		if (isset($data['customer_group_id'])
			&& is_array(C('config_customer_group_display'))
			&& in_array($data['customer_group_id'], C('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		M('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		
		$customer_data = array(
			'store_id'               => C('config_store_id'),
			'firstname'              => $data['firstname'],
			'lastname'               => $data['lastname'],
			'email'                  => $data['email'],
			'telephone'              => $data['telephone'],
			'fax'                    => $data['fax'],
			'salt'                   => substr(md5(uniqid(rand(), true)), 0, 9),
			'newsletter'             => isset($data['newsletter']) ? (int)$data['newsletter'] : 0,
			'customer_group_id'      => $customer_group_id,
			'ip'                     => $this->request->server['REMOTE_ADDR'],
			'status'                 => 1,
			'approved'               => !$customer_group_info['approval'],
			'date_added'             => 'NOW()'
		);
		
		$customer_data['password'] = sha1($customer_data['salt'] . sha1($customer_data['salt'] . sha1($data['password'])));

		$customer_id = $this->db->insert('customer', $customer_data);
		
		if (C('config_register_address')) {
			$address_data = array(
				'customer_id'            => $customer_id,
				'firstname'              => $data['firstname'],
				'lastname'               => $data['lastname'],
				'company'                => $data['company'],
				'company_id'             => $data['company_id'],
				'tax_id'                 => $data['tax_id'],
				'address_1'              => $data['address_1'],
				'address_2'              => $data['address_2'],
				'city'                   => $data['city'],
				'postcode'               => $data['postcode'],
				'country_id'             => $data['country_id'],
				'zone_id'                => $data['zone_id'],
			);
			
			$address_id = $this->db->insert('address', $address_data);

			$this->db->query("UPDATE @@customer SET address_id = $address_id WHERE customer_id = $customer_id");
		}
		//Send mail
		$this->language->load('mail/customer');

		$subject = sprintf(L('text_subject'), C('config_name'));

		$message = sprintf(L('text_welcome'), C('config_name')) . "\n\n";
		
		if (!$customer_group_info['approval']) {
			$message .= L('text_login') . "\n";
		} else {
			$message .= L('text_approval') . "\n";
		}

		$message .= U('account/login', '', 'SSL') . "\n\n";
		$message .= L('text_services') . "\n\n";
		$message .= L('text_thanks') . "\n";
		$message .= C('config_name');

		$mail = new Mail();
		$mail->protocol = C('config_mail_protocol');
		$mail->parameter = C('config_mail_parameter');
		$mail->hostname = C('config_smtp_host');
		$mail->username = C('config_smtp_username');
		$mail->password = C('config_smtp_password');
		$mail->port = C('config_smtp_port');
		$mail->timeout = C('config_smtp_timeout');
		$mail->setTo($data['email']);
		$mail->setFrom(C('config_email'));
		$mail->setSender(C('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if (C('config_account_mail')) {
			$message  = L('text_signup') . "\n\n";
			$message .= L('text_website') . ' ' . C('config_name') . "\n";
			$message .= L('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= L('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= L('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";

			if ($data['company']) {
				$message .= L('text_company') . ' '  . $data['company'] . "\n";
			}

			$message .= L('text_email') . ' '  .  $data['email'] . "\n";
			$message .= L('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo(C('config_email'));
			$mail->setSubject(html_entity_decode(L('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', C('config_alert_emails'));

			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}

	public function editCustomer($data) {
		$customer_data = array(
			'firstname'  => $data['firstname'],
			'lastname'   => $data['lastname'],
			'email'      => $data['email'],
			'telephone'  => $data['telephone'],
			'fax'        => $data['fax']
		);
		
		$this->db->update('customer', $customer_data, array('customer_id' => $this->customer->getId()));
	}

	public function editPassword($email, $password) {
		$data = array();
		$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($password)));
		
		$this->db->update('customer', $data, array('email' => $email));
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE @@customer SET newsletter = " . (int)$newsletter . " WHERE customer_id = " . (int)$this->customer->getId());
	}

	public function getCustomer($customer_id) {
		return $this->db->get("customer", array('customer_id' => $customer_id));
	}

	public function getCustomerByEmail($email) {
		return $this->db->get("customer", array('email' => $email));
	}

	public function getCustomerByToken($token) {
		$customer = $this->db->queryOne("SELECT * FROM @@customer WHERE token = '" . ES($token) . "' AND token != ''");

		$this->db->query("UPDATE @@customer SET token = ''");

		return $customer;
	}

	public function getTotalCustomersByEmail($email) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@customer WHERE email = '" . ES($email) . "'");
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `@@customer_ip` WHERE customer_id = " . (int)$customer_id);
		
		return $query->rows;
	}

	public function isBlacklisted($ip) {
		return $this->db->queryOne("SELECT COUNT(*) as total FROM `@@customer_ip_blacklist` WHERE ip = '" . ES($ip) . "'");
	}
}
?>