<?php
class ModelSaleVoucher extends Model {
	public function addVoucher($data) {
		$this->db->insert('voucher', $data);
	}

	public function editVoucher($voucher_id, $data) {
		$this->db->update('voucher', $data, array('voucher_id' => $voucher_id));
	}

	public function deleteVoucher($voucher_id) {
      	$this->db->query("DELETE FROM  @@voucher WHERE voucher_id = " . (int)$voucher_id);
		$this->db->query("DELETE FROM  @@voucher_history WHERE voucher_id = " . (int)$voucher_id);
	}

	public function getVoucher($voucher_id) {
      	return $this->db->queryOne("SELECT DISTINCT * FROM  @@voucher WHERE voucher_id = " . (int)$voucher_id);
	}

	public function getVoucherByCode($code) {
      	return $this->db->queryOne("SELECT DISTINCT * FROM  @@voucher WHERE code = '" . ES($code) . "'");
	}

	public function getVouchers($data = array()) {
		$sql = "SELECT v.voucher_id, v.code, v.from_name, v.from_email, v.to_name, v.to_email, (SELECT vtd.name FROM  @@voucher_theme_description vtd WHERE vtd.voucher_theme_id = v.voucher_theme_id AND vtd.language_id = " . (int)C('config_language_id') . ") AS theme, v.amount, v.status, v.date_added FROM  @@voucher v";

		$sort_data = array(
			'v.code',
			'v.from_name',
			'v.from_email',
			'v.to_name',
			'v.to_email',
			'v.theme',
			'v.amount',
			'v.status',
			'v.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY v.date_added";
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

	public function sendVoucher($voucher_id) {
		$voucher_info = $this->getVoucher($voucher_id);

		if ($voucher_info) {
			if ($voucher_info['order_id']) {
				$order_id = $voucher_info['order_id'];
			} else {
				$order_id = 0;
			}

			M('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			// If voucher belongs to an order
			if ($order_info) {
				M('localisation/language');

				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_filename']);
				$language->load('mail/voucher');

				// HTML Mail
				$template = new Template();

				$template->data['title'] = sprintf($language->get('text_subject'), $voucher_info['from_name']);

				$template->data['text_greeting'] = sprintf($language->get('text_greeting'), $this->currency->format($voucher_info['amount'], $order_info['currency_code'], $order_info['currency_value']));
				$template->data['text_from'] = sprintf($language->get('text_from'), $voucher_info['from_name']);
				$template->data['text_message'] = $language->get('text_message');
				$template->data['text_redeem'] = sprintf($language->get('text_redeem'), $voucher_info['code']);
				$template->data['text_footer'] = $language->get('text_footer');

				M('sale/voucher_theme');

				$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

				if ($voucher_info && file_exists(DIR_IMAGE . $voucher_theme_info['image'])) {
					$template->data['image'] = HTTP_IMAGE . $voucher_theme_info['image'];
				} else {
					$template->data['image'] = '';
				}

				$template->data['store_name'] = $order_info['store_name'];
				$template->data['store_url'] = $order_info['store_url'];
				$template->data['message'] = nl2br($voucher_info['message']);

				$mail = new Mail();
				$mail->protocol = C('config_mail_protocol');
				$mail->parameter = C('config_mail_parameter');
				$mail->hostname = C('config_smtp_host');
				$mail->username = C('config_smtp_username');
				$mail->password = C('config_smtp_password');
				$mail->port = C('config_smtp_port');
				$mail->timeout = C('config_smtp_timeout');
				$mail->setTo($voucher_info['to_email']);
				$mail->setFrom(C('config_email'));
				$mail->setSender($order_info['store_name']);
				$mail->setSubject(html_entity_decode(sprintf($language->get('text_subject'), $voucher_info['from_name']), ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($template->fetch('mail/voucher.tpl'));
				$mail->send();

			// If voucher does not belong to an order
			}  else {
				$this->language->load('mail/voucher');

				$template = new Template();

				$template->data['title'] = sprintf(L('text_subject'), $voucher_info['from_name']);

				$template->data['text_greeting'] = sprintf(L('text_greeting'), $this->currency->format($voucher_info['amount'], $order_info['currency_code'], $order_info['currency_value']));
				$template->data['text_from'] = sprintf(L('text_from'), $voucher_info['from_name']);
				$template->data['text_message'] = L('text_message');
				$template->data['text_redeem'] = sprintf(L('text_redeem'), $voucher_info['code']);
				$template->data['text_footer'] = L('text_footer');

				M('sale/voucher_theme');

				$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

				if ($voucher_info && file_exists(DIR_IMAGE . $voucher_theme_info['image'])) {
					$template->data['image'] = HTTP_IMAGE . $voucher_theme_info['image'];
				} else {
					$template->data['image'] = '';
				}

				$template->data['store_name'] = C('config_name');
				$template->data['store_url'] = HTTP_CATALOG;
				$template->data['message'] = nl2br($voucher_info['message']);

				$mail = new Mail();
				$mail->protocol = C('config_mail_protocol');
				$mail->parameter = C('config_mail_parameter');
				$mail->hostname = C('config_smtp_host');
				$mail->username = C('config_smtp_username');
				$mail->password = C('config_smtp_password');
				$mail->port = C('config_smtp_port');
				$mail->timeout = C('config_smtp_timeout');
				$mail->setTo($voucher_info['to_email']);
				$mail->setFrom(C('config_email'));
				$mail->setSender(C('config_name'));
				$mail->setSubject(html_entity_decode(sprintf(L('text_subject'), $voucher_info['from_name']), ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($template->fetch('mail/voucher.tpl'));
				$mail->send();
			}
		}
	}

	public function getTotalVouchers() {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@voucher");
	}

	public function getTotalVouchersByVoucherThemeId($voucher_theme_id) {
      	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@voucher WHERE voucher_theme_id = " . (int)$voucher_theme_id);
	}

	public function getVoucherHistories($voucher_id, $start = 0, $limit = 10) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 10;

		$query = $this->db->query("SELECT vh.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, vh.amount, vh.date_added FROM  @@voucher_history vh LEFT JOIN `@@order` o ON (vh.order_id = o.order_id) WHERE vh.voucher_id = '" . (int)$voucher_id . "' ORDER BY vh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalVoucherHistories($voucher_id) {
	  	return $this->db->queryOne("SELECT COUNT(*) AS total FROM  @@voucher_history WHERE voucher_id = " . (int)$voucher_id);
	}
}
?>