<?php
class ModelSettingExtension extends Model {
	public function getInstalled($type) {
		return $this->db->queryArray("SELECT code FROM  @@extension WHERE `type` = '" . ES($type) . "'");
	}

	public function install($type, $code) {
		$this->db->query("INSERT INTO @@extension SET `type` = '" . ES($type) . "', `code` = '" . ES($code) . "'");
		
		$this->load->helper('cache');
		cache_system();
	}

	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM @@extension WHERE `type` = '" . ES($type) . "' AND `code` = '" . ES($code) . "'");

		$this->load->helper('cache');
		cache_system();
	}
}
?>