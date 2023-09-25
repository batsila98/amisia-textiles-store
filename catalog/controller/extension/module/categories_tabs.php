<?php
class ControllerExtensionModuleCategoriesTabs extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/categories_tabs');

		$this->load->model('catalog/product');
        $this->load->model('catalog/category');

		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/owl.theme.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/owl.carousel.min.js');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

        if (!empty($setting['category'])) {
			$categories = $setting['category'];

			foreach ($categories as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    $data['categories'][$category_info['category_id']] = array(
                        'name' => $category_info['name'],
                        'category_id' => $category_info['category_id']
                    );

                    $filter_data = array(
                        'filter_category_id' => $category_info['category_id'],
                        'filter_filter'      => '',
                        'sort'               => '',
                        'order'              => '',
                        'start'              => 0,
                        'limit'              => (int)$setting['limit']
                    );
        
                    $products = $this->model_catalog_product->getProducts($filter_data);
        
                    
                    if($products) {
                        foreach($products as $product_info) {

                            // trofimenk0
                            $discounts_info = $this->model_catalog_product->getProductDiscounts($product_info['product_id']);

                            $discounts = array();

                            foreach ($discounts_info as $discount) {
                                $discounts[] = array(
                                    'quantity' => sprintf($this->language->get('discountText'), $discount['quantity']),
                                    'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                                );
                            }
                            // trofimenk0

                            if ($product_info['image']) {
                                $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
                            } else {
                                $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                            }

                            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                                $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                            } else {
                                $price = false;
                            }

                            if ((float)$product_info['special']) {
                                $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                            } else {
                                $special = false;
                            }

                            if ($this->config->get('config_tax')) {
                                $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
                            } else {
                                $tax = false;
                            }

                            if ($this->config->get('config_review_status')) {
                                $rating = $product_info['rating'];
                            } else {
                                $rating = false;
                            }

                            /*Additional images start*/
                                    
                            $more_images['images'] = array();
                            
                            $results = $this->model_catalog_product->getProductImages($product_info['product_id']);
                            
                            foreach ($results as $result){
                                    $more_images['images'][]=array(
                                        'popup_more' => $this->model_tool_image->resize($result['image'],$setting['width'], $setting['height'])
                                    );
                                    //print_r($more_images);
                            }
                            $more_images['product_id_images']=$product_info['product_id'];
                                    
                            /*Additional images end*/


                            $data['categories'][$category_info['category_id']]['products'][] = array(
                                'product_id'  => $product_info['product_id'],
                                'thumb'       => $image,
                                'name'        => $product_info['name'],
                                'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                                'price'       => $price,
                                'special'     => $special,
                                'tax'         => $tax,
                                'rating'      => $rating,
                                // trofimenk0
                                'sku' 		  => $product_info["sku"],
                                'discounts'   => $discounts,
                                // trofimenk0
                                // Add images Data 
                                    'more_images' => $more_images, //Additional images
                                //End
                                'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                            );
                        }
                    }
                }
			}
		}

		if ($data['categories']) {
			return $this->load->view('extension/module/categories_tabs', $data);
		}
	}
}