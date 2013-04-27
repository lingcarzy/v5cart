<?php
class ModelSaleCustomerGroup extends Model {
	public function addCustomerGroup($data) {
		$customer_group_id = $this->db->insert('customer_group', $data);
		
		foreach ($data['customer_group_description'] as $language_id => $value) {
			$value['language_id'] = $language_id;
			$value['customer_group_id'] = $customer_group_id;
			$this->db->insert('customer_group_description', $value);
		}	
	}
	
	public function editCustomerGroup($customer_group_id, $data) {
		$this->db->update('customer_group', $data, array('customer_group_id' => $customer_group_id));
	
		$this->db->query("DELETE FROM  @@customer_group_description WHERE customer_group_id = " . (int)$customer_group_id);

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$value['language_id'] = $language_id;
			$value['customer_group_id'] = $customer_group_id;
			$this->db->insert('customer_group_description', $value);
		}
	}
	
	public function deleteCustomerGroup($customer_group_id) {
		$this->db->query("DELETE FROM  @@customer_group WHERE customer_group_id = " . (int)$customer_group_id);
		$this->db->query("DELETE FROM  @@customer_group_description WHERE customer_group_id = " . (int)$customer_group_id);
		$this->db->query("DELETE FROM  @@product_discount WHERE customer_group_id = " . (int)$customer_group_id);
		$this->db->query("DELETE FROM  @@product_special WHERE customer_group_id = " . (int)$customer_group_id);
		$this->db->query("DELETE FROM  @@product_reward WHERE customer_group_id = " . (int)$customer_group_id);
	}
	
	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM  @@customer_group cg LEFT JOIN  @@customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = " . (int)$customer_group_id . " AND cgd.language_id = " . (int)C('config_language_id'));
		
		return $query->row;
	}
	
	public function getCustomerGroups($data = array()) {
		$sql = "SELECT * FROM  @@customer_group cg LEFT JOIN  @@customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = " . (int)C('config_language_id');
		
		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cgd.name";	
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
	
	public function getCustomerGroupDescriptions($customer_group_id) {
		$customer_group_data = array();
		
		$query = $this->db->query("SELECT * FROM  @@customer_group_description WHERE customer_group_id = " . (int)$customer_group_id);
				
		foreach ($query->rows as $result) {
			$customer_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $customer_group_data;
	}
		
	public function getTotalCustomerGroups() {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@customer_group");
	}
}
?>