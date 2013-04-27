<?php
class ModelCheckoutCoupon extends Model {
	public function getCoupon($code) {
		$status = true;

		$coupon = $this->db->queryOne("SELECT * FROM `@@coupon` WHERE code = '" . ES($code) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND status = 1");

		if ($coupon) {
			if ($coupon['total'] >= $this->cart->getSubTotal()) {
				$status = false;
			}

			$coupon_history_total = $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@coupon_history` ch WHERE ch.coupon_id = " . (int)$coupon['coupon_id']);

			if ($coupon['uses_total'] > 0 && ($coupon_history_total >= $coupon['uses_total'])) {
				$status = false;
			}

			if ($coupon['logged'] && !$this->customer->getId()) {
				$status = false;
			}

			if ($this->customer->getId()) {
				$coupon_history_total = $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@coupon_history` ch WHERE ch.coupon_id = " . (int)$coupon['coupon_id'] . " AND ch.customer_id = " . (int)$this->customer->getId());

				if ($coupon['uses_customer'] > 0 && ($coupon_history_total >= $coupon['uses_customer'])) {
					$status = false;
				}
			}

			// Products
			$coupon_product_data = $this->db->queryArray("SELECT product_id FROM `@@coupon_product` WHERE coupon_id = " . (int)$coupon['coupon_id']);

			// Categories
			$coupon_category_data = $this->db->queryArray("SELECT category_id FROM `@@coupon_category` WHERE coupon_id = " . (int)$coupon['coupon_id']);
			
			$product_data = array();
			
			if ($coupon_product_data || $coupon_category_data) {
				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$product_data[] = $product['product_id'];

						continue;
					}

					foreach ($coupon_category_data as $category_id) {
						$coupon_category_query = $this->db->query("SELECT COUNT(*) AS total FROM `@@product_to_category` WHERE `product_id` = " . (int)$product['product_id'] . " AND category_id = " . (int)$category_id);

						if ($coupon_category_query->row['total']) {
							$product_data[] = $product['product_id'];

							break;
						}						
					}
				}	

				if (!$product_data) {
					$status = false;
				}
			}
		} else {
			$status = false;
		}

		if ($status) {
			return array(
				'coupon_id'     => $coupon['coupon_id'],
				'code'          => $coupon['code'],
				'name'          => $coupon['name'],
				'type'          => $coupon['type'],
				'discount'      => $coupon['discount'],
				'shipping'      => $coupon['shipping'],
				'total'         => $coupon['total'],
				'product'       => $product_data,
				'date_start'    => $coupon['date_start'],
				'date_end'      => $coupon['date_end'],
				'uses_total'    => $coupon['uses_total'],
				'uses_customer' => $coupon['uses_customer'],
				'status'        => $coupon['status'],
				'date_added'    => $coupon['date_added']
			);
		}
		else return false;
	}

	public function redeem($coupon_id, $order_id, $customer_id, $amount) {
		$this->db->query("INSERT INTO `@@coupon_history` SET coupon_id = " . (int)$coupon_id . ", order_id = " . (int)$order_id . ", customer_id = " . (int)$customer_id . ", amount = " . (float)$amount . ", date_added = NOW()");
	}
}
?>
