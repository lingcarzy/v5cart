<?php

class ModelLocalisationCarrier extends Model {
	public function addCarrier($data) {
		$this->db->insert('shipping_carrier', $data);
		$this->cache->delete('carrier');
	}
	
	public function editCarrier($carrier_id, $data) {
		$this->db->update('shipping_carrier', $data, "carrier_id=$carrier_id");
		$this->cache->delete('carrier');
	}
	
	public function getCarrier($carrier_id) {
		return $this->db->queryOne("SELECT * FROM @@shipping_carrier WHERE carrier_id=$carrier_id");
	}
	
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
	
	public function deleteCarrier($carrier_id) {
		if (!is_array($carrier_id)) {
			$carrier_id = array($carrier_id);
		}
		$carrier_ids = implode(',', $carrier_id);
		$this->db->runSql("DELETE FROM @@shipping_carrier WHERE carrier_id IN ($carrier_ids)");
		$this->cache->delete('carrier');
	}
}