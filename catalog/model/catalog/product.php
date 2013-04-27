<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE LOW_PRIORITY @@product SET viewed = (viewed + 1) WHERE product_id = " . (int)$product_id);
	}

	public function getProduct($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$query = $this->db->query("SELECT DISTINCT p.*, pd.name,pd.seo_title,pd.summary,pd.description,pd.meta_description,pd.meta_keyword,pd.tag, m.name AS manufacturer, (SELECT price FROM @@product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = " . (int)$customer_group_id . " AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM @@product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = " . (int)$customer_group_id . " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM @@product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = " . (int)$customer_group_id . ") AS reward FROM @@product p LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN @@manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = " . (int)$product_id . " AND pd.language_id = " . C('config_language_id') . " AND p.status = 1 AND p.date_available <= NOW() AND p2s.store_id = " . C('config_store_id'));

		if ($query->num_rows) {
			$query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
			$query->row['rating'] = round($query->row['rating']);
			return $query->row;
		} else {
			return false;
		}
	}

	public function getProducts($data = array(), $cache = true) {
		global $CATEGORIES;

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$cache = md5(http_build_query($data));

		$product_data = $this->cache->get('product.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);

		if (!$product_data) {
			$sql = "SELECT DISTINCT p.product_id FROM @@product p LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id)";

			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN @@product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			
			if (!empty($data['filter_attribute'])) {
				$sql .= " LEFT JOIN @@product_attribute pa ON (p.product_id = pa.product_id)";
			}
			
			$sql .= " WHERE pd.language_id = " . (int)C('config_language_id') . " AND p.status = 1 AND p.date_available <= NOW() AND p2s.store_id = " . (int)C('config_store_id');

			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";

				if (!empty($data['filter_name'])) {
					if (!empty($data['filter_description'])) {
						$sql .= "pd.name LIKE '%" . ES($data['filter_name']) . "%' OR pd.description AGAINST('" . ES($data['filter_name']) . "')";
					} else {
						$sql .= "pd.name LIKE '%" . ES($data['filter_name']) . "%'";
					}
				}

				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}

				if (!empty($data['filter_tag'])) {
					$sql .= "MATCH(pd.tag) AGAINST('" . ES($data['filter_tag']) . "')";
				}

				$sql .= ")";

				if (!empty($data['filter_name'])) {
					$sql .= " OR p.model = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.sku = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.upc = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.ean = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.jan = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.isbn = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.mpn = '" . ES($data['filter_name']) . "'";
				}
			}

			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					if (isset($CATEGORIES[$data['filter_category_id']])) {
						$sql .= " AND p2c.category_id IN (" . $CATEGORIES[$data['filter_category_id']]['children'] . ")";
					}
				} else {
					$sql .= " AND p2c.category_id = " . (int)$data['filter_category_id'];
				}
			}

			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = " . (int)$data['filter_manufacturer_id'];
			}
			
			if (!empty($data['filter_attribute'])) {
				$sql .= " AND pa.language_id = " . (int)C('config_language_id');
				foreach($data['filter_attribute'] as $attribute_id => $value) {
					$sql .= " AND (pa.attribute_id = $attribute_id AND pa.text = '" . ES($value) . "')";
				}
			}
			
			$sql .= " GROUP BY p.product_id";
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.quantity',
				'p.price',
				'rating',
				'p.sort_order',
				'p.date_added'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY " . $data['sort'];
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, pd.name DESC";
			} else {
				$sql .= " ASC, pd.name ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) $data['start'] = 0;
				if ($data['limit'] < 1) $data['limit'] = 20;

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}



			$product_ids = $this->db->queryArray($sql);
			$product_data = $this->getProductByIds($product_ids);

			if ($cache) {
				$this->cache->set('product.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
			}
		}

		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$sql = "SELECT DISTINCT ps.product_id FROM @@product_special ps LEFT JOIN @@product p ON (ps.product_id = p.product_id) LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY " . $data['sort'];
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, pd.name DESC";
		} else {
			$sql .= " ASC, pd.name ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$product_ids = $this->db->queryArray($sql);
		return $this->getProductByIds($product_ids);
	}

	public function getLatestProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$product_data = $this->cache->get('product.latest.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) {
			$product_ids = $this->db->queryArray("SELECT p.product_id FROM @@product p LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			$product_data = $this->getProductByIds($product_ids);

			$this->cache->set('product.latest.' . (int)C('config_language_id') . '.' . (int)C('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = array();

		$product_ids = $this->db->queryArray("SELECT p.product_id FROM @@product p LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);

		$product_data = $this->getProductByIds($product_ids);

		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$product_data = $this->cache->get('product.bestseller.' . (int)C('config_language_id') . '.' . (int)C('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$product_ids = $this->db->queryArray("SELECT op.product_id, COUNT(*) AS total FROM @@order_product op LEFT JOIN `@@order` o ON (op.order_id = o.order_id) LEFT JOIN `@@product` p ON (op.product_id = p.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit, 'product_id', 'product_id');

			$product_data = $this->getProductByIds($product_ids);

			$this->cache->set('product.bestseller.' . (int)C('config_language_id') . '.' . (int)C('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM @@product_attribute pa LEFT JOIN @@attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN @@attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN @@attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)C('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM @@product_attribute pa LEFT JOIN @@attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN @@attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)C('config_language_id') . "' AND pa.language_id = '" . (int)C('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM @@product_option po LEFT JOIN `@@option` o ON (po.option_id = o.option_id) LEFT JOIN @@option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)C('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM @@product_option_value pov LEFT JOIN @@option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN @@option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)C('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);
			}
      	}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$query = $this->db->query("SELECT * FROM @@product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		if ($query->num_rows) {
			return $query->rows;
		}
		elseif (C('config_use_global_discount')) {
			return $this->getGlobalDiscount($product_id, $customer_group_id);
		}

		return array();
	}

	public function getGlobalDiscount($product_id, $group_id) {
		$rates = C('config_global_discount_rates');
		if (!isset($rates[$group_id])) return array();
		$data = array();
		$price = $this->db->queryOne("SELECT price FROM @@product WHERE product_id = $product_id");
		foreach($rates[$group_id] as $k => $v) {
			$data[] = array(
				'quantity' =>$k,
				'price' => round($price * $v / 100, 2)
			);
		}
		return $data;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM @@product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {

		$product_ids = $this->db->queryArray("SELECT related_id FROM @@product_related pr LEFT JOIN @@product p ON (pr.related_id = p.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "'");

		return $this->getProductByIds($product_ids);
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM @@product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)C('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return  C('config_layout_product');
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM @@product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$cache = md5(http_build_query($data));

		$product_data = $this->cache->get('product.total.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);

		if (!$product_data) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM @@product p LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id)";

			if (!empty($data['filter_category_id'])) {
				$sql .= " LEFT JOIN @@product_to_category p2c ON (p.product_id = p2c.product_id)";
			}
			
			if (!empty($data['filter_attribute'])) {
				$sql .= " LEFT JOIN @@product_attribute pa ON (p.product_id = pa.product_id)";
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)C('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "'";

			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";

				if (!empty($data['filter_name'])) {
					if (!empty($data['filter_description'])) {
						$sql .= "pd.name LIKE '%" . ES($data['filter_name']) . "%' OR MATCH(pd.description) AGAINST('" . ES($data['filter_name']) . "')";
					} else {
						$sql .= "pd.name LIKE '%" . ES($data['filter_name']) . "%'";
					}
				}

				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}

				if (!empty($data['filter_tag'])) {
					$sql .= "MATCH(pd.tag) AGAINST('" . ES($data['filter_tag']) . "')";
				}

				$sql .= ")";

				if (!empty($data['filter_name'])) {
					$sql .= " OR p.model = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.sku = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.upc = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.ean = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.jan = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.isbn = '" . ES($data['filter_name']) . "'";
					$sql .= " OR p.mpn = '" . ES($data['filter_name']) . "'";
				}
			}

			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();

					$implode_data[] = (int)$data['filter_category_id'];

					M('catalog/category');

					$categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);

					foreach ($categories as $category_id) {
						$implode_data[] = (int)$category_id;
					}

					$sql .= " AND p2c.category_id IN (" . implode(', ', $implode_data) . ")";
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}

			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
			}
			
			if (!empty($data['filter_attribute'])) {
				$sql .= " AND pa.language_id = " . (int)C('config_language_id');
				foreach($data['filter_attribute'] as $attribute_id => $value) {
					$sql .= " AND (pa.attribute_id = $attribute_id AND pa.text = '" . ES($value) . "')";
				}
			}
			
			$query = $this->db->query($sql);

			$product_data = $query->row['total'];

			$this->cache->set('product.total.' . (int)C('config_language_id') . '.' . (int)C('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
		}

		return $product_data;
	}

	public function getTotalProductSpecials() {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM @@product_special ps LEFT JOIN @@product p ON (ps.product_id = p.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)C('config_store_id') . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getGroupProducts($ref) {
		$product_ids = $this->db->queryArray("SELECT DISTINCT product_id FROM @@product_group_products a, @@product_group b WHERE a.product_group_id=b.product_group_id AND b.ref_id = '$ref'");
		return $this->getProductByIds($product_ids);
	}

	public function getAlsoBuyProducts($product_id, $limit) {
		$customer_ids = $this->db->queryArray("SELECT o.customer_id FROM @@order o, @@order_product op WHERE o.order_id = op.order_id AND op.product_id = $product_id AND o.order_status_id > 0 ORDER BY o.order_id DESC LIMIT 50");
		
		if ($customer_ids) {
			$customer_ids = implode(',', $customer_ids);
			$product_ids = $this->db->queryArray("SELECT op.product_id FROM @@order o, @@order_product op WHERE o.order_id = op.order_id AND op.product_id <> $product_id AND o.customer_id IN ($customer_ids) GROUP BY op.product_id ORDER BY COUNT(op.product_id) LIMIT 50");
			$product_data = $this->getProductByIds($product_ids);
			if (count($product_data) > $limit) {
				return array_slice($product_data, 0, $limit);
			}
			else return $product_data;
		}
		else return array();
	}
	
	public function getProductByIds($product_ids) {
		if (!$product_ids) return array();

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}

		$products = $this->db->queryArray("SELECT p.*, pd.name,pd.summary, (SELECT price FROM @@product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = " . (int)$customer_group_id . " AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM @@product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = " . (int)$customer_group_id . " AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM @@product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = " . (int)$customer_group_id . ") AS reward FROM @@product p LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id) LEFT JOIN @@product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id IN (" . implode(',', $product_ids) . ") AND pd.language_id = " . C('config_language_id') . " AND p.status = 1 AND p.date_available <= NOW() AND p2s.store_id = " . C('config_store_id'), 'product_id');

		$product_data = array();
		foreach ($product_ids as $product_id) {
			if (isset($products[$product_id])) {
				$products[$product_id]['price'] = $products[$product_id]['discount'] ? $products[$product_id]['discount'] : $products[$product_id]['price'];
				$products[$product_id]['rating'] = round($products[$product_id]['rating']);
				$product_data[] = $products[$product_id];
			}
		}
		return $product_data;
	}
}
?>