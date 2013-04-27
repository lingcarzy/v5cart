<?php
class ModelSaleCustomerBlacklist extends Model {
	public function addCustomerBlacklist($data) {
		$this->db->query("INSERT INTO `@@customer_ip_blacklist` SET `ip` = '" . ES($data['ip']) . "'");
	}

	public function editCustomerBlacklist($customer_ip_blacklist_id, $data) {
		$this->db->query("UPDATE `@@customer_ip_blacklist` SET `ip` = '" . ES($data['ip']) . "' WHERE customer_ip_blacklist_id = " . (int)$customer_ip_blacklist_id);
	}

	public function deleteCustomerBlacklist($customer_ip_blacklist_id) {
		$this->db->query("DELETE FROM `@@customer_ip_blacklist` WHERE customer_ip_blacklist_id = " . (int)$customer_ip_blacklist_id);
	}

	public function getCustomerBlacklist($customer_ip_blacklist_id) {
		$query = $this->db->query("SELECT * FROM `@@customer_ip_blacklist` WHERE customer_ip_blacklist_id = " . (int)$customer_ip_blacklist_id);

		return $query->row;
	}

	public function getCustomerBlacklists($data = array()) {
		$sql = "SELECT *, (SELECT COUNT(DISTINCT customer_id) FROM `@@customer_ip` ci WHERE ci.ip = cib.ip) AS total FROM `@@customer_ip_blacklist` cib";

		$sql .= " ORDER BY `ip`";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0)	$data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalCustomerBlacklists($data = array()) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `@@customer_ip_blacklist`");

		return $query->row['total'];
	}
}
?>