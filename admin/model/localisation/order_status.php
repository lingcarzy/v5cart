<?php 
class ModelLocalisationOrderStatus extends Model {
	public function addOrderStatus($data) {
		foreach ($data['order_status'] as $language_id => $value) {
			if (isset($order_status_id)) {
				$this->db->query("INSERT INTO  @@order_status SET order_status_id = " . (int)$order_status_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO  @@order_status SET language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
				
				$order_status_id = $this->db->getLastId();
			}
		}
	}

	public function editOrderStatus($order_status_id, $data) {
		$this->db->query("DELETE FROM  @@order_status WHERE order_status_id = " . (int)$order_status_id);

		foreach ($data['order_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO  @@order_status SET order_status_id = " . (int)$order_status_id . ", language_id = " . (int)$language_id . ", name = '" . ES($value['name']) . "'");
		}
	}
	
	public function deleteOrderStatus($order_status_id) {
		$this->db->query("DELETE FROM  @@order_status WHERE order_status_id = " . (int)$order_status_id);
	}
		
	public function getOrderStatus($order_status_id) {
		return $this->db->queryOne("SELECT * FROM  @@order_status WHERE order_status_id = " . (int)$order_status_id . " AND language_id = " . (int)C('config_language_id'));
	}
		
	public function getOrderStatuses($data = array()) {
      	
		$sql = "SELECT * FROM  @@order_status WHERE language_id = '" . (int)C('config_language_id') . "'";
		
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
	
	public function getOrderStatusDescriptions($order_status_id) {
		$order_status_data = array();
		
		$query = $this->db->query("SELECT * FROM  @@order_status WHERE order_status_id = " . (int)$order_status_id);
		
		foreach ($query->rows as $result) {
			$order_status_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $order_status_data;
	}
	
	public function getTotalOrderStatuses() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@order_status WHERE language_id = " . (int)C('config_language_id'));
	}	
}
?>