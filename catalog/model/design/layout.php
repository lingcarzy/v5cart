<?php
class ModelDesignLayout extends Model {	
	public function getLayout($route) {
		$query = $this->db->query("SELECT * FROM @@layout_route WHERE '" . ES($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . (int)C('config_store_id') . "' ORDER BY route DESC LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;	
		}
	}
}
?>