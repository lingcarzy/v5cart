<?php
class ModelAccountCustomerGroup extends Model {
	public function getCustomerGroup($customer_group_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM @@customer_group cg LEFT JOIN @@customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = " . (int)C('config_language_id'));
	}

	public function getCustomerGroups() {
		$query = $this->db->query("SELECT * FROM @@customer_group cg LEFT JOIN @@customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = " . (int)C('config_language_id') . " ORDER BY cg.sort_order ASC, cgd.name ASC");

		return $query->rows;
	}
}
?>
