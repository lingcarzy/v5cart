<?php
class ModelCatalogSupplier extends Model {
	public function addSupplier($data) {
		$this->db->insert("supplier", $data);
	}
	
	public function editSupplier($supplier_id, $data) {
		$this->db->update("supplier", $data, "supplier_id=$supplier_id");
	}
	
	public function deleteSupplier($supplier_id) {
		$this->db->delete('supplier', "supplier_id = $supplier_id");
	}	
	
	public function getSupplier($supplier_id) {
		return $this->db->get('supplier', "supplier_id = $supplier_id");
	}
	
	public function getSupplierOptions() {
		return $this->db->queryArray("SELECT `supplier_id`, `name` FROM @@supplier", 'supplier_id', 'name');
	}
	
	public function getSuppliers($offset = 0, $limit = 10, &$total, $filters = null) {
		$cond = '';
		if ($filters) {
			if (!empty($filters['keyword'])) {
				$filters['keyword'] = ES($filters['keyword']);
				$cond .= " WHERE CONCAT(name,contact,address,email) LIKE '%{$filters['keyword']}%'";
			}			
			$cond .= " ORDER BY name";			
			if (isset($filters['order']) && ($filters['order'] == 'DESC')) {
				$cond .= " DESC";
			} else {
				$cond .= " ASC";
			}
		}
		$total = $this->db->queryOne("SELECT count(*) FROM @@supplier $cond");
		$result = $this->db->query("SELECT * FROM @@supplier $cond LIMIT $offset, $limit");
		return $result->rows;
	}
	
	function getTotalProductBySupplierId($id) {
		return $this->db->queryOne("SELECT count(*) FROM @@product WHERE supplier_id=$id");
	}
}
?>