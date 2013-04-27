<?php
class ModelCatalogProduct extends Model {
	public function addProduct($data) {
		if ($data['image']) {
			$data['image'] = html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8');
		}
		$data['date_added'] = 'NOW()';
		$product_id = $this->db->insert('product', $data);
		
		//description
		foreach ($data['product_description'] as $language_id => $value) {
			$value['product_id'] = $product_id;
			$value['language_id'] = $language_id;			
			$this->db->insert('product_description', $value);
		}

		//store
		if (!empty($data['product_store'])) {
			$product_store = array();
			foreach ($data['product_store'] as $store_id) {
				$product_store[] = array(
					'product_id' => $product_id,
					'store_id' => $store_id
				);
			}
			$this->db->insert('product_to_store', $product_store);
		}

		//attribute
		if (!empty($data['product_attribute'])) {
			$product_attributes = array();
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						if (is_array($product_attribute_description['text'])) {
							$product_attribute_description['text'] = implode(' | ', $product_attribute_description['text']);
						}
						$product_attributes[] = array(
							'product_id' => $product_id,
							'attribute_id' => $product_attribute['attribute_id'],
							'language_id' => $language_id,
							'text' => $product_attribute_description['text']
						);
					}
				}
			}
			if ($product_attributes) $this->db->insert('product_attribute', $product_attributes);
		}

		//option
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if (in_array($product_option['type'], array('select', 'radio', 'checkbox', 'image'))) {
					if (!empty($product_option['product_option_value'])) {
						$product_option_data = array(
							'product_id' => $product_id,
							'option_id' => $product_option['option_id'],
							'required' => $product_option['required']
						);						

						$product_option_id = $this->db->insert('product_option', $product_option_data);
						$product_option_values = array();
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$product_option_values[] = array(
								'product_option_id' => $product_option_id,
								'product_id' => $product_id,
								'option_id' => $product_option['option_id'],
								'option_value_id' => $product_option_value['option_value_id'],
								'quantity' => $product_option_value['quantity'],
								'subtract' => $product_option_value['subtract'],
								'price' => $product_option_value['price'],
								'price_prefix' => $product_option_value['price_prefix'],
								'points' => $product_option_value['points'],
								'points_prefix' => $product_option_value['points_prefix'],
								'weight' => $product_option_value['weight'],
								'weight_prefix' => $product_option_value['weight_prefix']
							);
						}
						$this->db->insert('product_option_value', $product_option_values);
					}

				} else {
					$product_option_data = array(
						'product_id' => $product_id,
						'option_id' => $product_option['option_id'],
						'option_value' => $product_option['option_value'],
						'required' => $product_option['required']
					);
					$this->db->insert('product_option', $product_option_data);
				}
			}
		}
		
		//discount
		if (!empty($data['product_discount'])) {
			$product_discounts = array();
			foreach ($data['product_discount'] as $product_discount) {
				$product_discounts[] = array(
					'product_id' => $product_id,
					'customer_group_id' => $product_discount['customer_group_id'],
					'quantity' =>  $product_discount['quantity'],
					'priority' =>  $product_discount['priority'],
					'price' =>  $product_discount['price'],
					'date_start' =>  $product_discount['date_start'],
					'date_end' =>  $product_discount['date_end']
				);
			}
			$this->db->insert('product_discount', $product_discounts);
		}
		
		//special
		if (!empty($data['product_special'])) {
			$product_specials = array();
			foreach ($data['product_special'] as $product_special) {
				$product_specials[] = array(
					'product_id' => $product_id,
					'customer_group_id' => $product_special['customer_group_id'],
					'priority' => $product_special['priority'],
					'price' => $product_special['price'],
					'date_start' => $product_special['date_start'],
					'date_end' => $product_special['date_end']
				);
			}
			$this->db->insert('product_special', $product_specials);
		}
		
		//image
		if (!empty($data['product_image'])) {
			$product_images = array();
			foreach ($data['product_image'] as $product_image) {
				$product_images[] = array(
					'product_id' => $product_id,
					'image' => html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8'),
					'sort_order' => $product_image['sort_order']
				);
			}
			$this->db->insert('product_image', $product_images);
		}
		
		//download
		if (!empty($data['product_download'])) {
			$product_downloads = array();
			foreach ($data['product_download'] as $download_id) {
				$product_downloads[] = array(
					'product_id' => $product_id,
					'download_id' => $download_id
				);
			}
			$this->db->insert('product_to_download', $product_downloads);
		}
		
		//category
		if ($data['cate_id'] 
			&& (!isset($data['product_category']) 
					|| !in_array($data['cate_id'],$data['product_category'])
			)) {
			$data['product_category'][] = $data['cate_id'];
		}
		
		$product_categories = array();
		foreach ($data['product_category'] as $category_id) {
			$product_categories[] = array(
				'product_id' => $product_id,
				'category_id' => $category_id
			);
		}
		$this->db->insert('product_to_category', $product_categories);
		
		//related
		if (!empty($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query('REPLACE INTO @@product_related SET product_id = ' . (int)$product_id . ', related_id = ' . (int)$related_id);
				$this->db->query('REPLACE INTO @@product_related SET product_id = ' . (int)$related_id . ', related_id = ' . (int)$product_id);
			}
		}

		//reward
		if (!empty($data['product_reward'])) {
			$product_rewards = array();
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$product_rewards[] = array(
					'product_id' => $product_id,
					'customer_group_id' => $customer_group_id,
					'points' => $product_reward['points']
				);
			}
			$this->db->insert('product_reward', $product_rewards);
		}

		//layout
		if (!empty($data['product_layout'])) {
			$product_layouts = array();
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {					
					$layout['product_id'] = $product_id;
					$layout['store_id'] = $store_id;
					$product_layouts[] = $layout;
				}
			}
			if ($product_layouts) $this->db->insert('product_to_layout', $product_layouts);
		}
		
		
		$this->updateLink($product_id, $data['cate_id'], $data['seo_url']);
		
		//$this->cache->delete('product');
	}

	public function editProduct($product_id, $data) {
		if ($data['image']) {
			$data['image'] = html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8');
		}
		$data['date_modified'] = 'NOW()';
		
		$this->db->update('product', $data, array('product_id' => $product_id));

		$this->db->query('DELETE FROM @@product_description WHERE product_id = ' . (int)$product_id);

		foreach ($data['product_description'] as $language_id => $value) {
			$value['product_id'] = $product_id;
			$value['language_id'] = $language_id;			
			$this->db->insert('product_description', $value);
		}

		$this->db->query('DELETE FROM @@product_to_store WHERE product_id = ' . (int)$product_id);
		
		
		if (isset($data['product_store'])) {
			$product_store = array();
			foreach ($data['product_store'] as $store_id) {
				$product_store[] = array(
					'product_id' => $product_id,
					'store_id' => $store_id
				);
			}
			$this->db->insert('product_to_store', $product_store);
		}

		$this->db->query('DELETE FROM @@product_attribute WHERE product_id = ' . (int)$product_id);
		
		if (!empty($data['product_attribute'])) {
			$product_attributes = array();
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						if (is_array($product_attribute_description['text'])) {
							$product_attribute_description['text'] = implode(' | ', $product_attribute_description['text']);
						}
						$product_attributes[] = array(
							'product_id' => $product_id,
							'attribute_id' => $product_attribute['attribute_id'],
							'language_id' => $language_id,
							'text' => $product_attribute_description['text']
						);
					}
				}
			}
			if ($product_attributes) $this->db->insert('product_attribute', $product_attributes);
		}

		$this->db->query("DELETE FROM @@product_option WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_option_value WHERE product_id = " . (int)$product_id);
		
		//option
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if (in_array($product_option['type'], array('select', 'radio', 'checkbox', 'image'))) {
					if (!empty($product_option['product_option_value'])) {
						$product_option_data = array(
							'product_id' => $product_id,
							'option_id' => $product_option['option_id'],
							'required' => $product_option['required']
						);						

						$product_option_id = $this->db->insert('product_option', $product_option_data);
						$product_option_values = array();
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$product_option_values[] = array(
								'product_option_id' => $product_option_id,
								'product_id' => $product_id,
								'option_id' => $product_option['option_id'],
								'option_value_id' => $product_option_value['option_value_id'],
								'quantity' => $product_option_value['quantity'],
								'subtract' => $product_option_value['subtract'],
								'price' => $product_option_value['price'],
								'price_prefix' => $product_option_value['price_prefix'],
								'points' => $product_option_value['points'],
								'points_prefix' => $product_option_value['points_prefix'],
								'weight' => $product_option_value['weight'],
								'weight_prefix' => $product_option_value['weight_prefix']
							);
						}
						$this->db->insert('product_option_value', $product_option_values);
					}

				} else {
					$product_option_data = array(
						'product_id' => $product_id,
						'option_id' => $product_option['option_id'],
						'option_value' => $product_option['option_value'],
						'required' => $product_option['required']
					);
					$this->db->insert('product_option', $product_option_data);
				}
			}
		}

		//discount
		$this->db->query("DELETE FROM @@product_discount WHERE product_id = " . (int)$product_id);
		
		if (!empty($data['product_discount'])) {
			$product_discounts = array();
			foreach ($data['product_discount'] as $product_discount) {
				$product_discounts[] = array(
					'product_id' => $product_id,
					'customer_group_id' => $product_discount['customer_group_id'],
					'quantity' =>  $product_discount['quantity'],
					'priority' =>  $product_discount['priority'],
					'price' =>  $product_discount['price'],
					'date_start' =>  $product_discount['date_start'],
					'date_end' =>  $product_discount['date_end']
				);
			}
			$this->db->insert('product_discount', $product_discounts);
		}


		$this->db->query("DELETE FROM @@product_special WHERE product_id = " . (int)$product_id);
		if (!empty($data['product_special'])) {
			$product_specials = array();
			foreach ($data['product_special'] as $product_special) {
				$product_specials[] = array(
					'product_id' => $product_id,
					'customer_group_id' => $product_special['customer_group_id'],
					'priority' => $product_special['priority'],
					'price' => $product_special['price'],
					'date_start' => $product_special['date_start'],
					'date_end' => $product_special['date_end']
				);
			}
			$this->db->insert('product_special', $product_specials);
		}

		$this->db->query("DELETE FROM @@product_image WHERE product_id = " . (int)$product_id);
		if (!empty($data['product_image'])) {
			$product_images = array();
			foreach ($data['product_image'] as $product_image) {
				$product_images[] = array(
					'product_id' => $product_id,
					'image' => html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8'),
					'sort_order' => $product_image['sort_order']
				);
			}
			$this->db->insert('product_image', $product_images);
		}
		
		$this->db->query("DELETE FROM @@product_to_download WHERE product_id = " . (int)$product_id);
		if (!empty($data['product_download'])) {
			$product_downloads = array();
			foreach ($data['product_download'] as $download_id) {
				$product_downloads[] = array(
					'product_id' => $product_id,
					'download_id' => $download_id
				);
			}
			$this->db->insert('product_to_download', $product_downloads);
		}

		$this->db->query("DELETE FROM @@product_to_category WHERE product_id = " . (int)$product_id);
		if ($data['cate_id'] 
			&& (!isset($data['product_category']) 
					|| !in_array($data['cate_id'],$data['product_category'])
			)) {
			$data['product_category'][] = $data['cate_id'];
		}
		
		$product_categories = array();
		foreach ($data['product_category'] as $category_id) {
			$product_categories[] = array(
				'product_id' => $product_id,
				'category_id' => $category_id
			);
		}
		$this->db->insert('product_to_category', $product_categories);
		
		//related
		$this->db->delete('product_related', "product_id = $product_id OR related_id = $product_id");
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query('REPLACE INTO @@product_related SET product_id = ' . (int)$product_id . ', related_id = ' . (int)$related_id);
				$this->db->query('REPLACE INTO @@product_related SET product_id = ' . (int)$related_id . ', related_id = ' . (int)$product_id);
			}
		}

		//reward
		$this->db->delete('product_reward', array('product_id' => $product_id));
		if (!empty($data['product_reward'])) {
			$product_rewards = array();
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$product_rewards[] = array(
					'product_id' => $product_id,
					'customer_group_id' => $customer_group_id,
					'points' => $product_reward['points']
				);
			}
			$this->db->insert('product_reward', $product_rewards);
		}
		
		//layout
		$this->db->delete('product_to_layout', array('product_id' => $product_id));
		if (!empty($data['product_layout'])) {
			$product_layouts = array();
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {					
					$layout['product_id'] = $product_id;
					$layout['store_id'] = $store_id;
					$product_layouts[] = $layout;
				}
			}
			if ($product_layouts) $this->db->insert('product_to_layout', $product_layouts);
		}
		
		$this->updateLink($product_id, $data['cate_id'], $data['seo_url']);
		
		//$this->cache->delete('product');
	}

	public function copyProduct($product_id) {
	
		$data = $this->db->get('product', array('product_id' => $product_id));
		
		if ($data) {
			unset($data['product_id']);
			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = 0;
			$data['seo_url'] = '';
			$data['status'] = 0;
			$data['reviews'] = 0;
			$data['rating'] = 0;
			
			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));
			$data = array_merge($data, array('product_discount' => $this->getProductDiscounts($product_id)));
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));
			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_reward' => $this->getProductRewards($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_download' => $this->getProductDownloads($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));
			$data = array_merge($data, array('product_store' => $this->getProductStores($product_id)));

			$this->addProduct($data);
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM @@product WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_attribute WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_description WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_discount WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_image WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_option WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_option_value WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_related WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_related WHERE related_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_reward WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_special WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_to_category WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_to_download WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_to_layout WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@product_to_store WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM @@review WHERE product_id = " . (int)$product_id);

		//$this->cache->delete('product');
	}

	public function getProduct($product_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM @@product p LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)C('config_language_id') . "'");
	}

	public function getProducts($data, &$total) {
		$sql = 'SELECT [*] FROM @@product p LEFT JOIN @@product_description pd ON (p.product_id = pd.product_id)';

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN @@product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		$sql .= " WHERE pd.language_id = " . (int)C('config_language_id');

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . ES($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . ES($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . ES($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = " . (int)$data['filter_quantity'];
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = " . (int)$data['filter_status'];
		}

		if (!empty($data['filter_category_id'])) {
			$sql .= " AND p2c.category_id = " . (int)$data['filter_category_id'];
		}

		if (!empty($data['filter_supplier_id'])) {
			$sql .= " AND p.supplier_id = " . (int)$data['filter_supplier_id'];
		}
		$total = $this->db->queryOne(str_replace('[*]', 'count(*) as total', $sql));

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order',
			'p.product_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

		$query = $this->db->query(str_replace('[*]', 'p.*, pd.name', $sql));
		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query('SELECT * FROM @@product_description WHERE product_id = ' . (int)$product_id);

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'seo_title'        => $result['seo_title'],
				'summary'          => $result['summary'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query('SELECT pa.attribute_id, a.attribute_group_id, a.`type`, a.`extend`, ad.name FROM @@product_attribute pa LEFT JOIN @@attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN @@attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = ' . (int)$product_id . ' AND ad.language_id = ' . (int)C('config_language_id') . ' GROUP BY pa.attribute_id');

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query('SELECT * FROM @@product_attribute WHERE product_id = ' . (int)$product_id . ' AND attribute_id = ' . (int)$product_attribute['attribute_id']);

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}
			
			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'attribute_group_id'            => $product_attribute['attribute_group_id'],
				'name'                          => $product_attribute['name'],
				'type'                          => $product_attribute['type'],
				'extend'                        => $product_attribute['extend'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getAttributeGroupByIds($attribute_group_ids) {
		if (is_array($attribute_group_ids)) $attribute_group_ids = implode(',', $attribute_group_ids);
		
		return $this->db->queryArray("SELECT * FROM @@attribute_group_description WHERE language_id=" . C('config_language_id') . " AND attribute_group_id IN ($attribute_group_ids)", 'attribute_group_id', 'name');
	}
	
	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM @@product_option po LEFT JOIN `@@option` o ON (po.option_id = o.option_id) LEFT JOIN @@option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)C('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM @@product_option_value pov LEFT JOIN @@option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN @@option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)C('config_language_id') . "' ORDER BY ov.sort_order");

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
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
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

	public function getProductImages($product_id) {
		$query = $this->db->query('SELECT * FROM @@product_image WHERE product_id = ' . (int)$product_id);
		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query('SELECT * FROM @@product_discount WHERE product_id = ' . (int)$product_id . ' ORDER BY quantity, priority, price');

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM @@product_special WHERE product_id = " . (int)$product_id . " ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM @@product_reward WHERE product_id = " . (int)$product_id);

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {

		return $this->db->queryArray("SELECT download_id FROM @@product_to_download WHERE product_id = " . (int)$product_id);
	}

	public function getProductStores($product_id) {

		return $this->db->queryArray("SELECT store_id FROM @@product_to_store WHERE product_id = " . (int)$product_id);
	}

	public function getProductLayouts($product_id) {

		return $this->db->queryArray("SELECT store_id, layout_id FROM @@product_to_layout WHERE product_id = " . (int)$product_id, 'store_id', 'layout_id');
	}

	public function getProductCategories($product_id) {

		return $this->db->queryArray("SELECT category_id FROM @@product_to_category WHERE product_id = " . (int)$product_id);
	}

	public function getProductRelated($product_id) {

		return $this->db->queryArray("SELECT related_id FROM @@product_related WHERE product_id = " . (int)$product_id);
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product WHERE tax_class_id = " . (int)$tax_class_id);
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product WHERE stock_status_id = " . (int)$stock_status_id);
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product WHERE weight_class_id = " . (int)$weight_class_id);
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product WHERE length_class_id = " . (int)$length_class_id);
	}

	public function getTotalProductsByDownloadId($download_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product_to_download WHERE download_id = " . (int)$download_id);
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product WHERE manufacturer_id = " . (int)$manufacturer_id);
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product_attribute WHERE attribute_id = " . (int)$attribute_id);
	}

	public function getTotalProductsByOptionId($option_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product_option WHERE option_id = " . (int)$option_id);
	}

	public function getTotalProductsByLayoutId($layout_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@product_to_layout WHERE layout_id = " . (int)$layout_id);
	}

	public function updateLink($product_id, $cate_id, $seo_url) {
		if (C('config_seo_url')) {
			$link = U('product/product', "cate_id={$cate_id}&product_id={$product_id}&seo_url={$seo_url}");
		}
		else {
			$link = U('product/product', "product_id={$product_id}");
		}
		$link = str_replace(HTTP_CATALOG, '', $link);
		$this->db->query("UPDATE @@product SET link='$link' WHERE product_id=$product_id");
	}
}
?>