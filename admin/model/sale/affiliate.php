<?php
class ModelSaleAffiliate extends Model {
	public function addAffiliate($data) {
		$data['date_added'] = 'NOW()';
		$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($data['password'])));

		$this->db->insert('affiliate', $data);
	}

	public function editAffiliate($affiliate_id, $data) {
		if (!empty($data['password'])) {
			$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
			$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($data['password'])));
		} else unset($data['password']);
		
		$this->db->update('affiliate', $data, array('affiliate_id' => $affiliate_id));
	}

	public function deleteAffiliate($affiliate_id) {
		$this->db->query("DELETE FROM  @@affiliate WHERE affiliate_id = " . (int)$affiliate_id);
		$this->db->query("DELETE FROM  @@affiliate_transaction WHERE affiliate_id = " . (int)$affiliate_id);
	}

	public function getAffiliate($affiliate_id) {
		return $this->db->queryOne("SELECT * FROM  @@affiliate WHERE affiliate_id = " . (int)$affiliate_id);
	}

	public function getAffiliateByEmail($email) {
		return $this->db->queryOne("SELECT * FROM  @@affiliate WHERE email = '" . ES($email) . "'");
	}

	public function getAffiliates($data = array()) {
		$sql = "SELECT *, CONCAT(a.firstname, ' ', a.lastname) AS name, (SELECT SUM(at.amount) FROM  @@affiliate_transaction at WHERE at.affiliate_id = a.affiliate_id GROUP BY at.affiliate_id) AS balance FROM  @@affiliate a";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . ES(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "a.email = '" . ES($data['filter_email']) . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "a.code = '" . ES($data['filter_code']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(a.date_added) = DATE('" . ES($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'a.email',
			'a.code',
			'a.status',
			'a.approved',
			'a.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function approve($affiliate_id) {
		$affiliate_info = $this->getAffiliate($affiliate_id);

		if ($affiliate_info) {
			$this->db->query("UPDATE  @@affiliate SET approved = 1 WHERE affiliate_id = " . (int)$affiliate_id);

			$this->language->load('mail/affiliate');

			$message  = sprintf(L('text_approve_welcome'), C('config_name')) . "\n\n";
			$message .= L('text_approve_login') . "\n";
			$message .= HTTP_CATALOG . 'index.php?route=affiliate/login' . "\n\n";
			$message .= L('text_approve_services') . "\n\n";
			$message .= L('text_approve_thanks') . "\n";
			$message .= C('config_name');

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender(C('config_name'));
			$mail->setSubject(html_entity_decode(sprintf(L('text_approve_subject'), C('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getAffiliatesByNewsletter() {
		$query = $this->db->query("SELECT * FROM  @@affiliate WHERE newsletter = '1' ORDER BY firstname, lastname, email");

		return $query->rows;
	}

	public function getTotalAffiliates($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM  @@affiliate";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . ES($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email = '" . ES($data['filter_email']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . ES($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		return $this->db->queryOne($sql);
	}

	public function getTotalAffiliatesAwaitingApproval() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@affiliate WHERE status = 0 OR approved = 0");
	}

	public function getTotalAffiliatesByCountryId($country_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@affiliate WHERE country_id = " . (int)$country_id);
	}

	public function getTotalAffiliatesByZoneId($zone_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@affiliate WHERE zone_id = " . (int)$zone_id);
	}

	public function addTransaction($affiliate_id, $description = '', $amount = '', $order_id = 0) {
		$affiliate_info = $this->getAffiliate($affiliate_id);

		if ($affiliate_info) {
			$this->db->query("INSERT INTO  @@affiliate_transaction SET affiliate_id = '" . (int)$affiliate_id . "', order_id = '" . (float)$order_id . "', description = '" . ES($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

			$this->language->load('mail/affiliate');

			$message  = sprintf(L('text_transaction_received'), $this->currency->format($amount, C('config_currency'))) . "\n\n";
			$message .= sprintf(L('text_transaction_total'), $this->currency->format($this->getTransactionTotal($affiliate_id), C('config_currency')));

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender(C('config_name'));
			$mail->setSubject(html_entity_decode(sprintf(L('text_transaction_subject'), C('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function deleteTransaction($order_id) {
		$this->db->query("DELETE FROM  @@affiliate_transaction WHERE order_id = " . (int)$order_id);
	}

	public function getTransactions($affiliate_id, $start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 10;

		$query = $this->db->query("SELECT * FROM  @@affiliate_transaction WHERE affiliate_id = " . (int)$affiliate_id . " ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalTransactions($affiliate_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total  FROM  @@affiliate_transaction WHERE affiliate_id = " . (int)$affiliate_id);
	}

	public function getTransactionTotal($affiliate_id) {
		return $this->db->queryOne("SELECT SUM(amount) AS total FROM  @@affiliate_transaction WHERE affiliate_id = " . (int)$affiliate_id);
	}

	public function getTotalTransactionsByOrderId($order_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@affiliate_transaction WHERE order_id = " . (int)$order_id);
	}
}
?>