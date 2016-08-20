<?php
class ModelPaymentFirstdata extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "firstdata_order` (
				`firstdata_order_id` int(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) NOT NULL,
				`order_ref` varchar(50) NOT NULL,
				`order_ref_previous` varchar(50) NOT NULL,
				`pasref` varchar(50) NOT NULL,
				`pasref_previous` varchar(50) NOT NULL,
				`tdate` datetime NOT NULL,
				`date_added` datetime NOT NULL,
				`date_modified` datetime NOT NULL,
				`capture_status` tinyint(1) DEFAULT NULL,
				`void_status` tinyint(1) DEFAULT NULL,
				`currency_code` varchar(3) NOT NULL,
				`authcode` varchar(30) NOT NULL,
				`account` varchar(30) NOT NULL,
				`total` decimal(10, 2) NOT NULL,
				PRIMARY KEY (`firstdata_order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "firstdata_order_transaction` (
				`firstdata_order_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
				`firstdata_order_id` int(11) NOT NULL,
				`date_added` datetime NOT NULL,
				`type` enum('auth', 'payment', 'void') DEFAULT NULL,
				`amount` decimal(10, 2) NOT NULL,
				PRIMARY KEY (`firstdata_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "firstdata_card` (
				`firstdata_card_id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` int(11) NOT NULL,
				`date_added` datetime NOT NULL,
				`digits` varchar(25) NOT NULL,
				`expire_month` int(2) NOT NULL,
				`expire_year` int(2) NOT NULL,
				`token` varchar(64) NOT NULL,
				PRIMARY KEY (`firstdata_card_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "firstdata_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "firstdata_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "firstdata_card`;");
	}

	public function void($order_id) {
		$firstdata_order = $this->getOrder($order_id);

		if (!empty($firstdata_order)) {
			$timestamp = strftime("%Y%m%d%H%M%S");

			$merchant_id = $this->config->get('firstdata_merchant_id');
			$secret = $this->config->get('firstdata_secret');

			$this->logger('Void hash construct: ' . $timestamp . ' . ' . $merchant_id . ' . ' . $firstdata_order['order_ref'] . ' . . . ');

			$tmp = $timestamp . ' . ' . $merchant_id . ' . ' . $firstdata_order['order_ref'] . ' . . . ';
			$hash = sha1($tmp);
			$tmp = $hash . ' . ' . $secret;
			$hash = sha1($tmp);

			$xml = '';
			$xml .= '<request type="void" timestamp="' . $timestamp . '">';
			$xml .= '<merchantid>' . $merchant_id . '</merchantid>';
			$xml .= '<account>' . $firstdata_order['account'] . '</account>';
			$xml .= '<orderid>' . $firstdata_order['order_ref'] . '</orderid>';
			$xml .= '<pasref>' . $firstdata_order['pasref'] . '</pasref>';
			$xml .= '<authcode>' . $firstdata_order['authcode'] . '</authcode>';
			$xml .= '<sha1hash>' . $hash . '</sha1hash>';
			$xml .= '</request>';

			$this->logger('Void XML request:\r\n' . print_r(simplexml_load_string($xml), 1));

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "OpenCart " . VERSION);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec ($ch);

			curl_close ($ch);

			return simplexml_load_string($response);
		} else {
			return false;
		}
	}

	public function updateVoidStatus($firstdata_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "firstdata_order` SET void_status = '" . (int)$status . "' WHERE firstdata_order_id = '" . (int)$firstdata_order_id . "'");
	}

	public function capture($order_id, $amount) {
		$firstdata_order = $this->getOrder($order_id);

		if (!empty($firstdata_order) && $firstdata_order['capture_status'] == 0) {
			$timestamp = strftime("%Y%m%d%H%M%S");

			$merchant_id = $this->config->get('firstdata_merchant_id');
			$secret = $this->config->get('firstdata_secret');

			if ($firstdata_order['settle_type'] == 2) {
				$this->logger('Capture hash construct: ' . $timestamp . ' . ' . $merchant_id . ' . ' . $firstdata_order['order_ref'] . ' . ' . (int)round($amount*100) . ' . ' . (string)$firstdata_order['currency_code'] . ' . ');

				$tmp = $timestamp . ' . ' . $merchant_id . ' . ' . $firstdata_order['order_ref'] . ' . ' . (int)round($amount*100) . ' . ' . (string)$firstdata_order['currency_code'] . ' . ';
				$hash = sha1($tmp);
				$tmp = $hash . ' . ' . $secret;
				$hash = sha1($tmp);

				$settle_type = 'multisettle';
				$xml_amount = '<amount currency="' . (string)$firstdata_order['currency_code'] . '">' . (int)round($amount*100) . '</amount>';
			} else {
				$this->logger('Capture hash construct: ' . $timestamp . ' . ' . $merchant_id . ' . ' . $firstdata_order['order_ref'] . ' . ' . (int)round($amount*100) . ' . ' . (string)$firstdata_order['currency_code'] . ' . ');

				$tmp = $timestamp . ' . ' . $merchant_id . ' . ' . $firstdata_order['order_ref'] . ' . ' . (int)round($amount*100) . ' . ' . (string)$firstdata_order['currency_code'] . ' . ';
				$hash = sha1($tmp);
				$tmp = $hash . ' . ' . $secret;
				$hash = sha1($tmp);

				$settle_type = 'settle';
				$xml_amount = '<amount currency="' . (string)$firstdata_order['currency_code'] . '">' . (int)round($amount*100) . '</amount>';
			}

			$xml = '';
			$xml .= '<request type="' . $settle_type . '" timestamp="' . $timestamp . '">';
			$xml .= '<merchantid>' . $merchant_id . '</merchantid>';
			$xml .= '<account>' . $firstdata_order['account'] . '</account>';
			$xml .= '<orderid>' . $firstdata_order['order_ref'] . '</orderid>';
			$xml .= $xml_amount;
			$xml .= '<pasref>' . $firstdata_order['pasref'] . '</pasref>';
			$xml .= '<autosettle flag="1" />';
			$xml .= '<authcode>' . $firstdata_order['authcode'] . '</authcode>';
			$xml .= '<sha1hash>' . $hash . '</sha1hash>';
			$xml .= '</request>';

			$this->logger('Settle XML request:\r\n' . print_r(simplexml_load_string($xml), 1));

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "OpenCart " . VERSION);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec ($ch);

			curl_close ($ch);

			return simplexml_load_string($response);
		} else {
			return false;
		}
	}

	public function updateCaptureStatus($firstdata_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "firstdata_order` SET capture_status = '" . (int)$status . "' WHERE firstdata_order_id = '" . (int)$firstdata_order_id . "'");
	}

	public function getOrder($order_id) {
		$this->logger('getOrder - ' . $order_id);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "firstdata_order` WHERE order_id = '" . (int)$order_id . "' LIMIT 1");

		if ($query->num_rows) {
			$order = $query->row;
			$order['transactions'] = $this->getTransactions($order['firstdata_order_id']);

			$this->logger(print_r($order, 1));

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($firstdata_order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "firstdata_order_transaction WHERE firstdata_order_id = '" . (int)$firstdata_order_id . "'");

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
	}

	public function addTransaction($firstdata_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "firstdata_order_transaction` SET firstdata_order_id = '" . (int)$firstdata_order_id . "', date_added = NOW(), `type` = '" . $this->db->escape($type) . "', amount = '" . (float)$total . "'");
	}

	public function logger($message) {
		if ($this->config->get('firstdata_debug') == 1) {
			$log = new Log('firstdata.log');
			$log->write($message);
		}
	}

	public function getTotalCaptured($firstdata_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS total FROM " . DB_PREFIX . "firstdata_order_transaction WHERE firstdata_order_id = '" . (int)$firstdata_order_id . "' AND (`type` = 'payment' OR `type` = 'refund')");

		return (float)$query->row['total'];
	}

	public function mapCurrency($code) {
		$currency = array(
			'GBP' => 826,
			'USD' => 840,
			'EUR' => 978
		);

		if (array_key_exists($code, $currency)) {
			return $currency[$code];
		} else {
			return false;
		}
	}
}
?>
