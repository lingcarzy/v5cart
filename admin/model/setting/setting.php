<?php 
class ModelSettingSetting extends Model {
	public function getSetting($group, $store_id = 0) {
		$data = array(); 
		
		$query = $this->db->query("SELECT * FROM @@setting WHERE store_id = " . (int)$store_id . " AND `group` = '" . ES($group) . "'");
		
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	
	public function editSetting($group, $data, $store_id = 0) {
		$this->db->query("DELETE FROM @@setting WHERE store_id = " . (int)$store_id . " AND `group` = '" . ES($group) . "'");

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$this->db->query("INSERT INTO @@setting SET store_id = " . (int)$store_id . ", `group` = '" . ES($group) . "', `key` = '" . ES($key) . "', `value` = '" . ES($value) . "'");
			} else {
				$this->db->query("INSERT INTO @@setting SET store_id = " . (int)$store_id . ", `group` = '" . ES($group) . "', `key` = '" . ES($key) . "', `value` = '" . ES(serialize($value)) . "', serialized = 1");
			}
		}
		$this->load->helper('cache');
		cache_setting();
	}
	
	public function deleteSetting($group, $store_id = 0) {
		$this->db->query("DELETE FROM @@setting WHERE store_id = " . (int)$store_id . " AND `group` = '" . ES($group) . "'");
		
		$this->load->helper('cache');
		cache_setting();
	}
	
	public function editSettingValue($group = '', $key = '', $value = '', $store_id = 0) {
		if (is_array($value)) {
			$value = serialize($value);
		}
		
		$this->db->query("UDPATE @@setting SET `value` = '" . ES($value) . " WHERE `group` = '" . ES($group) . "' AND `key` = '" . ES($key) . "' AND store_id = " . (int)$store_id);
		
		$this->load->helper('cache');
		cache_setting();
	}
}
?>