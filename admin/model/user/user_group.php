<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$this->db->query("INSERT INTO  @@user_group SET name = '" . ES($data['name']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "'");
	}

	public function editUserGroup($user_group_id, $data) {
		$this->db->query("UPDATE  @@user_group SET name = '" . ES($data['name']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "' WHERE user_group_id = " . (int)$user_group_id);
	}

	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM  @@user_group WHERE user_group_id = " . (int)$user_group_id);
	}

	public function addPermission($user_id, $type, $page) {
		$user_group_id = $this->db->queryOne("SELECT DISTINCT user_group_id FROM  @@user WHERE user_id = " . (int)$user_id);

		if ($user_group_id) {
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM  @@user_group WHERE user_group_id = $user_group_id");

			if ($user_group_query->num_rows) {
			
				$data = unserialize($user_group_query->row['permission']);
				if (!in_array($page, $data[$type])) {
					$data[$type][] = $page;
				}

				$this->db->query("UPDATE  @@user_group SET permission = '" . serialize($data) . "' WHERE user_group_id = $user_group_id");
			}
		}
	}

	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM  @@user_group WHERE user_group_id = " . (int)$user_group_id);

		$user_group = array(
			'name'       => $query->row['name'],
			'permission' => unserialize($query->row['permission'])
		);

		return $user_group;
	}

	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM  @@user_group";

		$sql .= " ORDER BY name";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalUserGroups() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@user_group");
	}
}
?>