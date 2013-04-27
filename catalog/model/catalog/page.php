<?php
class ModelCatalogPage extends Model {
	public function getPage($page_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM @@page i LEFT JOIN @@page_content id ON (i.page_id = id.page_id) LEFT JOIN @@page_to_store i2s ON (i.page_id = i2s.page_id) WHERE i.page_id = " . (int)$page_id . " AND id.language_id = " . (int)C('config_language_id') . " AND i2s.store_id = " . (int)C('config_store_id') . " AND i.status = 1");
	
		return $query->row;
	}
	
	public function getPages() {
		$cache = md5('getpages');		
		$infos = $this->cache->get('page.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . $cache);
		if ($infos) return $infos;
		
		$query = $this->db->query("SELECT i.page_id,i.bottom,i.link,id.title FROM @@page i LEFT JOIN @@page_content id ON (i.page_id = id.page_id) LEFT JOIN @@page_to_store i2s ON (i.page_id = i2s.page_id) WHERE id.language_id = " . (int)C('config_language_id') . " AND i2s.store_id = " . (int)C('config_store_id') . " AND i.status = 1 ORDER BY i.sort_order, id.title ASC");
		$this->cache->set('page.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . $cache, $query->rows);
		return $query->rows;
	}
	
	public function getPageLayoutId($page_id) {
		$query = $this->db->query("SELECT * FROM @@page_to_layout WHERE page_id = " . (int)$page_id . " AND store_id = " . (int)C('config_store_id'));
		 
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return C('config_layout_page');
		}
	}	
}
?>