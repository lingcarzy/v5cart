<?php
class ModelAffiliateAffiliate extends Model {
	public function addAffiliate($data) {
		$affiliate_data = array(
			'firstname'              => $data['firstname'],
			'lastname'               => $data['lastname'],
			'email'                  => $data['email'],
			'telephone'              => $data['telephone'],
			'fax'                    => $data['fax'],
			'company'                => $data['company'],
			'address_1'              => $data['address_1'],
			'address_2'              => $data['address_2'],
			'city'                   => $data['city'],
			'postcode'               => $data['postcode'],
			'country_id'             => $data['country_id'],
			'zone_id'                => $data['zone_id'],
			'code'                   => uniqid(),
			'commission'             => C('config_commission'),
			'tax'                    => $data['tax'],
			'payment'                => $data['payment'],
			'cheque'                 => $data['cheque'],
			'paypal'                 => $data['paypal'],
			'bank_name'              => $data['bank_name'],
			'bank_branch_number'     => $data['bank_branch_number'],
			'bank_swift_code'        => $data['bank_swift_code'],
			'bank_account_name'      => $data['bank_account_name'],
			'bank_account_number'    => $data['bank_account_number'],
			'status'                 => 1,
			'date_added'             => 'NOW()',
		);
		
		$affiliate_data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$affiliate_data['password'] = sha1($affiliate_data['salt'] . sha1($affiliate_data['salt'] . sha1($password)));
		
      	$this->db->insert('affiliate', $affiliate_data);
		

		//Send mail
		$this->language->load('mail/affiliate');

		$subject = sprintf(L('text_subject'), C('config_name'));

		$message  = sprintf(L('text_welcome'), C('config_name')) . "\n\n";
		$message .= L('text_approval') . "\n";
		$message .= U('affiliate/login', '', 'SSL') . "\n\n";
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
		$mail->setTo($this->request->post['email']);
		$mail->setFrom(C('config_email'));
		$mail->setSender(C('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}

	public function editAffiliate($data) {
		$affiliate_data = array(
			'firstname'              => $data['firstname'],
			'lastname'               => $data['lastname'],
			'company'                => $data['company'],
			'email'                  => $data['email'],
			'telephone'              => $data['telephone'],
			'fax'                    => $data['fax'],
			'address_1'              => $data['address_1'],
			'address_2'              => $data['address_2'],
			'city'                   => $data['city'],
			'postcode'               => $data['postcode'],
			'country_id'             => $data['country_id'],
			'zone_id'                => $data['zone_id'],
		);
		
		$this->db->update('affiliate', $affiliate_data, array('affiliate_id' => $this->affiliate->getId()));
	}

	public function editPayment($data) {
		$payment_data = array(
			'tax'                   => $data['tax'],
			'payment'               => $data['payment'],
			'cheque'                => $data['cheque'],
			'paypal'                => $data['paypal'],
			'bank_name'             => $data['bank_name'],
			'bank_branch_number'    => $data['bank_branch_number'],
			'bank_swift_code'       => $data['bank_swift_code'],
			'bank_account_name'     => $data['bank_account_name'],
			'bank_account_number'   => $data['bank_account_number'],
		);
		
		$this->db->update('affiliate', $payment_data, array('affiliate_id' => $this->affiliate->getId()));
	}

	public function editPassword($email, $password) {
		$data = array();
		$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($password)));

		$this->db->update('affiliate', $data, array('email' => $email));
	}

	public function getAffiliate($affiliate_id) {
		return $this->db->get("affiliate", array('affiliate_id' => $affiliate_id));
	}

	public function getAffiliateByEmail($email) {
		return $this->db->get("affiliate", array('email' => $email));
	}

	public function getAffiliateByCode($code) {
		return $this->db->get("affiliate", array('code' => $code));
	}

	public function getTotalAffiliatesByEmail($email) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@affiliate WHERE email = '" . ES($email) . "'");
	}
}
?>