<?php
class ModelExtensionShippingNovaposhta extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/novaposhta');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_novaposhta_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_novaposhta_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		if ($this->cart->getSubTotal() < $this->config->get('shipping_novaposhta_total')) {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['novaposhta'] = array(
				'code'         => 'novaposhta.novaposhta',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00, $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'novaposhta',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_novaposhta_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

    public function getNovaposhtaApi() {
        $sql = "SELECT * FROM " . DB_PREFIX . "novaposhta_api";

		$query = $this->db->query($sql);

		return $query->rows;
    }
}