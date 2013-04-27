<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {
		$this->db->insert('country', $data);
	}

	public function editCountry($country_id, $data) {
		$this->db->update('country', $data, array('country_id' => $country_id));
	}

	public function deleteCountry($country_id) {
		$this->db->delete('country', array('country_id' => $country_id));
	}

	public function getCountry($country_id) {
		return $this->db->get('country', array('country_id' => $country_id));
	}

	public function getCountries($data = array()) {
		$sql = "SELECT * FROM  @@country";

		$sort_data = array(
			'name',
			'iso_code_2',
			'iso_code_3'
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

	public function getTotalCountries() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@country");
	}

	public function cache() {
		$country_data = $this->db->queryArray("SELECT country_id, name FROM  @@country WHERE status = 1 ORDER BY name ASC", 'country_id', 'name');
		cache_write('country.php', $country_data);
	}
}
?>