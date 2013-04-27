<?php
class ModelToolOnline extends Model {	
	public function whosonline($ip, $customer_id, $url, $referer) {
		$this->db->query("DELETE FROM `@@customer_online` WHERE (UNIX_TIMESTAMP(`date_added`) + 3600) < UNIX_TIMESTAMP(NOW())");
		 
		$this->db->query("REPLACE INTO `@@customer_online` SET `ip` = '" . ES($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . ES($url) . "', `referer` = '" . ES($referer) . "', `date_added` = NOW()");
	}
}
?>