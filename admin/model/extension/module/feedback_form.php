<?php
class ModelExtensionModuleFeedbackForm extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "feedback_form` (
				`feedback_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
                `phone` varchar(255) NOT NULL,
				PRIMARY KEY (`feedback_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "feedback_form`");
	}

    public function getFeedbackFormData($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback_form";

		$query = $this->db->query($sql);

		return $query->rows;
	}

}
