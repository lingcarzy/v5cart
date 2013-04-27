<?php
class ModelCatalogReview extends Model {
	public function addReview($data) {
		$data['text'] = strip_tags($data['text']);
		$data['date_added'] = 'NOW()';
		
		$this->db->insert('review', $data);
		
		$this->_updateRating($data['product_id']);
	}

	public function editReview($review_id, $data) {
		$data['text'] = strip_tags($data['text']);
		$this->db->update('review', $data, array('review_id' => $review_id));

		$this->_updateRating($data['product_id']);
	}

	public function deleteReview($review_id) {
		$this->db->delete('review', array('review_id' => $review_id));

		$this->_updateRating($data['product_id']);
	}

	public function getReview($review_id) {
		return $this->db->queryOne("SELECT DISTINCT *, (SELECT pd.name FROM @@product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = " . (int)C('config_language_id') . ") AS product FROM @@review r WHERE r.review_id = " . (int)$review_id);
	}

	public function getReviews($data = array()) {
		$sql = "SELECT r.review_id, pd.name, r.author, r.rating, r.status, r.date_added FROM @@review r LEFT JOIN @@product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = " . (int)C('config_language_id');

		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
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

	public function getTotalReviews() {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@review");
	}

	public function getTotalReviewsAwaitingApproval() {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@review WHERE status = 0");
	}

	public function _updateRating($product_id) {
		$total = $rating = 0;
		$row = $this->db->queryOne("SELECT count(*) as total, ROUND(AVG(rating),1) AS rating FROM @@review WHERE status = 1 AND product_id = " . (int)$product_id . " GROUP BY product_id");
		if ($row) {
			$total = $row['total'];
			$rating = $row['rating'];
		}
		$this->db->runSql("UPDATE @@product SET reviews=$total, rating=$rating WHERE product_id=$product_id");
	}
}
?>