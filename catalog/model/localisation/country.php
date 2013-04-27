<?php
class ModelLocalisationCountry extends Model {
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM @@country WHERE country_id = " . (int)$country_id . " AND status = 1");
		
		return $query->row;
	}
}
?>