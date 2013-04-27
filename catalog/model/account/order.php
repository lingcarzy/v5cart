<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order = $this->db->queryOne("SELECT * FROM `@@order` WHERE order_id = " . (int)$order_id . " AND customer_id = " . (int)$this->customer->getId() . " AND order_status_id > 0");

		if (!$order) return false;

		$country = $this->db->queryOne("SELECT iso_code_2,iso_code_3 FROM `@@country` WHERE country_id = " . $order['payment_country_id']);

		if ($country) {
			$order['payment_iso_code_2'] = $country['iso_code_2'];
			$order['payment_iso_code_3'] = $country['iso_code_3'];
		} else {
			$order['payment_iso_code_2'] = '';
			$order['payment_iso_code_3'] = '';
		}

		$code = $this->db->queryOne("SELECT `code` FROM `@@zone` WHERE zone_id = " . $order['payment_zone_id']);

		if ($code) {
			$order['payment_zone_code'] = $code;
		} else {
			$order['payment_zone_code'] = '';
		}

		$country = $this->db->queryOne("SELECT iso_code_2,iso_code_3 FROM `@@country` WHERE country_id = " . $order['shipping_country_id']);

		if ($country) {
			$order['shipping_iso_code_2'] = $country['iso_code_2'];
			$order['shipping_iso_code_3'] = $country['iso_code_3'];
		} else {
			$order['shipping_iso_code_2'] = '';
			$order['shipping_iso_code_3'] = '';
		}

		$code = $this->db->queryOne("SELECT `code` FROM `@@zone` WHERE zone_id = " . $order['shipping_zone_id']);

		if ($code) {
			$order['shipping_zone_code'] = $code;
		} else {
			$order['shipping_zone_code'] = '';
		}

		return $order;
	}

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 5;

		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `@@order` o LEFT JOIN @@order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = " . (int)$this->customer->getId() . " AND o.order_status_id > 0 AND os.language_id = " . (int)C('config_language_id') . " ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM @@order_product WHERE order_id = " . (int)$order_id);

		return $query->rows;
	}

	public function getOrderShippingHistory($order_id) {
		$query = $this->db->query("SELECT * FROM @@order_shipping_history WHERE order_id = " . (int)$order_id);
		foreach ($query->rows as $i => $row) {
			$query->rows[$i]['products'] = unserialize($row['products']);
		}
		return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM @@order_option WHERE order_id = " . (int)$order_id . " AND order_product_id = " . (int)$order_product_id);

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM `@@order_voucher` WHERE order_id = " . (int)$order_id);

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM @@order_total WHERE order_id = " . (int)$order_id . " ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM @@order_history oh LEFT JOIN @@order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = " . (int)$order_id . " AND oh.notify = 1 AND os.language_id = " . (int)C('config_language_id') . " ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getOrderDownloads($order_id) {
		$query = $this->db->query("SELECT * FROM @@order_download WHERE order_id = " . (int)$order_id . " ORDER BY name");

		return $query->rows;
	}

	public function getTotalOrders() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@order` WHERE customer_id = " . (int)$this->customer->getId() . " AND order_status_id > 0");
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@order_product WHERE order_id = " . (int)$order_id);
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@order_voucher` WHERE order_id = " . (int)$order_id);
	}

}
?>