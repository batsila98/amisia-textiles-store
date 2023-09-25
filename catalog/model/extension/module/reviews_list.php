<?php 

class ModelExtensionModuleReviewsList extends Model {

    public function getAllReviews() {

        $sql = "SELECT * FROM " . DB_PREFIX . "review";

		$query = $this->db->query($sql);

		return $query->rows;

    }

}



