<?php
class ModelSettingExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM @@extension WHERE `type` = '" . ES($type) . "'");

		return $query->rows;
	}
}
?>