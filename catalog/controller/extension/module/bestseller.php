<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/bestseller');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				/* insp Images Start */

				$insp_data['insp_images'] = array();
				$insp_results = $this->model_catalog_product->getProductImages($result['product_id']);

				

				foreach ($insp_results as $insp_result) {
					$insp_data['insp_images'][] = array('popup' => $this->model_tool_image->resize($insp_result['image'],$setting['width'], $setting['height']));
				}
				

				/* End */


				// trofimenk0
				$discounts_info = $this->model_catalog_product->getProductDiscounts($result['product_id']);

				$discounts = array();

				foreach ($discounts_info as $discount) {
					$discounts[] = array(
						'quantity' => sprintf($this->language->get('discountText'), $discount['quantity']),
						'price'    => $this->currency->format($this->tax->calculate($discount['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
				// trofimenk0

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					// trofimenk0
					'sku' 		  => $result["sku"],
					'discounts'   => $discounts,
					// trofimenk0
					// Add images Data 
					'insp_images' => $insp_data['insp_images'],
					//End
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}

			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
