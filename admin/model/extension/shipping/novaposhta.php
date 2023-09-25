<?php
class ModelExtensionShippingNovaposhta extends Model {
	public function install() {
		$this->db->query("
            CREATE TABLE `" . DB_PREFIX . "novaposhta_api` (
                `api_id` INT(11) NOT NULL AUTO_INCREMENT,
                `api_key` varchar(255) NOT NULL,
                PRIMARY KEY (`api_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");


        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD novaposhta_area varchar(255);
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD novaposhta_city varchar(255);
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD novaposhta_office varchar(255);
        ");

	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "novaposhta_api`");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN novaposhta_area;
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN novaposhta_city;
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN novaposhta_office;
        ");
	}

    public function getNovaposhtaApi($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "novaposhta_api";

		$query = $this->db->query($sql);

		return $query->rows;
	}

    public function setNovaposhtaApi($apiKey) {
        $this->db->query("INSERT INTO  `" . DB_PREFIX . "novaposhta_api` SET api_key = '" . $this->db->escape($apiKey) . "'");
    }

    public function updateNovaposhtaApi($apiKey) {

        $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "novaposhta_api`");

        $this->db->query("INSERT INTO  `" . DB_PREFIX . "novaposhta_api` SET api_key = '" . $this->db->escape($apiKey) . "'");


    }

}
