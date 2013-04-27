<?php
class ModelSaleCoupon extends Model {
	public function addCoupon($data) {
		$data['date_added'] = 'NOW()';
		$coupon_id = $this->db->insert('coupon', $data);
		
		if (!empty($data['coupon_product'])) {
			$coupon_product = array();
      		foreach ($data['coupon_product'] as $product_id) {
				$coupon_product[] = array(
					'coupon_id' => $coupon_id,
					'product_id' => $product_id
				);
      		}
			$this->db->insert('coupon_product', $coupon_product);
		}
		
		if (!empty($data['coupon_category'])) {
			$coupon_category = array();
      		foreach ($data['coupon_category'] as $category_id) {
				$coupon_category[] = array(
					'coupon_id' =>  $coupon_id,
					'category_id' => $category_id
				);
      		}
			$this->db->insert('coupon_category', $coupon_category);
		}
	}
	
	public function editCoupon($coupon_id, $data) {
		$this->db->update('coupon', $data, array('coupon_id' => $coupon_id));
		
		$this->db->query("DELETE FROM @@coupon_product WHERE coupon_id = " . (int)$coupon_id);
		
		if (!empty($data['coupon_product'])) {
			$coupon_product = array();
      		foreach ($data['coupon_product'] as $product_id) {
				$coupon_product[] = array(
				'coupon_id' => $coupon_id,
				'product_id' => $product_id
				);
      		}
			$this->db->insert('coupon_product', $coupon_product);
		}
		
		$this->db->query("DELETE FROM @@coupon_category WHERE coupon_id = " . (int)$coupon_id);
		
		if (!empty($data['coupon_category'])) {
			$coupon_category = array();
      		foreach ($data['coupon_category'] as $category_id) {
				$coupon_category[] = array(
				'coupon_id' =>  $coupon_id,
				'category_id' => $category_id
				);
      		}
			$this->db->insert('coupon_category', $coupon_category);
		}
	}
	
	public function deleteCoupon($coupon_id) {
      	$this->db->query("DELETE FROM @@coupon WHERE coupon_id = " . (int)$coupon_id);
		$this->db->query("DELETE FROM @@coupon_product WHERE coupon_id = " . (int)$coupon_id);
		$this->db->query("DELETE FROM @@coupon_category WHERE coupon_id = " . (int)$coupon_id);
		$this->db->query("DELETE FROM @@coupon_history WHERE coupon_id = " . (int)$coupon_id);		
	}
	
	public function getCoupon($coupon_id) {
      	return $this->db->queryOne("SELECT * FROM @@coupon WHERE coupon_id = " . (int)$coupon_id);
	}

	public function getCouponByCode($code) {
      	return $this->db->queryOne("SELECT * FROM @@coupon WHERE code = '" . ES($code) . "'");
	}
		
	public function getTotalCoupons() {
		return $this->db->queryOne("SELECT COUNT(*) as total FROM @@coupon");
	}
	
	public function getCoupons($filter = array()) {		
		$sql = "SELECT * FROM @@coupon ORDER BY $filter[sort]  $filter[order] LIMIT $filter[start], $filter[limit]";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getCouponProducts($coupon_id) {
		return $this->db->queryArray("SELECT product_id FROM @@coupon_product WHERE coupon_id = " . (int)$coupon_id);
	}
	
	public function getCouponCategories($coupon_id) {
		return $this->db->queryArray("SELECT category_id FROM @@coupon_category WHERE coupon_id = " . (int)$coupon_id);
	}
	
	public function getCouponHistories($coupon_id, $start = 0, $limit = 10) {	
		$query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM @@coupon_history ch LEFT JOIN @@customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT $start, $limit");
		return $query->rows;
	}
	
	public function getTotalCouponHistories($coupon_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@coupon_history WHERE coupon_id = " . (int)$coupon_id);
	}
}
?>