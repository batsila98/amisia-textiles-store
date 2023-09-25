<?php
class ModelExtensionShippingUkrposhta extends Model {
	public function install() {
        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD ukrposhta_city varchar(255);
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD ukrposhta_office varchar(255);
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` ADD ukrposhta_index varchar(255);
        ");
	}

	public function uninstall() {
        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN ukrposhta_city;
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN ukrposhta_office;
        ");

        $this->db->query("
            ALTER TABLE `" . DB_PREFIX . "order` DROP COLUMN ukrposhta_index;
        ");
	}

}
