<?php
class ModelLocalisationReturnReason extends Model {
	public function addReturnReason($data) {
		foreach ($data['return_reason'] as $language_id => $value) {
			if (isset($return_reason_id)) {
				$this->db->query("INSERT INTO  @@return_reason SET return_reason_id = " . (int)$return_reason_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO  @@return_reason SET language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");

				$return_reason_id = $this->db->getLastId();
			}
		}

	}

	public function editReturnReason($return_reason_id, $data) {
		$this->db->query("DELETE FROM  @@return_reason WHERE return_reason_id = " . (int)$return_reason_id);

		foreach ($data['return_reason'] as $language_id => $value) {
			$this->db->query("INSERT INTO  @@return_reason SET return_reason_id = " . (int)$return_reason_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
		}

	}

	public function deleteReturnReason($return_reason_id) {
		$this->db->query("DELETE FROM  @@return_reason WHERE return_reason_id = " . (int)$return_reason_id);
	}

	public function getReturnReason($return_reason_id) {
		return $this->db->queryOne("SELECT * FROM  @@return_reason WHERE return_reason_id = " . (int)$return_reason_id . " AND language_id = " . (int)C('config_language_id'));
	}

	public function getReturnReasons($data = array()) {
      $sql = "SELECT * FROM  @@return_reason WHERE language_id = " . (int)C('config_language_id');

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

	public function getReturnReasonDescriptions($return_reason_id) {
		$return_reason_data = array();

		$query = $this->db->query("SELECT * FROM  @@return_reason WHERE return_reason_id = " . (int)$return_reason_id);

		foreach ($query->rows as $result) {
			$return_reason_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $return_reason_data;
	}

	public function getTotalReturnReasons() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@return_reason WHERE language_id = " . (int)C('config_language_id'));
	}
}
?>