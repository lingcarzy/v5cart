<?php
class ModelLocalisationTaxRate extends Model {
	public function addTaxRate($data) {
		
		$data['date_added'] = 'NOW()';
		$data['date_modified'] = 'NOW()';
		
		$tax_rate_id = $this->db->insert('tax_rate', $data);

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->db->query("INSERT INTO  @@tax_rate_to_customer_group SET tax_rate_id = " . $tax_rate_id . ", customer_group_id = " . (int)$customer_group_id);
			}
		}
	}

	public function editTaxRate($tax_rate_id, $data) {
		$data['date_modified'] = 'NOW()';
		$this->db->update('tax_rate', $data, array('tax_rate_id' => $tax_rate_id));

		$this->db->query("DELETE FROM  @@tax_rate_to_customer_group WHERE tax_rate_id = " . (int)$tax_rate_id);

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->db->query("INSERT INTO  @@tax_rate_to_customer_group SET tax_rate_id = " . (int)$tax_rate_id . ", customer_group_id = " . (int)$customer_group_id);
			}
		}
	}

	public function deleteTaxRate($tax_rate_id) {
		$this->db->query("DELETE FROM  @@tax_rate WHERE tax_rate_id = " . (int)$tax_rate_id);
		$this->db->query("DELETE FROM  @@tax_rate_to_customer_group WHERE tax_rate_id = " . (int)$tax_rate_id);
	}

	public function getTaxRate($tax_rate_id) {
		return $this->db->queryOne("SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM  @@tax_rate tr LEFT JOIN  @@geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE tr.tax_rate_id = " . (int)$tax_rate_id);
	}

	public function getTaxRates($data = array()) {
		$sql = "SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM  @@tax_rate tr LEFT JOIN  @@geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id)";

		$sort_data = array(
			'tr.name',
			'tr.rate',
			'tr.type',
			'gz.name',
			'tr.date_added',
			'tr.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY tr.name";
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

	public function getTaxRateCustomerGroups($tax_rate_id) {

		return $this->db->queryArray("SELECT customer_group_id FROM  @@tax_rate_to_customer_group WHERE tax_rate_id = " . (int)$tax_rate_id);
	}

	public function getTotalTaxRates() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@tax_rate");
	}

	public function getTotalTaxRatesByGeoZoneId($geo_zone_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@tax_rate WHERE geo_zone_id = " . (int)$geo_zone_id);
	}
}
?>