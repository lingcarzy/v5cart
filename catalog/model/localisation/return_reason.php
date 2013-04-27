<?php
class ModelLocalisationReturnReason extends Model {

	public function getReturnReasons() {
      
		$return_reason_data = $this->cache->get('return_reason.' . (int)C('config_language_id'));

		if (!$return_reason_data) {
			$query = $this->db->query("SELECT return_reason_id, name FROM @@return_reason WHERE language_id = " . (int)C('config_language_id') . " ORDER BY name");

			$return_reason_data = $query->rows;

			$this->cache->set('return_reason.' . (int)C('config_language_id'), $return_reason_data);
		}

		return $return_reason_data;
	}
}
?>