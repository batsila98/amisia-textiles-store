<?php
class ModelExtensionModuleMinimalOrderTotal extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "minimal_order_total` (
				`item_id` INT(11) NOT NULL AUTO_INCREMENT,
				`value` varchar(255) NOT NULL,
				PRIMARY KEY (`item_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "minimal_order_total`");
	}

    public function getMinimalOrderTotal($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "minimal_order_total";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function setMinimalOrderTotal($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "minimal_order_total SET value = '" . (int)$data . "'");

	}

	public function updateMinimalOrderTotal($oldVal, $newVal) {

		$this->db->query("UPDATE `" . DB_PREFIX . "minimal_order_total` SET value = '" . $this->db->escape($newVal) . "' WHERE value = " . $this->db->escape($oldVal));

	}

}
