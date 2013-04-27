<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {
		if ($data['image']) {
			$data['image'] = html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8');
		}
		$manufacturer_id = $this->db->insert('manufacturer', $data);

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO  @@manufacturer_to_store SET manufacturer_id = " . (int)$manufacturer_id . ", store_id = " . (int)$store_id);
			}
		}
		
		$this->cache->delete('manufacturer');
	}
	
	public function editManufacturer($manufacturer_id, $data) {
		if ($data['image']) {
			$data['image'] = html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8');
		}
		$this->db->update('manufacturer', $data, "manufacturer_id=$manufacturer_id");
		
		$this->db->query("DELETE FROM  @@manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id);

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO  @@manufacturer_to_store SET manufacturer_id = " . (int)$manufacturer_id . ", store_id = " . (int)$store_id);
			}
		}
		
		$this->cache->delete('manufacturer');
	}
	
	public function deleteManufacturer($manufacturer_id) {
		$this->db->query("DELETE FROM  @@manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id);
		$this->db->query("DELETE FROM  @@manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id);
			
		$this->cache->delete('manufacturer');
	}	
	
	public function getManufacturer($manufacturer_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM  @@manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id);
	}
	
	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM  @@manufacturer";
		
		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . ES($data['filter_name']) . "%'";
		}
		
		$sort_data = array(
			'name',
			'sort_order'
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
	
	public function getManufacturerStores($manufacturer_id) {
		return $this->db->queryArray("SELECT store_id FROM  @@manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id);
	}

	public function getTotalManufacturers() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@manufacturer");
	}	
}
?>