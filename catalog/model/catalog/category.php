<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM @@category c LEFT JOIN @@category_description cd ON (c.category_id = cd.category_id) LEFT JOIN @@category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)C('config_language_id') . "' AND c2s.store_id = '" . (int)C('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM @@category c LEFT JOIN @@category_description cd ON (c.category_id = cd.category_id) LEFT JOIN @@category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)C('config_language_id') . "' AND c2s.store_id = '" . (int)C('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}
	
	public function getCategoryAttributes($category_id, $attribute_ids) {
		
		$attribute_data = $this->cache->get('attribute.' . (int)C('config_language_id') . '.' . $category_id);
		if (!$attribute_data) {
			$query = $this->db->query("SELECT * FROM @@attribute_description WHERE language_id = " . (int)C('config_language_id') . " AND attribute_id IN ($attribute_ids)");
			$attribute_data = array();
			foreach ($query->rows as $attribute_description) {
				$attribute_data[$attribute_description['attribute_id']] = array(
					'name'     => $attribute_description['name'],
					'values'   => explode('|', $attribute_description['value'])
				);
			}
			$this->cache->set('attribute.' . (int)C('config_language_id') . '.' . $category_id, $attribute_data);
		}
		return $attribute_data;
	}
}
?>