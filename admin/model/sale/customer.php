<?php
class ModelSaleCustomer extends Model {
	public function addCustomer($data) {
		$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($data['password'])));
		$data['date_added'] = 'NOW()';
      	$customer_id = $this->db->insert('customer', $data);

      	if (isset($data['address'])) {
      		foreach ($data['address'] as $address) {
				$address['customer_id'] = $customer_id;
				$address_id = $this->db->insert('address', $address);
				if (isset($address['default'])) {
					$this->db->query("UPDATE  @@customer SET address_id = $address_id WHERE customer_id = $customer_id");
				}
			}
		}
	}

	public function editCustomer($customer_id, $data) {
		if ($data['password']) {
			$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
			$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($data['password'])));
		}
		else unset($data['password']);
		
		$this->db->update('customer', $data, array('customer_id' => $customer_id));

      	$this->db->query("DELETE FROM  @@address WHERE customer_id = " . (int)$customer_id);

      	if (isset($data['address'])) {
      		foreach ($data['address'] as $address) {
				$address['customer_id'] = $customer_id;
				$address_id = $this->db->insert('address', $address);
				if (isset($address['default'])) {
					$this->db->query("UPDATE  @@customer SET address_id = $address_id WHERE customer_id = $customer_id");
				}
			}
		}
	}

	public function editToken($customer_id, $token) {
		$this->db->update('customer',
			array('token' => $token),
			array('customer_id' => $customer_id)
		);
	}

	public function deleteCustomer($customer_id) {
		$this->db->query("DELETE FROM  @@customer WHERE customer_id = " . (int)$customer_id);
		$this->db->query("DELETE FROM  @@customer_reward WHERE customer_id = " . (int)$customer_id);
		$this->db->query("DELETE FROM  @@customer_transaction WHERE customer_id = " . (int)$customer_id);
		$this->db->query("DELETE FROM  @@customer_ip WHERE customer_id = " . (int)$customer_id);
		$this->db->query("DELETE FROM  @@address WHERE customer_id = " . (int)$customer_id);
	}

	public function getCustomer($customer_id) {
		return  $this->db->get('customer', array('customer_id' => $customer_id));
	}

	public function getCustomerByEmail($email) {
		return  $this->db->get('customer', array('email' => $email));
	}

	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM  @@customer c LEFT JOIN  @@customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = " . (int)C('config_language_id');

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . ES($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . ES($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "c.newsletter = " . (int)$data['filter_newsletter'];
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = " . (int)$data['filter_customer_group_id'];
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM  @@customer_ip WHERE ip = '" . ES($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = " . (int)$data['filter_status'];
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = " . (int)$data['filter_approved'];
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . ES($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
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

	public function approve($customer_id) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("UPDATE  @@customer SET approved = 1 WHERE customer_id = " . (int)$customer_id);

			$this->language->load('mail/customer');

			M('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = C('config_name');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$message  = sprintf(L('text_approve_welcome'), $store_name) . "\n\n";
			$message .= L('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= L('text_approve_services') . "\n\n";
			$message .= L('text_approve_thanks') . "\n";
			$message .= $store_name;

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf(L('text_approve_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getAddress($address_id) {
		$address = $this->db->queryOne("SELECT * FROM  @@address WHERE address_id = " . (int)$address_id);

		if ($address) {
			$country = $this->db->queryOne("SELECT * FROM `@@country` WHERE country_id = " . $address['country_id']);

			if ($country) {
				$address['country'] = $country['name'];
				$address['iso_code_2'] = $country['iso_code_2'];
				$address['iso_code_3'] = $country['iso_code_3'];
				$address['address_format'] = $country['address_format'];
			} else {
				$address['country'] = '';
				$address['iso_code_2'] = '';
				$address['iso_code_3'] = '';
				$address['address_format'] = '';
			}

			$zone = $this->db->queryOne("SELECT * FROM `@@zone` WHERE zone_id = " . $address['zone_id']);

			if ($zone) {
				$address['zone'] = $zone['name'];
				$address['zone_code'] = $zone['code'];
			} else {
				$address['zone'] = '';
				$address['zone_code'] = '';
			}
		}
		return $address;
	}

	public function getAddresses($customer_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM  @@address WHERE customer_id = " . (int)$customer_id);

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getTotalCustomers($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM  @@customer";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . ES($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . ES($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = " . (int)$data['filter_newsletter'];
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = " . (int)$data['filter_customer_group_id'];
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "customer_id IN (SELECT customer_id FROM  @@customer_ip WHERE ip = '" . ES($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = " . (int)$data['filter_status'];
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = " . (int)$data['filter_approved'];
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . ES($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		return $this->db->queryOne($sql);
	}

	public function getTotalCustomersAwaitingApproval() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer WHERE status = 0 OR approved = 0");
	}

	public function getTotalAddressesByCustomerId($customer_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@address WHERE customer_id = " . (int)$customer_id);
	}

	public function getTotalAddressesByCountryId($country_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@address WHERE country_id = " . (int)$country_id);
	}

	public function getTotalAddressesByZoneId($zone_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@address WHERE zone_id = " . (int)$zone_id);
	}

	public function getTotalCustomersByCustomerGroupId($customer_group_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer WHERE customer_group_id = " . (int)$customer_group_id);
	}

	public function addHistory($customer_id, $comment) {
      	$this->db->query("INSERT INTO  @@customer_history SET customer_id = " . (int)$customer_id . ", comment = '" . ES(strip_tags($comment)) . "', date_added = NOW()");
	}

	public function getHistories($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 10;

		$query = $this->db->query("SELECT comment, date_added FROM  @@customer_history WHERE customer_id = $customer_id ORDER BY date_added DESC LIMIT $start, $limit");

		return $query->rows;
	}

	public function getTotalHistories($customer_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer_history WHERE customer_id = " . (int)$customer_id);
	}

	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO  @@customer_transaction SET customer_id = " . (int)$customer_id . ", order_id = " . (int)$order_id . ", description = '" . ES($description) . "', amount = " . (float)$amount . ", date_added = NOW()");

			$this->language->load('mail/customer');

			if ($customer_info['store_id']) {
				M('setting/store');

				$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = C('config_name');
				}
			} else {
				$store_name = C('config_name');
			}

			$message  = sprintf(L('text_transaction_received'), $this->currency->format($amount, C('config_currency'))) . "\n\n";
			$message .= sprintf(L('text_transaction_total'), $this->currency->format($this->getTransactionTotal($customer_id)));

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf(L('text_transaction_subject'), C('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function deleteTransaction($order_id) {
		$this->db->query("DELETE FROM  @@customer_transaction WHERE order_id = " . (int)$order_id);
	}

	public function getTransactions($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 10;

		$query = $this->db->query("SELECT * FROM  @@customer_transaction WHERE customer_id = $customer_id ORDER BY date_added DESC LIMIT $start, $limit");

		return $query->rows;
	}

	public function getTotalTransactions($customer_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total  FROM  @@customer_transaction WHERE customer_id = " . (int)$customer_id);
	}

	public function getTransactionTotal($customer_id) {
		return $this->db->queryOne("SELECT SUM(amount) AS total FROM  @@customer_transaction WHERE customer_id = " . (int)$customer_id);
	}

	public function getTotalTransactionsByOrderId($order_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer_transaction WHERE order_id = " . (int)$order_id);
	}

	public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO  @@customer_reward SET customer_id = " . (int)$customer_id . ", order_id = " . (int)$order_id . ", points = " . (int)$points . ", description = '" . ES($description) . "', date_added = NOW()");

			$this->language->load('mail/customer');

			if ($order_id) {
				M('sale/order');

				$order_info = $this->model_sale_order->getOrder($order_id);

				if ($order_info) {
					$store_name = $order_info['store_name'];
				} else {
					$store_name = C('config_name');
				}
			} else {
				$store_name = C('config_name');
			}

			$message  = sprintf(L('text_reward_received'), $points) . "\n\n";
			$message .= sprintf(L('text_reward_total'), $this->getRewardTotal($customer_id));

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf(L('text_reward_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM  @@customer_reward WHERE order_id = " . (int)$order_id);
	}

	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM  @@customer_reward WHERE customer_id = $customer_id ORDER BY date_added DESC LIMIT $start, $limit");

		return $query->rows;
	}

	public function getTotalRewards($customer_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer_reward WHERE customer_id = " . (int)$customer_id);
	}

	public function getRewardTotal($customer_id) {
		return $this->db->queryOne("SELECT SUM(points) AS total FROM  @@customer_reward WHERE customer_id = " . (int)$customer_id);
	}

	public function getTotalCustomerRewardsByOrderId($order_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer_reward WHERE order_id = " . (int)$order_id);
	}

	public function getIpsByCustomerId($customer_id) {
		$query = $this->db->query("SELECT * FROM  @@customer_ip WHERE customer_id = " . (int)$customer_id);

		return $query->rows;
	}

	public function getTotalCustomersByIp($ip) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer_ip WHERE ip = '" . ES($ip) . "'");
	}

	public function addBlacklist($ip) {
		$this->db->query("INSERT INTO `@@customer_ip_blacklist` SET `ip` = '" . ES($ip) . "'");
	}

	public function deleteBlacklist($ip) {
		$this->db->query("DELETE FROM `@@customer_ip_blacklist` WHERE `ip` = '" . ES($ip) . "'");
	}

	public function getTotalBlacklistsByIp($ip) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@customer_ip_blacklist` WHERE `ip` = '" . ES($ip) . "'");
	}
}
?>