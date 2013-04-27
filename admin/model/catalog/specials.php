<?php
class ModelCatalogSpecials extends Model {
	
	public function editSpecial($product_special_id, $data) {
		$this->db->update('product_special', $data, array('product_special_id' => $product_special_id));
	}
	
	public function deleteSpecials($product_special_ids) {
		$this->db->query("DELETE FROM @@product_special WHERE product_special_id IN ($product_special_ids)");
	}	

	public function getSpecial($product_special_id) {
		$langId = (int)C('config_language_id');
		$sql = "SELECT ps.*, pd.name FROM @@product_special ps LEFT JOIN @@product_description pd ON (pd.product_id = ps.product_id) WHERE pd.language_id = $langId AND ps.product_special_id=$product_special_id";
		return $this->db->queryOne($sql);
	}
		
	public function getSpecials($data = array()) {
		$langId = (int)C('config_language_id');
		$sql = "SELECT ps.*, pd.name, p.model, p.price as list_price, p.image, cg.name as customer_group FROM @@product_special ps LEFT JOIN @@product p ON (ps.product_id = p.product_id) LEFT JOIN @@product_description pd ON (pd.product_id = p.product_id) LEFT JOIN @@customer_group_description cg ON (cg.customer_group_id = ps.customer_group_id) WHERE pd.language_id = $langId";
	
		$sort_data = array(
			'ps.customer_group_id',
			'ps.date_start',
			'ps.date_end'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ps.date_end";	
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
		
	public function getTotalSpecials() {
      	return $this->db->queryOne('SELECT COUNT(*) AS total FROM @@product_special');
	}
}
?>