<?php
class ModelSettingStore extends Model {
	public function addStore($data) {
		$store_data = array(
			'name' => $data['config_name'],
			'url' => $data['config_url'],
			'ssl' => $data['config_ssl']
		);
		
		$this->db->insert('store', $data);		
		$this->cache->delete('store');

		return $this->db->getLastId();
	}

	public function editStore($store_id, $data) {
		$store_data = array(
			'name' => $data['config_name'],
			'url' => $data['config_url'],
			'ssl' => $data['config_ssl']
		);
		$this->db->update('store', $data, array('store_id' => $store_id));

		$this->cache->delete('store');
	}

	public function deleteStore($store_id) {
		$this->db->query("DELETE FROM @@store WHERE store_id = " . (int)$store_id);

		$this->cache->delete('store');
	}

	public function getStore($store_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM @@store WHERE store_id = " . (int)$store_id);
	}

	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM @@store ORDER BY url");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

	public function getTotalStores() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@store");
	}

	public function getTotalStoresByLayoutId($layout_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_layout_id' AND `value` = '" . $layout_id . "' AND store_id != 0");
	}

	public function getTotalStoresByLanguage($language) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_language' AND `value` = '" . ES($language) . "' AND store_id != 0");
	}

	public function getTotalStoresByCurrency($currency) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_currency' AND `value` = '" . ES($currency) . "' AND store_id != 0");
	}

	public function getTotalStoresByCountryId($country_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_country_id' AND `value` = '" . $country_id . "' AND store_id != 0");
	}

	public function getTotalStoresByZoneId($zone_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_zone_id' AND `value` = '" . $zone_id . "' AND store_id != 0");
	}

	public function getTotalStoresByCustomerGroupId($customer_group_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_customer_group_id' AND `value` = '" . $customer_group_id . "' AND store_id != 0");
	}

	public function getTotalStoresByInformationId($information_id) {
      	$account_query = $this->db->query("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND store_id != 0");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND store_id != 0");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId($order_status_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@setting WHERE `key` = 'config_order_status_id' AND `value` = '" . $order_status_id . "' AND store_id != 0");
	}
}
?>