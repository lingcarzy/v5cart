<?php
class ModelLocalisationLanguage extends Model {
	public function addLanguage($data) {

		$language_id = $this->db->insert('language', $data);

		// Attribute
		$query = $this->db->query("SELECT * FROM  @@attribute_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $attribute) {
			$this->db->query("INSERT INTO  @@attribute_description SET attribute_id = " . $attribute['attribute_id'] . ", language_id = " . $language_id . ", name = '" . ES($attribute['name']) . "'");
		}

		// Attribute Group
		$query = $this->db->query("SELECT * FROM  @@attribute_group_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $attribute_group) {
			$this->db->query("INSERT INTO  @@attribute_group_description SET attribute_group_id = " . $attribute_group['attribute_group_id'] . ", language_id = " . $language_id . ", name = '" . ES($attribute_group['name']) . "'");
		}

		// Banner
		$query = $this->db->query("SELECT * FROM  @@banner_image_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $banner_image) {
			$this->db->query("INSERT INTO  @@banner_image_description SET banner_image_id = " . $banner_image['banner_image_id'] . ", banner_id = " . (int)$banner_image['banner_id'] . ", language_id = " . (int)$language_id . ", title = '" . ES($banner_image['title']) . "'");
		}

		// Category
		$query = $this->db->query("SELECT * FROM  @@category_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO  @@category_description SET category_id = " . (int)$category['category_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($category['name']) . "', meta_description = '" . ES($category['meta_description']) . "', meta_keyword = '" . ES($category['meta_keyword']) . "', description = '" . ES($category['description']) . "'");
		}

		// Customer Group
		$query = $this->db->query("SELECT * FROM  @@customer_group_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $customer_group) {
			$this->db->query("INSERT INTO  @@customer_group_description SET customer_group_id = " . (int)$customer_group['customer_group_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($customer_group['name']) . "', description = '" . ES($customer_group['description']) . "'");
		}

		// Download
		$query = $this->db->query("SELECT * FROM  @@download_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO  @@download_description SET download_id = " . (int)$download['download_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($download['name']) . "'");
		}

		// Pages
		$query = $this->db->query("SELECT * FROM  @@page_content WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $page) {
			$this->db->query("INSERT INTO  @@page_content SET page_id = " . (int)$page['page_id'] . ", language_id = " . (int)$language_id . ", title = '" . ES($page['title']) . "', description = '" . ES($page['description']) . "'");
		}

		// Length
		$query = $this->db->query("SELECT * FROM  @@length_class_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO  @@length_class_description SET length_class_id = " . (int)$length['length_class_id'] . ", language_id = " . (int)$language_id . ", title = '" . ES($length['title']) . "', unit = '" . ES($length['unit']) . "'");
		}

		// Option
		$query = $this->db->query("SELECT * FROM  @@option_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $option) {
			$this->db->query("INSERT INTO  @@option_description SET option_id = " . (int)$option['option_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($option['name']) . "'");
		}

		// Option Value
		$query = $this->db->query("SELECT * FROM  @@option_value_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO  @@option_value_description SET option_value_id = " . (int)$option_value['option_value_id'] . ", language_id = " . (int)$language_id . ", option_id = " . (int)$option_value['option_id'] . ", name = '" . ES($option_value['name']) . "'");
		}

		// Order Status
		$query = $this->db->query("SELECT * FROM  @@order_status WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO  @@order_status SET order_status_id = " . (int)$order_status['order_status_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($order_status['name']) . "'");
		}

		// Product
		$query = $this->db->query("SELECT * FROM  @@product_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO  @@product_description SET product_id = " . (int)$product['product_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($product['name']) . "', meta_description = '" . ES($product['meta_description']) . "', meta_keyword = '" . ES($product['meta_keyword']) . "', description = '" . ES($product['description']) . "', tag = '" . ES($product['tag']) . "'");
		}

		// Product Attribute
		$query = $this->db->query("SELECT * FROM  @@product_attribute WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $product_attribute) {
			$this->db->query("INSERT INTO  @@product_attribute SET product_id = " . (int)$product_attribute['product_id'] . ", attribute_id = " . (int)$product_attribute['attribute_id'] . ", language_id = " . (int)$language_id . ", text = '" . ES($product_attribute['text']) . "'");
		}

		// Return Action
		$query = $this->db->query("SELECT * FROM  @@return_action WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO  @@return_action SET return_action_id = " . (int)$return_action['return_action_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($return_action['name']) . "'");
		}

		// Return Reason
		$query = $this->db->query("SELECT * FROM  @@return_reason WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO  @@return_reason SET return_reason_id = " . (int)$return_reason['return_reason_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($return_reason['name']) . "'");
		}

		// Return Status
		$query = $this->db->query("SELECT * FROM  @@return_status WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO  @@return_status SET return_status_id = " . (int)$return_status['return_status_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($return_status['name']) . "'");
		}

		// Stock Status
		$query = $this->db->query("SELECT * FROM  @@stock_status WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO  @@stock_status SET stock_status_id = " . (int)$stock_status['stock_status_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($stock_status['name']) . "'");
		}

		// Voucher Theme
		$query = $this->db->query("SELECT * FROM  @@voucher_theme_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO  @@voucher_theme_description SET voucher_theme_id = " . (int)$voucher_theme['voucher_theme_id'] . ", language_id = " . (int)$language_id . ", name = '" . ES($voucher_theme['name']) . "'");
		}

		// Weight Class
		$query = $this->db->query("SELECT * FROM  @@weight_class_description WHERE language_id = " . (int)C('config_language_id'));

		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO  @@weight_class_description SET weight_class_id = " . (int)$weight_class['weight_class_id'] . ", language_id = " . (int)$language_id . ", title = '" . ES($weight_class['title']) . "', unit = '" . ES($weight_class['unit']) . "'");
		}
	}

	public function editLanguage($language_id, $data) {
		$this->db->update('language', $data, array('language_id' => $language_id));
	}

	public function deleteLanguage($language_id) {
		$this->db->query("DELETE FROM  @@language WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@attribute_description WHERE language_id = " . (int)$language_id);
		$this->db->query("DELETE FROM  @@attribute_group_description WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@banner_image_description WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@category_description WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@customer_group_description WHERE language_id = " . (int)$language_id);
		$this->db->query("DELETE FROM  @@download_description WHERE language_id = " . (int)$language_id);
		$this->db->query("DELETE FROM  @@page_content WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@length_class_description WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@option_description WHERE language_id = " . (int)$language_id);
		$this->db->query("DELETE FROM  @@option_value_description WHERE language_id = " . (int)$language_id);
		$this->db->query("DELETE FROM  @@order_status WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@product_attribute WHERE language_id = " . (int)$language_id);
		$this->db->query("DELETE FROM  @@product_description WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@return_action WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@return_reason WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@return_status WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@stock_status WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@voucher_theme_description WHERE language_id = " . (int)$language_id);

		$this->db->query("DELETE FROM  @@weight_class_description WHERE language_id = " . (int)$language_id);
	}

	public function getLanguage($language_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM  @@language WHERE language_id = " . (int)$language_id);
	}

	public function getLanguages($data = array()) {

		$sql = "SELECT * FROM  @@language";

		$sort_data = array(
			'name',
			'code',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order, name";
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

	public function getTotalLanguages() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@language");
	}
}
?>