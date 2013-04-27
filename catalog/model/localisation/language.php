<?php
class ModelLocalisationLanguage extends Model {
	public function getLanguage($language_id) {
		$query = $this->db->query("SELECT * FROM @@language WHERE language_id = " . (int)$language_id);
		
		return $query->row;	
	}
}
?>