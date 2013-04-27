<?php
class ModelLocalisationTaxClass extends Model {
	public function addTaxClass($data) {
	
		$data['date_added'] = 'NOW()';
		$tax_class_id = $this->db->insert('tax_class', $data);

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->db->query("INSERT INTO  @@tax_rule SET tax_class_id = " . (int)$tax_class_id . ", tax_rate_id = " . (int)$tax_rule['tax_rate_id'] . ", based = '" . ES($tax_rule['based']) . "', priority = " . (int)$tax_rule['priority']);
			}
		}
	}

	public function editTaxClass($tax_class_id, $data) {
		$data['date_modified'] = 'NOW()';
		$this->db->update('tax_class', $data, array('tax_class_id' => $tax_class_id));

		$this->db->delete('tax_rule', array('tax_class_id' => $tax_class_id));

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->db->query("INSERT INTO  @@tax_rule SET tax_class_id = " . (int)$tax_class_id . ", tax_rate_id = " . (int)$tax_rule['tax_rate_id'] . ", based = '" . ES($tax_rule['based']) . "', priority = " . (int)$tax_rule['priority']);
			}
		}
	}

	public function deleteTaxClass($tax_class_id) {
		$this->db->query("DELETE FROM  @@tax_class WHERE tax_class_id = " . (int)$tax_class_id);
		$this->db->query("DELETE FROM  @@tax_rule WHERE tax_class_id = " . (int)$tax_class_id);
	}

	public function getTaxClass($tax_class_id) {
		return $this->db->queryOne("SELECT * FROM  @@tax_class WHERE tax_class_id = " . (int)$tax_class_id);
	}

	public function getTaxClasses($data = array()) {
    	$sql = "SELECT * FROM  @@tax_class";
		$sql .= " ORDER BY title";

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

	public function getTotalTaxClasses() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@tax_class");
	}

	public function getTaxRules($tax_class_id) {
      	$query = $this->db->query("SELECT * FROM  @@tax_rule WHERE tax_class_id = " . (int)$tax_class_id);

		return $query->rows;
	}

	public function getTotalTaxRulesByTaxRateId($tax_rate_id) {
      	return $this->db->queryOne("SELECT COUNT(DISTINCT tax_class_id) AS total FROM  @@tax_rule WHERE tax_rate_id = " . (int)$tax_rate_id);
	}
}
?>