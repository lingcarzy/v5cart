<?php
final class Tax {
	private $shipping_address;
	private $payment_address;
	private $store_address;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->db = $registry->get('db');
		$this->session = $registry->get('session');

		if (isset($this->session->data['shipping_country_id']) || isset($this->session->data['shipping_zone_id'])) {
			$this->setShippingAddress($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
		} elseif (C('config_tax_default') == 'shipping') {
			$this->setShippingAddress(C('config_country_id'), C('config_zone_id'));
		}

		if (isset($this->session->data['payment_country_id']) || isset($this->session->data['payment_zone_id'])) {
			$this->setPaymentAddress($this->session->data['payment_country_id'], $this->session->data['payment_zone_id']);
		} elseif (C('config_tax_default') == 'payment') {
			$this->setPaymentAddress(C('config_country_id'), C('config_zone_id'));
		}

		$this->setStoreAddress(C('config_country_id'), C('config_zone_id'));
  	}

	public function setShippingAddress($country_id, $zone_id) {
		$this->shipping_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function setPaymentAddress($country_id, $zone_id) {
		$this->payment_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function setStoreAddress($country_id, $zone_id) {
		$this->store_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

  	public function calculate($value, $tax_class_id, $calculate = true) {
		if ($tax_class_id && $calculate) {
			$amount = $this->getTax($value, $tax_class_id);

			return $value + $amount;
		} else {
      		return $value;
    	}
  	}

  	public function getTax($value, $tax_class_id) {
		$amount = 0;

		$tax_rates = $this->getRates($value, $tax_class_id);

		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}

		return $amount;
  	}

	public function getRateName($tax_rate_id) {
		$tax_query = $this->db->query("SELECT name FROM @@tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		if ($tax_query->num_rows) {
			return $tax_query->row['name'];
		} else {
			return false;
		}
	}

    public function getRates($value, $tax_class_id) {
		$tax_rates = array();
		
		$_geo_zones = C('cache_geo_zone');
		$_tax_rates = C('cache_tax_rate');
		
		if (!isset($_tax_rates[$tax_class_id])) return array();
		
		$_tax_rates = $_tax_rates[$tax_class_id];
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = C('config_customer_group_id');
		}
		
		if ($this->shipping_address && isset($_tax_rates['shipping'])) {
			$country_id = $this->shipping_address['country_id'];
			$zone_id = $this->shipping_address['zone_id'];
			foreach ($_tax_rates['shipping'] as $_tax_rate_id => $_tax_rate) {
				$_geo = array();
				if (isset($_geo_zones[$country_id])) {
					if (isset($_geo_zones[$country_id][0])) $_geo = $_geo_zones[$country_id][0];
					elseif (isset($_geo_zones[$country_id][$zone_id])) $_geo = $_geo_zones[$country_id][$zone_id];
				}
				if (empty($_geo)) continue;
				if (strpos($_tax_rate['cg'], ",{$customer_group_id},") !== FALSE
					&& in_array($_tax_rate['gzid'], $_geo)) {
					$tax_rates[$_tax_rate_id] = $_tax_rate;
				}
			}
		}
		if ($this->payment_address && isset($_tax_rates['payment'])) {
			$country_id = $this->payment_address['country_id'];
			$zone_id = $this->payment_address['zone_id'];
			foreach ($_tax_rates['payment'] as $_tax_rate_id => $_tax_rate) {
				$_geo = array();
				if (isset($_geo_zones[$country_id])) {
					if (isset($_geo_zones[$country_id][0])) $_geo = $_geo_zones[$country_id][0];
					elseif (isset($_geo_zones[$country_id][$zone_id])) $_geo = $_geo_zones[$country_id][$zone_id];
				}
				if (empty($_geo)) continue;
				if (strpos($_tax_rate['cg'], ",{$customer_group_id},") !== FALSE
					&& in_array($_tax_rate['gzid'], $_geo)) {
					$tax_rates[$_tax_rate_id] = $_tax_rate;
				}
			}
		}
		if ($this->store_address && isset($_tax_rates['store'])) {
			$country_id = $this->store_address['country_id'];
			$zone_id = $this->store_address['zone_id'];
			foreach ($_tax_rates['store'] as $_tax_rate_id => $_tax_rate) {
				$_geo = array();
				if (isset($_geo_zones[$country_id])) {
					if (isset($_geo_zones[$country_id][0])) $_geo = $_geo_zones[$country_id][0];
					elseif (isset($_geo_zones[$country_id][$zone_id])) $_geo = $_geo_zones[$country_id][$zone_id];
				}
				if (empty($_geo)) continue;
				if (strpos($_tax_rate['cg'], ",{$customer_group_id},") !== FALSE
					&& in_array($_tax_rate['gzid'], $_geo)) {
					$tax_rates[$_tax_rate_id] = $_tax_rate;
				}
			}
		}
		
		$tax_rate_data = array();
		foreach ($tax_rates as $tax_rate) {
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}

			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($value / 100 * $tax_rate['rate']);
			}

			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				//'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}

		return $tax_rate_data;
	}

  	public function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
  	}
}
?>