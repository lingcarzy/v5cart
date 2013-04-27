<?php
class ModelLocalisationReturnAction extends Model {
	public function addReturnAction($data) {
		foreach ($data['return_action'] as $language_id => $value) {
			if (isset($return_action_id)) {
				$this->db->query("INSERT INTO  @@return_action SET return_action_id = " . (int)$return_action_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO  @@return_action SET language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");

				$return_action_id = $this->db->getLastId();
			}
		}
	}

	public function editReturnAction($return_action_id, $data) {
		$this->db->query("DELETE FROM  @@return_action WHERE return_action_id = " . (int)$return_action_id);

		foreach ($data['return_action'] as $language_id => $value) {
			$this->db->query("INSERT INTO  @@return_action SET return_action_id = " . (int)$return_action_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
		}

	}

	public function deleteReturnAction($return_action_id) {
		$this->db->query("DELETE FROM  @@return_action WHERE return_action_id = " . (int)$return_action_id);
	}

	public function getReturnAction($return_action_id) {
		return $this->db->queryOne("SELECT * FROM  @@return_action WHERE return_action_id = " . (int)$return_action_id . " AND language_id = " . (int)C('config_language_id'));
	}

	public function getReturnActions($data = array()) {
		$sql = "SELECT * FROM  @@return_action WHERE language_id = " . (int)C('config_language_id');

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

	public function getReturnActionDescriptions($return_action_id) {
		$return_action_data = array();

		$query = $this->db->query("SELECT * FROM  @@return_action WHERE return_action_id = " . (int)$return_action_id);

		foreach ($query->rows as $result) {
			$return_action_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $return_action_data;
	}

	public function getTotalReturnActions() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@return_action WHERE language_id = " . (int)C('config_language_id'));
	}
}
?>