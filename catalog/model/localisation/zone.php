<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM @@zone WHERE zone_id = " . (int)$zone_id . " AND status = 1");

		return $query->row;
	}
}
?>