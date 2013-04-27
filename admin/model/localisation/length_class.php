<?php
class ModelLocalisationLengthClass extends Model {
	public function addLengthClass($data) {
		$this->db->query("INSERT INTO  @@length_class SET value = " . (float)$data['value']);

		$length_class_id = $this->db->getLastId();

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO  @@length_class_description SET length_class_id = " . (int)$length_class_id . ", language_id = " . (int)$language_id . ", title = '" . ES($value['title']) . "', unit = '" . ES($value['unit']) . "'");
		}
	}

	public function editLengthClass($length_class_id, $data) {
		$this->db->query("UPDATE  @@length_class SET value = " . (float)$data['value'] . " WHERE length_class_id = " . (int)$length_class_id);

		$this->db->query("DELETE FROM  @@length_class_description WHERE length_class_id = " . (int)$length_class_id);

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO  @@length_class_description SET length_class_id = " . (int)$length_class_id . ", language_id = " . (int)$language_id . ", title = '" . ES($value['title']) . "', unit = '" . ES($value['unit']) . "'");
		}
	}

	public function deleteLengthClass($length_class_id) {
		$this->db->query("DELETE FROM  @@length_class WHERE length_class_id = " . (int)$length_class_id);
		$this->db->query("DELETE FROM  @@length_class_description WHERE length_class_id = " . (int)$length_class_id);
	}

	public function getLengthClasses($data = array()) {
		$sql = "SELECT * FROM  @@length_class lc LEFT JOIN  @@length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = " . (int)C('config_language_id');

		$sort_data = array(
			'title',
			'unit',
			'value'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY title";
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

	public function getLengthClass($length_class_id) {
		return $this->db->queryOne("SELECT * FROM  @@length_class lc LEFT JOIN  @@length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lc.length_class_id = " . (int)$length_class_id . " AND lcd.language_id = " . (int)C('config_language_id'));
	}

	public function getLengthClassDescriptionByUnit($unit) {
		$query = $this->db->query("SELECT * FROM  @@length_class_description WHERE unit = '" . ES($unit) . "' AND language_id = " . (int)C('config_language_id'));

		return $query->row;
	}

	public function getLengthClassDescriptions($length_class_id) {
		$length_class_data = array();

		$query = $this->db->query("SELECT * FROM  @@length_class_description WHERE length_class_id = " . (int)$length_class_id);

		foreach ($query->rows as $result) {
			$length_class_data[$result['language_id']] = array(
				'title' => $result['title'],
				'unit'  => $result['unit']
			);
		}

		return $length_class_data;
	}

	public function getTotalLengthClasses() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@length_class");
	}
}
?>