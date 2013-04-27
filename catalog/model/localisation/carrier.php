<?php

class ModelLocalisationCarrier extends Model {
	public function getCarriers() {
		$data = $this->cache->get('carrier');
		if (!$data) {			
			$query = $this->db->query("SELECT * FROM @@shipping_carrier");
			foreach ($query->rows as $r) {
				$data[$r['code']] = $r;
			}
			$this->cache->set('carrier', $data);
		}
		return $data;
	}
}