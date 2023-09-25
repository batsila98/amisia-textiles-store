<?php
class ModelExtensionModulePartners extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "partners` (
				`partner_id` INT(11) NOT NULL AUTO_INCREMENT,
				`company_name` varchar(255) NOT NULL,
                `contact_person` varchar(255) NOT NULL,
                `phone` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
				`city` varchar(255) NOT NULL,
				`cooperation_type` varchar(255) NOT NULL,
				`trade_duration` varchar(255) NOT NULL,
				PRIMARY KEY (`partner_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "partners`");
	}

    public function getPartners($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "partners";

		$query = $this->db->query($sql);

		return $query->rows;
	}

}
