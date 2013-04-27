<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$data['date_added'] = 'NOW()';
		$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($data['password'])));
		
		$this->db->insert('user', $data);
	}

	public function editUser($user_id, $data) {
		if ($data['password']) {
			$data['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
			$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($data['password'])));
		}
		else unset($data['password']);
		$this->db->update('user', $data, array('user_id' => $user_id));
	}

	public function editPassword($user_id, $password) {
		$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$data = array(
			'salt' => $salt,
			'password' => sha1($salt . sha1($salt . sha1($password))),
			'code' => ''
		);
		$this->db->update('user', $data, array('user_id' => (int) $user_id));
	}

	public function editCode($email, $code) {
		$this->db->update('user', array('code' => $code), array('email' => $email));
	}

	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `@@user` WHERE user_id = " . (int)$user_id);
	}

	public function getUser($user_id) {
		return $this->db->get('user', array('user_id' => $user_id));
	}

	public function getUserByUsername($username) {
		return $this->db->get('user', array('username' => $username));
	}

	public function getUserByCode($code) {
		return $this->db->queryOne("SELECT * FROM `@@user` WHERE code = '" . ES($code) . "' AND code != ''");
	}

	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `@@user`";

		$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
		}

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

	public function getTotalUsers() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@user`");
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	return $this->db->queryOney("SELECT COUNT(*) AS total FROM `@@user` WHERE user_group_id = " . (int)$user_group_id);
	}

	public function getTotalUsersByEmail($email) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@user` WHERE email = '" . ES($email) . "'");
	}
}
?>