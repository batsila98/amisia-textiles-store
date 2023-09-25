<?php 

class ModelExtensionModuleMinimalOrderTotal extends Model {

    public function getMinimalOrderTotal() {

        $sql = "SELECT * FROM " . DB_PREFIX . "minimal_order_total";

		$query = $this->db->query($sql);

		return $query->rows;

    }

}

