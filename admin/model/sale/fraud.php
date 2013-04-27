<?php
class ModelSaleFraud extends Model {
	public function getFraud($order_id) {
		return $this->db->queryOne("SELECT * FROM `@@order_fraud` WHERE order_id = " . (int)$order_id);
	}
}
?>