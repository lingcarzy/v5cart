<?php
class ModelLocalisationStockStatus extends Model {
	public function addStockStatus($data) {
		foreach ($data['stock_status'] as $language_id => $value) {
			if (isset($stock_status_id)) {
				$this->db->query("INSERT INTO  @@stock_status SET stock_status_id = " . (int)$stock_status_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO  @@stock_status SET language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");

				$stock_status_id = $this->db->getLastId();
			}
		}
	}

	public function editStockStatus($stock_status_id, $data) {
		$this->db->query("DELETE FROM  @@stock_status WHERE stock_status_id = " . (int)$stock_status_id);

		foreach ($data['stock_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO  @@stock_status SET stock_status_id = " . (int)$stock_status_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
		}
	}

	public function deleteStockStatus($stock_status_id) {
		$this->db->query("DELETE FROM  @@stock_status WHERE stock_status_id = " . (int)$stock_status_id);
	}

	public function getStockStatus($stock_status_id) {
		$query = $this->db->query("SELECT * FROM  @@stock_status WHERE stock_status_id = " . (int)$stock_status_id . " AND language_id = " . (int)C('config_language_id'));

		return $query->row;
	}

	public function getStockStatuses($data = array()) {
		$sql = "SELECT * FROM  @@stock_status WHERE language_id = " . (int)C('config_language_id');

		$sql .= " ORDER BY name";

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

	public function getStockStatusDescriptions($stock_status_id) {
		$stock_status_data = array();

		$query = $this->db->query("SELECT * FROM  @@stock_status WHERE stock_status_id = " . (int)$stock_status_id);

		foreach ($query->rows as $result) {
			$stock_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $stock_status_data;
	}

	public function getTotalStockStatuses() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@stock_status WHERE language_id = " . (int)C('config_language_id'));
	}
}
?>