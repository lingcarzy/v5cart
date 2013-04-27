<?php
class ModelSaleOrder extends Model {
	public function addOrder($data) {
		M('setting/store');

		$store_info = $this->model_setting_store->getStore($data['store_id']);

		if ($store_info) {
			$data['store_name'] = $store_info['name'];
			$data['store_url'] = $store_info['url'];
		} else {
			$data['store_name'] = C('config_name');
			$data['store_url'] = HTTP_CATALOG;
		}

		M('setting/setting');

		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);

		if (isset($setting_info['invoice_prefix'])) {
			$data['invoice_prefix'] = $setting_info['invoice_prefix'];
		} else {
			$data['invoice_prefix'] = C('config_invoice_prefix');
		}

		M('localisation/country');
		M('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

		if ($country_info) {
			$data['shipping_country'] = $country_info['name'];
			$data['shipping_address_format'] = $country_info['address_format'];
		} else {
			$data['shipping_country'] = '';
			$data['shipping_address_format'] = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);

		if ($zone_info) {
			$data['shipping_zone'] = $zone_info['name'];
		} else {
			$data['shipping_zone'] = '';
		}

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);

		if ($country_info) {
			$data['payment_country'] = $country_info['name'];
			$data['payment_address_format'] = $country_info['address_format'];
		} else {
			$data['payment_country'] = '';
			$data['payment_address_format'] = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);

		if ($zone_info) {
			$data['payment_zone'] = $zone_info['name'];
		} else {
			$data['payment_zone'] = '';
		}

		M('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode(C('config_currency'));

		if ($currency_info) {
			$data['currency_id'] = $currency_info['currency_id'];
			$data['currency_code'] = $currency_info['code'];
			$data['currency_value'] = $currency_info['value'];
		} else {
			$data['currency_id'] = 0;
			$data['currency_code'] = C('config_currency');
			$data['currency_value'] = 1.00000;
		}

		$data['date_added'] = 'NOW()';
		$data['date_modified'] = 'NOW()';
      	$order_id = $this->db->insert('order', $data);

      	if (isset($data['order_product'])) {
      		foreach ($data['order_product'] as $order_product) {
				$order_product['order_id'] = $order_id;

				$order_product_id = $this->db->inset('order_product', $order_product);

				$this->db->query("UPDATE  @@product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = " . (int)$order_product['product_id'] . " AND subtract = 1");

				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$order_option['order_id'] = $order_id;
						$order_option['order_product_id'] = $order_product_id;

						$this->db->insert('order_option', $order_option);

						$this->db->query("UPDATE  @@product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = " . (int)$order_option['product_option_value_id'] . " AND subtract = 1");
					}
				}

				if (isset($order_product['order_download'])) {
					foreach ($order_product['order_download'] as $order_download) {
						$order_download['order_id'] = $order_id;
						$order_download['order_product_id'] = $order_product_id;

						$this->db->insert('order_download', $order_download);
					}
				}
			}
		}

		if (isset($data['order_voucher'])) {
			foreach ($data['order_voucher'] as $order_voucher) {
				$order_voucher['order_id'] = $order_id;

				$this->db->insert('order_voucher', $order_voucher);
      			$this->db->query("UPDATE  @@voucher SET order_id = " . (int)$order_id . " WHERE voucher_id = " . (int)$order_voucher['voucher_id']);
			}
		}

		// Get the total
		$total = 0;

		if (isset($data['order_total'])) {
      		foreach ($data['order_total'] as $order_total) {
				$order_total['order_id'] = $order_id;

				$this->db->insert('order_total', $order_total);
			}
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			M('sale/affiliate');

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);

			if ($affiliate_info) {
				$affiliate_id = $affiliate_info['affiliate_id'];
				$commission = ($total / 100) * $affiliate_info['commission'];
			}
		}

		// Update order total
		$this->db->query("UPDATE `@@order` SET total = " . (float)$total . ", affiliate_id = " . (int)$affiliate_id . ", commission = " . (float)$commission . " WHERE order_id = " . (int)$order_id);
	}

	public function editOrder($order_id, $data) {
		M('setting/store');
		$store_info = $this->model_setting_store->getStore($data['store_id']);

		if ($store_info) {
			$data['store_name'] = $store_info['name'];
			$data['store_url'] = $store_info['url'];
		} else {
			$data['store_name'] = C('config_name');
			$data['store_url'] = HTTP_CATALOG;
		}

		M('localisation/country');

		M('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

		if ($country_info) {
			$data['shipping_country'] = $country_info['name'];
			$data['shipping_address_format'] = $country_info['address_format'];
		} else {
			$data['shipping_country'] = '';
			$data['shipping_address_format'] = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);

		if ($zone_info) {
			$data['shipping_zone'] = $zone_info['name'];
		} else {
			$data['shipping_zone'] = '';
		}

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);

		if ($country_info) {
			$data['payment_country'] = $country_info['name'];
			$data['payment_address_format'] = $country_info['address_format'];
		} else {
			$data['payment_country'] = '';
			$data['payment_address_format'] = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);

		if ($zone_info) {
			$data['payment_zone'] = $zone_info['name'];
		} else {
			$data['payment_zone'] = '';
		}

		$data['date_modified'] = 'NOW()';

		// Restock products before subtracting the stock later on
		$order_query = $this->db->query("SELECT * FROM `@@order` WHERE order_status_id > 0 AND order_id = " . (int)$order_id);

		if ($order_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM  @@order_product WHERE order_id = " . (int)$order_id);

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `@@product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = " . (int)$product['product_id'] . " AND subtract = 1");

				$option_query = $this->db->query("SELECT * FROM  @@order_option WHERE order_id = " . (int)$order_id . " AND order_product_id = " . (int)$product['order_product_id']);

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE  @@product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = " . (int)$option['product_option_value_id'] . " AND subtract = 1");
				}
			}
		}
		$this->db->update('order', $data, array('order_id' => $order_id));

		$this->db->query("DELETE FROM  @@order_product WHERE order_id = " . (int)$order_id);
       	$this->db->query("DELETE FROM  @@order_option WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_download WHERE order_id = " . (int)$order_id);

      	if (isset($data['order_product'])) {
      		foreach ($data['order_product'] as $order_product) {
				$order_product['order_id'] = $order_id;
				$order_product_id = $this->db->inset('order_product', $order_product);

				$this->db->query("UPDATE  @@product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = " . (int)$order_product['product_id'] . " AND subtract = 1");

				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$order_option['order_id'] = $order_id;
						$order_option['order_product_id'] = $order_product_id;
						$this->db->insert('order_option', $order_option);

						$this->db->query("UPDATE  @@product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = " . (int)$order_option['product_option_value_id'] . " AND subtract = 1");
					}
				}

				if (isset($order_product['order_download'])) {
					foreach ($order_product['order_download'] as $order_download) {
						$order_download['order_id'] = $order_id;
						$order_download['order_product_id'] = $order_product_id;
						$this->db->insert('order_download', $order_download);
					}
				}
			}
		}

		$this->db->query("DELETE FROM  @@order_voucher WHERE order_id = " . (int)$order_id);

		if (isset($data['order_voucher'])) {
			foreach ($data['order_voucher'] as $order_voucher) {
				$order_voucher['order_id'] = $order_id;
				$this->db->insert('order_voucher', $order_voucher);

				$this->db->query("UPDATE  @@voucher SET order_id = " . (int)$order_id . " WHERE voucher_id = " . (int)$order_voucher['voucher_id']);
			}
		}

		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM  @@order_total WHERE order_id = " . (int)$order_id);

		if (isset($data['order_total'])) {
      		foreach ($data['order_total'] as $order_total) {
				$order_total['order_id'] = $order_id;
				$this->db->inset('order_total', $order_total);
			}
			$total += $order_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			M('sale/affiliate');

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);

			if ($affiliate_info) {
				$affiliate_id = $affiliate_info['affiliate_id'];
				$commission = ($total / 100) * $affiliate_info['commission'];
			}
		}

		$this->db->query("UPDATE `@@order` SET total = " . (float)$total . ", affiliate_id = " . (int)$affiliate_id . ", commission = " . (float)$commission . " WHERE order_id = " . (int)$order_id);
	}

	public function deleteOrder($order_id) {
		$total = $this->db->queryOne("SELECT COUNT(*) as total FROM `@@order` WHERE order_status_id > 0 AND order_id = " . (int)$order_id);

		if ($total) {
			$product_query = $this->db->query("SELECT * FROM  @@order_product WHERE order_id = " . (int)$order_id);

			foreach($product_query->rows as $product) {
				$this->db->query("UPDATE `@@product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = " . (int)$product['product_id'] . " AND subtract = 1");

				$option_query = $this->db->query("SELECT * FROM  @@order_option WHERE order_id = " . (int)$order_id . " AND order_product_id = " . (int)$product['order_product_id']);

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE  @@product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = " . (int)$option['product_option_value_id'] . " AND subtract = 1");
				}
			}
		}

		$this->db->query("DELETE FROM `@@order` WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_product WHERE order_id = " . (int)$order_id);
      	$this->db->query("DELETE FROM  @@order_option WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_download WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_voucher WHERE order_id = " . (int)$order_id);
      	$this->db->query("DELETE FROM  @@order_total WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_history WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_shipping_history WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@order_fraud WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@customer_transaction WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@customer_reward WHERE order_id = " . (int)$order_id);
		$this->db->query("DELETE FROM  @@affiliate_transaction WHERE order_id = " . (int)$order_id);
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM  @@customer c WHERE c.customer_id = o.customer_id) AS customer FROM `@@order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM  @@order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}

			$country_query = $this->db->query("SELECT * FROM `@@country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `@@zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `@@country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `@@zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			M('sale/affiliate');

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			M('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_company_id'      => $order_query->row['payment_company_id'],
				'payment_tax_id'          => $order_query->row['payment_tax_id'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_transaction_id'          => $order_query->row['payment_transaction_id'],
				'payment_type'          => $order_query->row['payment_type'],
				'payment_fee_amt'          => $order_query->row['payment_fee_amt'],
				'payment_status'          => $order_query->row['payment_status'],
				'payment_payer_status'          => $order_query->row['payment_payer_status'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'remark'                  => $order_query->row['remark'],
			);
		} else {
			return false;
		}
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id,o.payment_method,o.shipping_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.email, o.shipping_city, o.shipping_country, o.invoice_no,o.invoice_prefix, (SELECT os.name FROM  @@order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = " . (int)C('config_language_id') . ") AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `@@order` o";

		if (!empty($data['filter_model'])) {
			$sql .= " LEFT JOIN @@order_product op ON o.order_id=op.order_id";
		}

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = " . (int)$data['filter_order_status_id'];
		} else {
			$sql .= " WHERE o.order_status_id > 0";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . ES(utf8_strtolower($data['filter_customer'])) . "%'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . ES($data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . ES($data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = " . (float)$data['filter_total'];
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND op.model='" . ES($data['filter_model']) . "'";
		}

		if (isset($data['filter_basket_id'])) {
			$sql .= " AND o.basket_id = " . $data['filter_basket_id'];
		}
		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT op.*, p.image FROM  @@order_product op LEFT JOIN @@product p ON (op.product_id=p.product_id) WHERE order_id = " . (int)$order_id);

		return $query->rows;
	}

	public function getOrderShippingHistory($order_id) {
		$query = $this->db->query("SELECT * FROM  @@order_shipping_history WHERE order_id = " . (int)$order_id);
		foreach ($query->rows as $i => $row) {
			$query->rows[$i]['products'] = unserialize($row['products']);
		}
		return $query->rows;
	}

	public function addShippingHistory($order_id, $data) {
		foreach ($data['products'] as $product_id => $qty) {
			$this->db->runSql("UPDATE @@order_product SET shipped_qty=shipped_qty+$qty WHERE order_id = $order_id AND product_id = $product_id");
		}
		$data['order_id'] = $order_id;
		$data['products'] = serialize($data['products']);
		$this->db->insert('order_shipping_history', $data);
	}

	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM  @@order_option WHERE order_id = " . (int)$order_id . " AND order_option_id = " . (int)$order_option_id);

		return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM  @@order_option WHERE order_id = " . (int)$order_id . " AND order_product_id = " . (int)$order_product_id);

		return $query->rows;
	}

	public function getOrderDownloads($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM  @@order_download WHERE order_id = " . (int)$order_id . " AND order_product_id = " . (int)$order_product_id);

		return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM  @@order_voucher WHERE order_id = " . (int)$order_id);

		return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
      	return $this->db->queryOne("SELECT * FROM `@@order_voucher` WHERE voucher_id = " . (int)$voucher_id);
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM  @@order_total WHERE order_id = " . (int)$order_id . " ORDER BY sort_order");

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `@@order` o";

		if (!empty($data['filter_model'])) {
			$sql .= " LEFT JOIN @@order_product op ON o.order_id=op.order_id";
		}

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = " . (int)$data['filter_order_status_id'];
		} else {
			$sql .= " WHERE o.order_status_id > 0";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . ES($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . ES($data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . ES($data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = " . (float)$data['filter_total'];
		}

		if (isset($data['filter_basket_id'])) {
			$sql .= " AND o.basket_id=" . $data['filter_basket_id'];
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND op.model='" . ES($data['filter_model']) . "'";
		}

		return $this->db->queryOne($sql);
	}

	public function getTotalOrdersByStoreId($store_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@order` WHERE store_id = " . (int)$store_id);
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@order` WHERE order_status_id = " . (int)$order_status_id . " AND order_status_id > 0");
	}

	public function getTotalOrdersByLanguageId($language_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@order` WHERE language_id = " . (int)$language_id . " AND order_status_id > 0");
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM `@@order` WHERE currency_id = " . (int)$currency_id . " AND order_status_id > 0");
	}

	public function getTotalSales() {
      	return $this->db->queryOne("SELECT SUM(total) AS total FROM `@@order` WHERE order_status_id > 0");
	}

	public function getTotalSalesByYear($year) {
      	return $this->db->queryOne("SELECT SUM(total) AS total FROM `@@order` WHERE order_status_id > 0 AND YEAR(date_added) = '" . (int)$year . "'");
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `@@order` WHERE invoice_prefix = '" . ES($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `@@order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . ES($order_info['invoice_prefix']) . "' WHERE order_id = " . (int)$order_id);

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function addOrderHistory($order_id, $data) {
		$this->db->query("UPDATE `@@order` SET order_status_id = " . (int)$data['order_status_id'] . ", date_modified = NOW() WHERE order_id = " . (int)$order_id);

		$this->db->query("INSERT INTO  @@order_history SET order_id = " . (int)$order_id . ", order_status_id = " . (int)$data['order_status_id'] . ", notify = " . (isset($data['notify']) ? (int)$data['notify'] : 0) . ", comment = '" . ES(strip_tags($data['comment'])) . "', date_added = NOW()");

		$order_info = $this->getOrder($order_id);

		// Send out any gift voucher mails
		if (C('config_complete_status_id') == $data['order_status_id']) {
			M('sale/voucher');

			$results = $this->getOrderVouchers($order_id);

			foreach ($results as $result) {
				$this->model_sale_voucher->sendVoucher($result['voucher_id']);
			}
		}

      	if ($data['notify']) {
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);

			$message  = $language->get('text_order') . ' ' . $order_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

			$order_status_query = $this->db->query("SELECT * FROM  @@order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$message .= $language->get('text_order_status') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}

			if ($order_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}

			if ($data['comment']) {
				$message .= $language->get('text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $language->get('text_footer');

			$mail = new Mail();
			$mail->protocol = C('config_mail_protocol');
			$mail->parameter = C('config_mail_parameter');
			$mail->hostname = C('config_smtp_host');
			$mail->username = C('config_smtp_username');
			$mail->password = C('config_smtp_password');
			$mail->port = C('config_smtp_port');
			$mail->timeout = C('config_smtp_timeout');
			$mail->setTo($order_info['email']);
			$mail->setFrom(C('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 10;

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM  @@order_history oh LEFT JOIN  @@order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = " . (int)$order_id . " AND os.language_id = " . (int)C('config_language_id') . " ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@order_history WHERE order_id = " . (int)$order_id);
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@order_history WHERE order_status_id = " . (int)$order_status_id);
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . $product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `@@order` o LEFT JOIN  @@order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> 0");

		return $query->rows;
	}

	public function getTotalEmailsByProductsOrdered($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . $product_id . "'";
		}

		return $this->db->queryOne("SELECT COUNT(DISTINCT email) AS total FROM `@@order` o LEFT JOIN  @@order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> 0");
	}

	public function getOrdersByBasketId($basket_id) {
		$query = $this->db->query("SELECT order_id FROM @@order WHERE basket_id=$basket_id");
		return $query->rows;
	}
}
?>