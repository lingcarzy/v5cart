<?php
class ModelAccountDownload extends Model {
	public function getDownload($order_download_id) {
		return $this->db->queryOne("SELECT * FROM @@order_download od LEFT JOIN `@@order` o ON (od.order_id = o.order_id) WHERE o.customer_id = " . (int)$this->customer->getId(). " AND o.order_status_id = " . (int)C('config_complete_status_id') . " AND od.order_download_id = " . (int)$order_download_id . " AND od.remaining > 0");
	}

	public function getDownloads($start = 0, $limit = 20) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 20;

		$query = $this->db->query("SELECT o.order_id, o.date_added, od.order_download_id, od.name, od.filename, od.remaining FROM @@order_download od LEFT JOIN `@@order` o ON (od.order_id = o.order_id) WHERE o.customer_id = " . (int)$this->customer->getId() . " AND o.order_status_id > 0 AND o.order_status_id = " . (int)C('config_complete_status_id') . " AND od.remaining > 0 ORDER BY o.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function updateRemaining($order_download_id) {
		$this->db->query("UPDATE @@order_download SET remaining = (remaining - 1) WHERE remaining > 0 AND order_download_id = " . (int)$order_download_id);
	}

	public function getTotalDownloads() {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@order_download od LEFT JOIN `@@order` o ON (od.order_id = o.order_id) WHERE o.customer_id = " . (int)$this->customer->getId() . " AND o.order_status_id > 0 AND o.order_status_id = " . (int)C('config_complete_status_id') . " AND od.remaining > 0");
	}
}
?>
