<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
		$data['customer_id'] = $this->customer->getId();
		$address_id = $this->db->insert('address', $data);

		//set as default address
		if (!empty($data['default'])) {
			$this->db->query("UPDATE @@customer SET address_id = " . (int)$address_id . " WHERE customer_id = " . (int)$this->customer->getId());
		}

		return $address_id;
	}

	public function editAddress($address_id, $data) {
		unset($data['customer_id']);
		
		$this->db->update('address', $data, 
					array(
						'address_id' => $address_id,
						'customer_id' => $this->customer->getId()
					)
		);

		//set as default address
		if (!empty($data['default'])) {
			$this->db->query("UPDATE @@customer SET address_id = " . (int)$address_id . " WHERE customer_id = " . (int)$this->customer->getId());
		}
	}

	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM @@address WHERE address_id = " . (int)$address_id . " AND customer_id = " . (int)$this->customer->getId());
	}

	public function getAddress($address_id) {
		$address = $this->db->queryOne("SELECT DISTINCT * FROM @@address WHERE address_id = " . (int)$address_id . " AND customer_id = " . (int)$this->customer->getId());

		if (!$address) return false;
		
		$country = $this->db->queryOne("SELECT * FROM `@@country` WHERE country_id = " . (int)$address['country_id']);

		if ($country) {
			$address['country']= $country['name'];
			$address['iso_code_2'] = $country['iso_code_2'];
			$address['iso_code_3'] = $country['iso_code_3'];
			$address['address_format'] = $country['address_format'];
		} else {
			$address['country'] = '';
			$address['iso_code_2'] = '';
			$address['iso_code_3'] = '';
			$address['address_format'] = '';
		}

		$zone = $this->db->queryOne("SELECT * FROM `@@zone` WHERE zone_id = " . (int)$address['zone_id']);

		if ($zone) {
			$address['zone'] = $zone['name'];
			$address['zone_code'] = $zone['code'];
		} else {
			$address['zone'] = '';
			$address['zone_code'] = '';
		}
		return $address;
	}

	public function getAddresses() {
		$address_data = array();

		$query = $this->db->query("SELECT * FROM @@address WHERE customer_id = " . (int)$this->customer->getId());

		foreach ($query->rows as $address) {
			$country = $this->db->queryOne("SELECT * FROM `@@country` WHERE country_id = " . (int)$address['country_id']);

			if ($country) {
				$address['country']= $country['name'];
				$address['iso_code_2'] = $country['iso_code_2'];
				$address['iso_code_3'] = $country['iso_code_3'];
				$address['address_format'] = $country['address_format'];
			} else {
				$address['country'] = '';
				$address['iso_code_2'] = '';
				$address['iso_code_3'] = '';
				$address['address_format'] = '';
			}

			$zone = $this->db->queryOne("SELECT * FROM `@@zone` WHERE zone_id = " . (int)$address['zone_id']);

			if ($zone) {
				$address['zone'] = $zone['name'];
				$address['zone_code'] = $zone['code'];
			} else {
				$address['zone'] = '';
				$address['zone_code'] = '';
			}

			$address_data[$address['address_id']] = $address;
		}

		return $address_data;
	}

	public function getTotalAddresses() {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM @@address WHERE customer_id = " . (int)$this->customer->getId());
	}
}
?>