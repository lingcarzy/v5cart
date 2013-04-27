<?php
class ModelDesignBanner extends Model {
	public function addBanner($data) {
		$banner_id = $this->db->insert('banner', $data);
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_data = array(
					'banner_id' => $banner_id,
					'link' => $banner_image['link'],
					'image' => $banner_image['image'],
				);
				
				$banner_image_id = $this->db->insert('banner_image', $banner_image_data);
				
				$banner_description_data = array();
				foreach ($banner_image['title'] as $language_id => $title) {
					$banner_description_data[] = array(
						'banner_id' => $banner_id,
						'banner_image_id' => $banner_image_id,
						'language_id' => $language_id,
						'title' => $title
					);
				}
				$this->db->insert('banner_image_description', $banner_description_data);
			}
		}
	}
	
	public function editBanner($banner_id, $data) {
		$this->db->update('banner', $data, "banner_id=$banner_id");
		
		$this->db->query("DELETE FROM  @@banner_image WHERE banner_id = " . (int)$banner_id);
		$this->db->query("DELETE FROM  @@banner_image_description WHERE banner_id = " . (int)$banner_id);
			
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$banner_image_data = array(
					'banner_id' => $banner_id,
					'link' => $banner_image['link'],
					'image' => $banner_image['image'],
				);
				
				$banner_image_id = $this->db->insert('banner_image', $banner_image_data);
				
				$banner_description_data = array();
				foreach ($banner_image['title'] as $language_id => $title) {
					$banner_description_data[] = array(
						'banner_id' => $banner_id,
						'banner_image_id' => $banner_image_id,
						'language_id' => $language_id,
						'title' => $title
					);
				}
				$this->db->insert('banner_image_description', $banner_description_data);
			}
		}	
	}
	
	public function deleteBanner($banner_id) {
		$this->db->query("DELETE FROM  @@banner WHERE banner_id = " . (int)$banner_id);
		$this->db->query("DELETE FROM  @@banner_image WHERE banner_id = " . (int)$banner_id);
		$this->db->query("DELETE FROM  @@banner_image_description WHERE banner_id = " . (int)$banner_id);
	}
	
	public function getBanner($banner_id) {
		return $this->db->queryOne("SELECT * FROM  @@banner WHERE banner_id = " . (int)$banner_id);
	}
		
	public function getBanners($data = array()) {
		$sql = "SELECT * FROM  @@banner";
		
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
		}
		
		if (isset($data['order']) && $data['order'] == 'DESC') {
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
		
	public function getBannerImages($banner_id) {
		$banner_image_data = array();
		
		$banner_image_query = $this->db->query("SELECT * FROM  @@banner_image WHERE banner_id = " . (int)$banner_id);
		
		foreach ($banner_image_query->rows as $banner_image) {
			 
			$title = $this->db->queryArray("SELECT language_id,title FROM  @@banner_image_description WHERE banner_image_id = " . $banner_image['banner_image_id'] . " AND banner_id = " . $banner_id, 'language_id', 'title');
		
			$banner_image_data[] = array(
				'title'  => $title,
				'link'   => $banner_image['link'],
				'image'  => $banner_image['image']	
			);
		}
		
		return $banner_image_data;
	}
		
	public function getTotalBanners() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@banner");
	}	
}
?>