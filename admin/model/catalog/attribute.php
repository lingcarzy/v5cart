<?php
class ModelCatalogAttribute extends Model {
	public function addAttribute($data) {
		$attribute_id = $this->db->insert('attribute', $data);

		foreach ($data['attribute_description'] as $language_id => $value) {
			$value['attribute_id'] = $attribute_id;
			$value['language_id'] = $language_id;
			$this->db->insert('attribute_description', $value);
		}
		$this->cache();
	}

	public function editAttribute($attribute_id, $data) {
		$this->db->update('attribute', $data, array('attribute_id' => $attribute_id));

		$this->db->delete('attribute_description', array('attribute_id' => $attribute_id));

		foreach ($data['attribute_description'] as $language_id => $value) {
			$value['attribute_id'] = $attribute_id;
			$value['language_id'] = $language_id;
			$this->db->insert('attribute_description', $value);
		}
		$this->cache();
	}

	public function deleteAttribute($attribute_id) {
		$this->db->query("DELETE FROM @@attribute WHERE attribute_id = " . (int)$attribute_id);
		$this->db->query("DELETE FROM @@attribute_description WHERE attribute_id = " . (int)$attribute_id);
		$this->cache();
	}

	public function getAttribute($attribute_id) {
		return $this->db->queryOne("SELECT * FROM @@attribute WHERE attribute_id = " . (int)$attribute_id);
	}

	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM @@attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = " . (int)C('config_language_id') . ") AS attribute_group FROM @@attribute a LEFT JOIN @@attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = " . (int)C('config_language_id');

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . ES($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_type'])) {
			$sql .= " AND a.`type` = '" . ES($data['filter_type']) . "'";
		}
		
		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = " . (int)$data['filter_attribute_group_id'];
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";
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

	public function getAttributeDescriptions($attribute_id) {
		$attribute_data = array();

		$query = $this->db->query("SELECT * FROM @@attribute_description WHERE attribute_id = " . (int)$attribute_id);

		foreach ($query->rows as $result) {
			$attribute_data[$result['language_id']] = array('name' => $result['name'], 'value' => $result['value']);
		}

		return $attribute_data;
	}

	public function getTotalAttributes($data) {
		$where = "1";
		if (!empty($data['filter_attribute_group_id'])) {
			$where = "attribute_group_id = " . (int)$data['filter_attribute_group_id'];
		}
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@attribute WHERE $where");
	}

	public function getTotalAttributesByAttributeGroupId($attribute_group_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@attribute WHERE attribute_group_id = " . (int)$attribute_group_id);
	}
	
	public function cache() {
		$query = $this->db->query("SELECT * FROM @@attribute");
		$data = array();
		foreach ($query->rows as $row) {
			$attribute_description = $this->getAttributeDescriptions($row['attribute_id']);
			$values = array();
			$names = array();
			foreach($attribute_description as $language_id => $description) {
				$values[$language_id] = $description['value'];
				$names[$language_id] = $description['name'];
			}
			$data[$row['attribute_id']] = array(
				'type'   => $row['type'],
				'extend' => $row['extend'],
				'names'  => $names,
				'values' => $values
			);
		}
		cache_write('attribute.php', $data);
	}
	
	function getAttributeFormField($attribute_id, $attribute_row, $value = null) {
		$html = '';
		$attributes = C('cache_attributes');
		if (!$attributes) {
			$attributes = cache_read('attribute.php');
			C('cache_attributes', $attributes);
		}
		
		$attribute = $attributes[$attribute_id];
		
		$languages = C('cache_language');

		foreach ($languages as $language) {
			$language_id = $language['language_id'];
			$flag = '<img src="view/image/flags/' . $language['image'] . '" title="' . $language['name'] . '" align="top" /><br />';
			$name = "name='product_attribute[{$attribute_row}][product_attribute_description][{$language_id}][text]' {$attribute['extend']}";
			switch ($attribute['type']) {
				case 'text':
					if (!$value) $default = $attribute['values'][$language_id];
					else $default = $value[$language_id]['text'];
					$html .= "<input type='text' {$name} value='{$default}'>\n{$flag}";
				break;
				case 'select':
					$options = explode('|', $attribute['values'][$language_id]);
					$html .= "<select {$name}><option value=''></option>";
					foreach ($options as $option) {
						$selected = FALSE;
						if (strpos($option, '(*)') !== FALSE) {
							$selected = TRUE;
						}
						$option = str_replace('(*)', '', $option);
						if ($value !== NULL) {
							if ($value[$language_id]['text'] == $option) $selected = TRUE;
							else $selected = FALSE;
						}
						$selected = $selected ? " selected='selected'" : '';
						$html .= "<option value='{$option}'{$selected}>{$option}</option>";
					}
					$html .= "</select>\n{$flag}";
				break;
				case 'checkbox':
					$options = explode('|', $attribute['values'][$language_id]);
					foreach ($options as $i => $option) {
						$checked = FALSE;
						if (strpos($option, '(*)') !== FALSE) {
							if (!$value) $checked = TRUE;
						}
						$option = str_replace('(*)', '', $option);
						if ($value !== NULL) {
							 if (in_array($option, explode(' | ', $value[$language_id]['text']))) $checked = TRUE;
							 else $checked = FALSE;
						}
						$checked = $checked ? " checked='checked'" : '';
						$html .= "<input type='checkbox' name='product_attribute[{$attribute_row}][product_attribute_description][{$language_id}][text][]' value='{$option}'{$checked}> {$option}";
					}
					$html .= "\n$flag";
				break;
				default:
					if (!$value) $default = $attribute['values'][$language_id];
					else $default = $value[$language_id]['text'];
					$html .= "<textarea {$name}>{$default}</textarea>\n{$flag}";
			}
		}
		return $html;
	}
}
?>