<?php
class ModelCatalogPage extends Model {
	public function addPage($data) {

		$page_id = $this->db->insert('page', $data);

		foreach ($data['page_content'] as $language_id => $value) {
			$value['page_id'] = $page_id;
			$value['language_id'] = $language_id;
			$this->db->insert('page_content', $value);
		}

		if (isset($data['page_store'])) {
			foreach ($data['page_store'] as $store_id) {
				$this->db->query("INSERT INTO  @@page_to_store SET page_id = $page_id, store_id = " . (int)$store_id);
			}
		}

		if (isset($data['page_layout'])) {
			foreach ($data['page_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO  @@page_to_layout SET page_id = $page_id, store_id = " . (int)$store_id . ", layout_id = " . (int)$layout['layout_id']);
				}
			}
		}
		$this->_updateLink($page_id, $data['seo_url']);

		return $page_id;
	}

	public function editPage($page_id, $data) {
		$this->db->update('page', $data, "page_id=$page_id");
		$this->db->query("DELETE FROM  @@page_content WHERE page_id = $page_id");

		foreach ($data['page_content'] as $language_id => $value) {
			$value['page_id'] = $page_id;
			$value['language_id'] = $language_id;
			$this->db->insert('page_content', $value);
		}

		$this->db->query("DELETE FROM  @@page_to_store WHERE page_id = $page_id");

		if (isset($data['page_store'])) {
			foreach ($data['page_store'] as $store_id) {
				$this->db->query("INSERT INTO  @@page_to_store SET page_id = $page_id, store_id = " . (int)$store_id);
			}
		}

		$this->db->query("DELETE FROM  @@page_to_layout WHERE page_id = $page_id");

		if (isset($data['page_layout'])) {
			foreach ($data['page_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO  @@page_to_layout SET page_id = $page_id, store_id = " . (int)$store_id . ", layout_id = " . (int)$layout['layout_id']);
				}
			}
		}

		$this->_updateLink($page_id, $data['seo_url']);
	}

	public function deletePage($page_id) {
		$page_id = (int) $page_id;
		$this->db->query("DELETE FROM  @@page WHERE page_id = $page_id");
		$this->db->query("DELETE FROM  @@page_content WHERE page_id = $page_id");
		$this->db->query("DELETE FROM  @@page_to_store WHERE page_id = $page_id");
		$this->db->query("DELETE FROM  @@page_to_layout WHERE page_id = $page_id");
	}

	public function getPage($page_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM  @@page WHERE page_id = " . (int)$page_id);
	}

	public function getPages($data = array()) {
		if ($data) {
			$sql = 'SELECT i.*, id.title FROM @@page i LEFT JOIN  @@page_content id ON (i.page_id = id.page_id) WHERE id.language_id = ' . (int)C('config_language_id');

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
		} else {
			$query = $this->db->query("SELECT i.*, id.title FROM  @@page i LEFT JOIN  @@page_content id ON (i.page_id = id.page_id) WHERE id.language_id = " . (int)C('config_language_id') . " ORDER BY id.title");

			$page_data = $query->rows;

			return $page_data;
		}
	}

	public function getPageContents($page_id) {
		return $this->db->queryArray("SELECT language_id,title,content FROM  @@page_content WHERE page_id = " . (int)$page_id, 'language_id');
	}

	public function getPageStores($page_id) {
		return $this->db->queryArray('SELECT store_id FROM @@page_to_store WHERE page_id = ' . (int)$page_id);
	}

	public function getPageLayouts($page_id) {
		return $this->db->queryArray("SELECT * FROM  @@page_to_layout WHERE page_id = " . (int)$page_id, 'store_id', 'layout_id');
	}

	public function getTotalPages() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@page");
	}

	public function getTotalPagesByLayoutId($layout_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@page_to_layout WHERE layout_id = " . (int)$layout_id);
	}

	//page url
	private function _updateLink($page_id, $seo_url = '') {
		$link = 'page/' . ($seo_url ? $seo_url : $page_id) . '.html';
		$this->db->query("UPDATE @@page SET link='$link' WHERE page_id=$page_id");
	}
}
?>