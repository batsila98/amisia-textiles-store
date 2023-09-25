<?php
class ControllerExtensionModuleReviewsList extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/reviews_list');

        $this->load->model('extension/module/reviews_list');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data = array();
		
        $reviews = $this->model_extension_module_reviews_list->getAllReviews();

        $data['reviews'] = array();

        foreach($reviews as $review) {
            
            $product_info = $this->model_catalog_product->getProduct($review['product_id']);

            if ($product_info) {

                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], 200, 200);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                }

                $data['reviews'][] = array(
                    'review'    => $review,
                    'product'   => array(
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                    ),
                );
            }

        }

        return $this->load->view('extension/module/reviews_list', $data);
	}

}