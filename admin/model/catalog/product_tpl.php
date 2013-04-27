<?php
class ModelCatalogProductTpl extends Model {
	public function getTemplates($offset = 0, $limit = 10, &$total, $where = '') {
		if ($where) $where = ' WHERE ' . $where;
		$total = $this->db->queryOne("SELECT count(*) FROM `@@product_template` $where");
		$query = $this->db->query("SELECT template_id, title, status FROM `@@product_template` $where ORDER BY title" . ($limit > 0 ? " LIMIT $offset, $limit" : ''));
		return $query->rows;
	}
	
	public function getTemplate($template_id) {
		return $this->db->get('product_template', array('template_id' => $template_id));
	}
	
	public function addTemplate($data) {
		return $this->db->insert('product_template', $data);
	}
	
	public function updateTemplate($template_id, $data) {
		return $this->db->update('product_template', $data, array('template_id' => $template_id));
	}
	
	public function deleteTemplate($template_ids) {
		if (is_array($template_ids))
			$template_ids = implode(',', $template_ids);
		return $this->db->runSql("DELETE FROM `@@product_template` WHERE template_id IN ($template_ids)");
	}
}
?>