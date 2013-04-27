<?php
class ModelDesignLayout extends Model {
	public function addLayout($data) {
		$this->db->query("INSERT INTO  @@layout SET name = '" . ES($data['name']) . "'");
	
		$layout_id = $this->db->getLastId();
		
		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->query("INSERT INTO  @@layout_route SET layout_id = " . (int)$layout_id . ", store_id = " . (int)$layout_route['store_id'] . ", route = '" . ES($layout_route['route']) . "'");
			}
		}
	}
	
	public function editLayout($layout_id, $data) {
		$this->db->query("UPDATE  @@layout SET name = '" . ES($data['name']) . "' WHERE layout_id = " . (int)$layout_id);
		
		$this->db->query("DELETE FROM  @@layout_route WHERE layout_id = " . (int)$layout_id);
		
		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->query("INSERT INTO  @@layout_route SET layout_id = " . (int)$layout_id . ", store_id = " . (int)$layout_route['store_id'] . ", route = '" . ES($layout_route['route']) . "'");
			}
		}
	}
	
	public function deleteLayout($layout_id) {
		$this->db->query("DELETE FROM  @@layout WHERE layout_id = " . (int)$layout_id);
		$this->db->query("DELETE FROM  @@layout_route WHERE layout_id = " . (int)$layout_id);
		$this->db->query("DELETE FROM  @@category_to_layout WHERE layout_id = " . (int)$layout_id);
		$this->db->query("DELETE FROM  @@product_to_layout WHERE layout_id = " . (int)$layout_id);
		$this->db->query("DELETE FROM  @@page_to_layout WHERE layout_id = " . (int)$layout_id);		
	}
	
	public function getLayout($layout_id) {
		return $this->db->queryOne("SELECT * FROM  @@layout WHERE layout_id = " . (int)$layout_id);
	}
	
	public function getLayouts($data = array()) {
		$sql = "SELECT * FROM  @@layout ORDER BY name";
		
		if (isset($data['order'])) {
			$sql .= " $data[order]";
		}
		else $sql .= " ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getLayoutRoutes($layout_id) {
		$query = $this->db->query("SELECT * FROM  @@layout_route WHERE layout_id = " . (int)$layout_id);
		return $query->rows;
	}
		
	public function getTotalLayouts() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@layout");
	}	
}
?>