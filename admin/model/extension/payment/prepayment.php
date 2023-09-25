<?php
class ModelExtensionPaymentPrepayment extends Model {

    public function install() {
		$this->db->query("
            CREATE TABLE `" . DB_PREFIX . "prepayment` (
                `prepayment_id` INT(11) NOT NULL AUTO_INCREMENT,
                `value` varchar(255) NOT NULL,
                PRIMARY KEY (`prepayment_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");
        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD prepayment varchar(255);
        ");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "prepayment`");
        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN prepayment;
        ");
	}

    public function getPrepayment($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "prepayment";
		$query = $this->db->query($sql);
		return $query->rows[0];
	}

    public function setPrepayment($prepayment_price) {
        $this->db->query("INSERT INTO  `" . DB_PREFIX . "prepayment` SET value = '" . $this->db->escape($prepayment_price) . "'");
    }

    public function updatePrepayment($prepayment_price) {
        $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "prepayment`");
        $this->db->query("INSERT INTO  `" . DB_PREFIX . "prepayment` SET value = '" . $this->db->escape($prepayment_price) . "'");
    }


}