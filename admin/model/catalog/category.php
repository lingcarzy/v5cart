<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {

		if (!empty($data['image'])) {
			$data['image'] = html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8');
		}
		$data['date_modified'] = 'NOW()';
		$data['date_added'] = 'NOW()';

		$category_id = $this->db->insert('category', $data);

		foreach ($data['category_description'] as $language_id => $value) {
			$value['language_id'] = $language_id;
			$value['category_id'] = $category_id;
			$this->db->insert('category_description', $value);
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO  @@category_to_store SET category_id = " . (int)$category_id . ", store_id = " . (int)$store_id);
			}
		}

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO  @@category_to_layout SET category_id = " . (int)$category_id . ", store_id = " . (int)$store_id . ", layout_id = " . (int)$layout['layout_id']);
				}
			}
		}
	}

	public function editCategory($category_id, $data) {
		if (!empty($data['image'])) {
			$data['image'] = html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8');
		}
		$data['date_modified'] = 'NOW()';
		$this->db->update('category', $data, "category_id=$category_id");

		$this->db->query("DELETE FROM  @@category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$value['language_id'] = $language_id;
			$value['category_id'] = $category_id;
			$this->db->insert('category_description', $value);
		}

		$this->db->query("DELETE FROM  @@category_to_store WHERE category_id = " . (int)$category_id);

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO  @@category_to_store SET category_id = " . (int)$category_id . ", store_id = " . (int)$store_id);
			}
		}

		$this->db->query("DELETE FROM  @@category_to_layout WHERE category_id = " . (int)$category_id);

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO  @@category_to_layout SET category_id = " . (int)$category_id . ", store_id = " . (int)$store_id . ", layout_id = " . (int)$layout['layout_id']);
				}
			}
		}
	}

	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM  @@category WHERE category_id = " . (int)$category_id);
		$this->db->query("DELETE FROM  @@category_description WHERE category_id = " . (int)$category_id);
		$this->db->query("DELETE FROM  @@category_to_store WHERE category_id = " . (int)$category_id);
		$this->db->query("DELETE FROM  @@category_to_layout WHERE category_id = " . (int)$category_id);
		$this->db->query("DELETE FROM  @@product_to_category WHERE category_id = " . (int)$category_id);

		$query = $this->db->query("SELECT category_id FROM  @@category WHERE parent_id = " . (int)$category_id);

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}
	}

	public function getCategory($category_id) {
		return $this->db->queryOne("SELECT * FROM  @@category WHERE category_id = " . (int)$category_id);
	}

	public function getCategories($parent_id = 0, $sub = true) {
			$category_data = array();

			$query = $this->db->query("SELECT * FROM  @@category c LEFT JOIN  @@category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = " . (int)$parent_id . " AND cd.language_id = '" . (int)C('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($result['category_id']),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
				if ($sub) {
					$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
				}
			}

		return $category_data;
	}

	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM  @@category c LEFT JOIN  @@category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = " . (int)$category_id . " AND cd.language_id = " . (int)C('config_language_id') . " ORDER BY c.sort_order, cd.name ASC");

		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id']) . L('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}

	public function getCategoryTree() {
		$query = $this->db->query("SELECT c.category_id, c.parent_id, cd.name FROM  @@category c LEFT JOIN  @@category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)C('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		$this->load->helper('tree');
		$tree = new Tree($query->rows, 'category_id');
		return $tree->get_plane();
	}

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM  @@category_description WHERE category_id = " . (int)$category_id);

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'seo_title'        => $result['seo_title'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}

		return $category_description_data;
	}
	
	public function getCategoryStores($category_id) {
		return $this->db->queryArray("SELECT store_id FROM  @@category_to_store WHERE category_id = " . (int)$category_id);
	}

	public function getCategoryLayouts($category_id) {
		return $this->db->queryArray("SELECT store_id,layout_id FROM  @@category_to_layout WHERE category_id = " . (int)$category_id, 'store_id', 'layout_id');
	}

	public function getTotalCategories() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@category");
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@category_to_layout WHERE layout_id = " . (int)$layout_id);
	}

	public function cache() {
		$this->load->helper('tree');
		$store_ids = $this->db->queryArray("SELECT store_id FROM @@store");
		$language_ids = $this->db->queryArray("SELECT language_id FROM  @@language WHERE status=1");
		array_push($store_ids, 0);

		foreach ($store_ids as $store_id) {
			$layouts = $this->db->queryArray("SELECT category_id, layout_id FROM  @@category_to_layout WHERE store_id=$store_id", 'category_id', 'layout_id');
			foreach ($language_ids as $language_id) {
				$query = $this->db->query("SELECT c.category_id, c.parent_id, c.seo_url, c.top, c.column, cd.name FROM  @@category c LEFT JOIN  @@category_description cd ON (c.category_id = cd.category_id) LEFT JOIN @@category_to_store cs ON (c.category_id=cs.category_id) WHERE cd.language_id = $language_id AND cs.store_id=$store_id AND c.status=1 ORDER BY c.sort_order, cd.name ASC");
				$tree = new Tree($query->rows, 'category_id');
				$categories = $tree->get_plane();
				$cats = array();
				$root = array();
				foreach ($categories as $category) {
					$category_id = (int)$category['category_id'];
					$parent_id = (int) $category['parent_id'];
					if ($parent_id == 0) {
						$root[] = $category_id;
						$cats[$category_id] = array(
							'pid' => 0,
							'id' => $category_id,
							'name' => $category['name'],
							'url' => $category['seo_url'],
							'path' => "$category_id",
							'child' => "$category_id",
							'top' =>  (int)$category['top'],
							'column'=> (int)$category['column'],
							'link' => str_replace(HTTP_CATALOG, '', U('product/category', "cate_id=$category_id"))
						);
						if (isset($layouts[$category_id])) {
							$cats[$category_id]['layout'] = $layouts[$category_id];
						}
						if (!$category['_leaf']) {
							$cats[$category_id]['sub'] = '';
						}
					}
					elseif (isset($cats[$parent_id])) {
						$path = $cats[$parent_id]['path'] . "," . $category_id;
						$cats[$parent_id]['child'] .= ",$category_id";
						if ($cats[$parent_id]['sub'])
							$cats[$parent_id]['sub'] .= ",$category_id";
						else
							$cats[$parent_id]['sub'] .= "$category_id";
						$ancestor_id = $cats[$parent_id]['pid'];
						if ($ancestor_id != 0) {
							$cats[$ancestor_id]['child'] .= ",$category_id";
						}
						$cats[$category_id] = array(
							'pid' => $parent_id,
							'id' => $category_id,
							'name' => $category['name'],
							'url' => $category['seo_url'],
							'path' => $path,
							'child' => "$category_id",
							'link' => str_replace(HTTP_CATALOG, '', U('product/category', "cate_id=$category_id"))
						);
						if (isset($layouts[$category_id])) {
							$cats[$category_id]['layout'] = $layouts[$category_id];
						}
						if (!$category['_leaf']) {
							$cats[$category_id]['sub'] = '';
						}
					}
				}
				foreach ($cats as $cate_id => $cate) {
					$cats[$cate_id]['total'] = (int)$this->_getTotalProducts($cate['child'], $store_id, $language_id);
				}
				$cats['p'] = $root;
				cache_write("category-{$store_id}-{$language_id}.php", $cats);
			}
		}
	}

	private function _getTotalProducts($cate_ids, $store_id, $language_id) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM  @@product p LEFT JOIN  @@product_description pd ON (p.product_id = pd.product_id) LEFT JOIN  @@product_to_store p2s ON (p.product_id = p2s.product_id)";
		$sql .= " LEFT JOIN  @@product_to_category p2c ON (p.product_id = p2c.product_id)";
		$sql .= " WHERE pd.language_id = $language_id AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = $store_id";
		$sql .= " AND p2c.category_id IN ($cate_ids)";
		return $this->db->queryOne($sql);
	}
}
?>