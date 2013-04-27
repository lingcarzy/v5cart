<?php
class ModelLocalisationCurrency extends Model {
	public function addCurrency($data) {
		$data['date_modified'] = 'NOW()';
		$this->db->insert('currency', $data);

		if (C('config_currency_auto')) {
			$this->updateCurrencies(true);
		}
	}

	public function editCurrency($currency_id, $data) {
		$data['date_modified'] = 'NOW()';
		$this->db->update('currency', $data, array('currency_id' => $currency_id));
	}

	public function deleteCurrency($currency_id) {
		$this->db->query("DELETE FROM  @@currency WHERE currency_id = " . (int)$currency_id);
	}

	public function getCurrency($currency_id) {
		return $this->db->queryOne("SELECT DISTINCT * FROM  @@currency WHERE currency_id = " . (int)$currency_id);
	}

	public function getCurrencyByCode($currency) {
		return $this->db->queryOne("SELECT DISTINCT * FROM  @@currency WHERE code = '" . ES($currency) . "'");
	}

	public function getCurrencies($data = array()) {
		$sql = "SELECT * FROM  @@currency";

		$sort_data = array(
			'title',
			'code',
			'value',
			'date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY title";
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

	public function updateCurrencies($force = false) {
		if (extension_loaded('curl')) {
			$data = array();

			if ($force) {
				$query = $this->db->query("SELECT * FROM  @@currency WHERE code != '" . ES(C('config_currency')) . "'");
			} else {
				$query = $this->db->query("SELECT * FROM  @@currency WHERE code != '" . ES(C('config_currency')) . "' AND date_modified < '" .  ES(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");
			}

			foreach ($query->rows as $result) {
				$data[] = C('config_currency') . $result['code'] . '=X';
			}

			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$content = curl_exec($curl);

			curl_close($curl);

			$lines = explode("\n", trim($content));

			foreach ($lines as $line) {
				$currency = utf8_substr($line, 4, 3);
				$value = utf8_substr($line, 11, 6);

				if ((float)$value) {
					$this->db->query("UPDATE  @@currency SET value = '" . (float)$value . "', date_modified = '" .  ES(date('Y-m-d H:i:s')) . "' WHERE code = '" . ES($currency) . "'");
				}
			}

			$this->db->query("UPDATE  @@currency SET value = '1.00000', date_modified = '" .  ES(date('Y-m-d H:i:s')) . "' WHERE code = '" . ES(C('config_currency')) . "'");
		}
	}

	public function getTotalCurrencies() {
		return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@currency");
	}
}
?>