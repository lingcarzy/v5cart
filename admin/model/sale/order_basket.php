<?php

class ModelSaleOrderBasket extends Model {
	public function getBaskets($data = array(), &$total) {
		$sql = "SELECT ob.* FROM  @@order_basket ob";
		
		if (!empty($data['order_id'])) {
			$sql .= " LEFT JOIN @@order o ON ob.basket_id=o.basket_id WHERE o.order_id={$data['order_id']}";
		}
		else {
			$sql .= " WHERE 1";
		}
		
		if (!empty($data['basket_id'])) {
			$sql .= " AND ob.basket_id={$data['basket_id']}";
		}
		
		$total = $this->db->queryOne(str_replace('ob.*', 'COUNT(*) as total', $sql));
		$sql .= " LIMIT {$data['start']}, {$data['limit']}";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function addBasket($data) {
		
		$basket_id = $this->db->queryOne("SELECT max(basket_id) FROM @@order_basket");
		if (!$basket_id) $basket_id = 1;
		else $basket_id++;
		
		$filter = '';
		if (!empty($data['start_date'])){
			$filter .= " AND DATE(date_added) >= '".ES(trim($data['start_date']))."'";
		}
		
		if (!empty($data['end_date'])){
			$filter .= " AND DATE(date_added) <= '".ES(trim($data['end_date']))."'";
		}
		
		if (!empty($data['order_status_id'])){
			$filter .= " AND order_status_id = '".(int) $data['order_status_id']."'";
		} else {
			$filter .= " AND order_status_id > 0 ";
		}
		
		$sql = "UPDATE `@@order` SET basket_id=$basket_id WHERE basket_id = 0 $filter";
		$this->db->query($sql);
			
		$sql = "SELECT count(DISTINCT model) AS skus, count(DISTINCT op.order_id) AS orders FROM `@@order` o,  @@order_product op WHERE o.basket_id = '$basket_id' AND o.order_id = op.order_id";
		$result = $this->db->queryOne($sql);
		
		$_data = array(
			'basket_id' => $basket_id,
			'total_orders' => $result['orders'],
			'order_rate' => 0,
			'total_skus' => $result['skus'],
			'date_created' => time(),
			'remark' => '',
			'status' => 0
		);
		$this->db->insert('order_basket', $_data);
	}
	
	function deleteOrder($basket_id, $order_id) {
		if (is_array($order_id)) $order_id = implode(',', $order_id);
		$this->db->runSql("UPDATE `@@order` SET basket_id = 0 WHERE basket_id= '$basket_id' AND order_id IN ($order_id)");
		
		$sql = "SELECT count(DISTINCT model) AS skus, count(DISTINCT op.order_id) AS orders FROM `@@order` o,  @@order_product op WHERE basket_id = '$basket_id' AND o.order_id = op.order_id";
		$result = $this->db->queryOne($sql);
		
		$this->db->runSql("UPDATE  @@order_basket SET total_orders='{$result['orders']}', total_skus='{$result['skus']}' WHERE basket_id = $basket_id");
	}
	
	function addOrder($basket_id, $order_id) {
		if (is_array($order_id)) $order_id = implode(',', $order_id);
		$this->db->runSql("UPDATE `@@order` SET basket_id = $basket_id WHERE basket_id= 0 AND order_id IN ($order_id)");
		
		$sql = "SELECT count(DISTINCT model) AS skus, count(DISTINCT op.order_id) AS orders FROM `@@order` o,  @@order_product op WHERE basket_id = '$basket_id' AND o.order_id = op.order_id";
		$result = $this->db->queryOne($sql);
		
		$this->db->runSql("UPDATE  @@order_basket SET total_orders='{$result['orders']}', total_skus='{$result['skus']}' WHERE basket_id = $basket_id");
	}
	
	function getSKUs($basket_id) {		
		$sql = "SELECT op.product_id, op.model, op.name, p.quantity as stock, p.image, p.price, p.cost, sum(op.quantity) as qty, p.sku, p.supplier_id FROM `@@order_product` op, `@@order` o, `@@product` p WHERE op.product_id = p.product_id AND op.order_id = o.order_id  AND o.basket_id = '$basket_id'";
		$sql .= " GROUP BY op.product_id ORDER BY supplier_id";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	function getBasket($basket_id) {
		return $this->db->queryOne("SELECT * FROM  @@order_basket WHERE basket_id = '$basket_id'");
	}
}