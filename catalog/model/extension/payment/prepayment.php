<?php
class ModelExtensionPaymentPrepayment extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/prepayment');

		if ($total <= 0.00) {
			$status = false;
		} else {
			$status = true;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'prepayment',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_prepayment_sort_order')
			);
		}

		return $method_data;
	}


	public function getPrepayment($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "prepayment";
		$query = $this->db->query($sql);
		return $query->rows[0];
	}



}