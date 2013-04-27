<?php
class ModelSaleReturn extends Model {
	public function addReturn($data) {
		$data['date_added'] = 'NOW()';
		$data['date_modified'] = 'NOW()';
		return $this->db->insert('return', $data);
	}

	public function editReturn($return_id, $data) {
		$data['date_modified'] = 'NOW()';
		$this->db->update('return', $data, array('return_id' => $return_id));
	}

	public function editReturnAction($return_id, $return_action_id) {
		$this->db->query("UPDATE `@@return` SET return_action_id = " . (int)$return_action_id . " WHERE return_id = " . (int)$return_id);
	}

	public function deleteReturn($return_id) {
		$this->db->query("DELETE FROM `@@return` WHERE return_id = " . (int)$return_id);
		$this->db->query("DELETE FROM  @@return_history WHERE return_id = " . (int)$return_id);
	}

	public function getReturn($return_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM  @@customer c WHERE c.customer_id = r.customer_id) AS customer FROM `@@return` r WHERE r.return_id = " . (int)$return_id);

		return $query->row;
	}

	public function getReturns($data = array()) {
		$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT rs.name FROM  @@return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = " . (int)C('config_language_id') . ") AS status FROM `@@return` r";

		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = " . (int)$data['filter_return_id'];
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . ES($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . ES($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . ES($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = " . (int)$data['filter_return_status_id'];
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . ES($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . ES($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.model',
			'status',
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.return_id";
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

	public function getTotalReturns($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `@@return`r";

		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = " . (int)$data['filter_return_id'];
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . ES($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = " . ES($data['filter_order_id']);
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . ES($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . ES($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = " . (int)$data['filter_return_status_id'];
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . ES($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . ES($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		return $this->db->queryOne($sql);
	}

	public function getTotalReturnsByReturnStatusId($return_status_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@return` WHERE return_status_id = " . (int)$return_status_id);
	}

	public function getTotalReturnsByReturnReasonId($return_reason_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@return` WHERE return_reason_id = " . (int)$return_reason_id);
	}

	public function getTotalReturnsByReturnActionId($return_action_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@return` WHERE return_action_id = " . (int)$return_action_id);
	}

	public function addReturnHistory($return_id, $data) {
		$this->db->query("UPDATE `@@return` SET return_status_id = " . (int)$data['return_status_id'] . ", date_modified = NOW() WHERE return_id = " . (int)$return_id);
		
		$data['return_id'] = $return_id;
		$data['notify'] = isset($data['notify']) ? $data['notify'] : 0;
		$data['date_added'] = 'NOW()';
		$this->db->insert('return_history', $data);
		
      	if ($data['notify']) {
        	$return_query = $this->db->query("SELECT *, rs.name AS status FROM `@@return` r LEFT JOIN  @@return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.return_id = " . (int)$return_id . " AND rs.language_id = " . (int)C('config_language_id'));

			if ($return_query->num_rows) {
				$this->language->load('mail/return');

				$subject = sprintf(L('text_subject'), C('config_name'), $return_id);

				$message  = L('text_return_id') . ' ' . $return_id . "\n";
				$message .= L('text_date_added') . ' ' . date(L('date_format_short'), strtotime($return_query->row['date_added'])) . "\n\n";
				$message .= L('text_return_status') . "\n";
				$message .= $return_query->row['status'] . "\n\n";

				if ($data['comment']) {
					$message .= L('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}

				$message .= L('text_footer');

				$mail = new Mail();
				$mail->protocol = C('config_mail_protocol');
				$mail->parameter = C('config_mail_parameter');
				$mail->hostname = C('config_smtp_host');
				$mail->username = C('config_smtp_username');
				$mail->password = C('config_smtp_password');
				$mail->port = C('config_smtp_port');
				$mail->timeout = C('config_smtp_timeout');
				$mail->setTo($return_query->row['email']);
				$mail->setFrom(C('config_email'));
	    		$mail->setSender(C('config_name'));
	    		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
	    		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
	    		$mail->send();
			}
		}
	}

	public function getReturnHistories($return_id, $start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 10;

		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM  @@return_history rh LEFT JOIN  @@return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = " . (int)$return_id . " AND rs.language_id = " . (int)C('config_language_id') . " ORDER BY rh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReturnHistories($return_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@return_history WHERE return_id = " . (int)$return_id);
	}

	public function getTotalReturnHistoriesByReturnStatusId($return_status_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@return_history WHERE return_status_id = " . (int)$return_status_id . " GROUP BY return_id");
	}
}
?>