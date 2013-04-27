<?php
class ModelLocalisationZone extends Model {
	public function addZone($data) {
		$this->db->query("INSERT INTO  @@zone SET status = " . (int)$data['status'] . ", name = '" . ES($data['name']) . "', code = '" . ES($data['code']) . "', country_id = " . (int)$data['country_id']);

		$this->cache($data['country_id']);
	}

	public function editZone($zone_id, $data) {
		$this->db->query("UPDATE  @@zone SET status = " . (int)$data['status'] . ", name = '" . ES($data['name']) . "', code = '" . ES($data['code']) . "', country_id = " . (int)$data['country_id'] . " WHERE zone_id = " . (int)$zone_id);

		$this->cache($data['country_id']);
	}

	public function deleteZone($zone_id) {
		$country_id = $this->db->queryOne("SELECT country_id FROM  @@zone WHERE zone_id = " . (int)$zone_id);
		
		$this->db->query("DELETE FROM  @@zone WHERE zone_id = " . (int)$zone_id);
		
		$this->cache($country_id);
	}

	public function getZone($zone_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM  @@zone WHERE zone_id = " . (int)$zone_id);

		return $query->row;
	}

	public function getZones($data = array()) {
		$sql = "SELECT *, z.name, c.name AS country FROM  @@zone z LEFT JOIN  @@country c ON (z.country_id = c.country_id)";

		$sort_data = array(
			'c.name',
			'z.name',
			'z.code'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.name";
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

	public function getZonesByCountryId($country_id) {
		$query = $this->db->query("SELECT * FROM  @@zone WHERE country_id = " . (int)$country_id . " ORDER BY name");

		return $query->rows;
	}

	public function getTotalZones() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@zone");
	}

	public function getTotalZonesByCountryId($country_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@zone WHERE country_id = " . (int)$country_id);
	}

	public function cache($country_id = 0) {
		if ($country_id) {
			$country_ids = array($country_id);
		} else {
			$country_ids = $this->db->queryArray("SELECT country_id FROM @@country WHERE status=1");
		}
		
		foreach ($country_ids as $country_id) {
			$data = $this->db->query("SELECT zone_id, name FROM  @@zone WHERE country_id = '" . (int)$country_id . "' AND status = 1 ORDER BY name");
			cache_write("$country_id.php", $data->rows, 'zone');
		}
	}
}
?>