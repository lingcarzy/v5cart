<?php
class ModelCatalogProductGroup extends Model {
	
	public function addGroup($data) {
		$data['date_added'] = $data['date_modified'] = time();
		return $this->db->insert('product_group', $data);
	}
	
	public function editGroup($product_group_id, $data) {
		$data['date_modified'] = time();
		$this->db->update('product_group', $data, array('product_group_id' => $product_group_id));
	}
	
	public function getGroup($product_group_id) {
		return $this->db->get('product_group', array('product_group_id' => $product_group_id));
	}
	
	public function deleteGroup($product_group_id) {
		$this->db->delete('product_group_products', array('product_group_id' => $product_group_id));
		$this->db->delete('product_group', array('product_group_id' => $product_group_id));
	}
	
	public function getGroups($offset = 0, $limit = 10, &$total) {
		$total = $this->db->queryOne("SELECT count(*) FROM `@@product_group`");
		$query = $this->db->query( "SELECT * FROM `@@product_group` ORDER BY product_group_id DESC LIMIT $offset, $limit");
		return $query->rows;
	}
	
	public function getGroupProducts($product_group_id) {
		$sql = 'SELECT p.product_id, p.model, p.price, p.image, p.link, pd.name FROM @@product p, @@product_description pd, @@product_group_products pg WHERE p.product_id = pd.product_id AND p.product_id = pg.product_id AND pd.language_id = ' . (int)C('config_language_id') . " AND pg.product_group_id = $product_group_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function setGroupProducts($product_group_id, $product_ids) {
		$exists = $this->db->queryArray("SELECT product_id FROM @@product_group_products WHERE product_group_id = $product_group_id AND product_id IN ($product_ids)", 'product_id', 'product_id');
		$product_ids = explode(',', $product_ids);
		$data = array();
		foreach($product_ids as $product_id) {
			if (!in_array($product_id, $exists)) {
				$data[] = array(
					'product_group_id' => $product_group_id,
					'product_id' => $product_id
				);
			}
		}
		if ($data) {
			$this->db->insert("product_group_products", $data);
		}
		return count($data);
	}
}
?>