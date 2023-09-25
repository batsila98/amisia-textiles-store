<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					// Level 3 
                                $children_data_3 = array();

                                $children_3 = $this->model_catalog_category->getCategories($child['category_id']);

                                foreach ($children_3 as $child_3) {

                                    $filter_data_3 = array(
                                        'filter_category_id'  => $child_3['category_id'],
                                        'filter_sub_category' => true
                                    );

                                    $children_data_3[] = array(
                                        'name'  => $child_3['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data_3) . ')' : ''),
                                        'href'  => $this->url->link('product/category', 'path=' . $child['category_id'] . '_' . $child_3['category_id'])
                                    );
                                }
                                //end of level 3

					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'thumb_menus' => HTTP_SERVER . 'image/' .$child['image'],
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'grand_childs' => $children_data_3//for level 3
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'thumb_menu' => HTTP_SERVER . 'image/' . $category['image'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		
		// trofimenk0
		
		$url_catalog = array('title' => "Каталог");
		$url_about = array();
		$url_reviews = array();
		$url_partners = array();


		foreach ($this->model_catalog_information->getInformations() as $result) {

			if($result['information_id'] == 4) {
				$url_about['title'] = $result['title'];
				$url_about['link'] = $this->url->link('information/information', 'information_id=' . $result['information_id']);
			}

			if($result['information_id'] == 7) {
				$url_reviews['title'] = $result['title'];
				$url_reviews['link'] = $this->url->link('information/information', 'information_id=' . $result['information_id']);
			}

			if($result['information_id'] == 9) {
				$url_partners['title'] = $result['title'];
				$url_partners['link'] = $this->url->link('information/information', 'information_id=' . $result['information_id']);
			}

		}

		$data['menuLinks'] = array(
			'catalog' => $url_catalog,
			'about' => $url_about,
			'partners' 	=> $url_partners,
			'reviews' => $url_reviews,
			'contact' 	=> array('title' => 'Контакти', 'link' => $this->url->link('information/contact')),
		);


		$current_url= $_SERVER['REQUEST_URI'];
		
		if($current_url != '/' && $current_url != '/index.php?route=common/home') {
			$url_home = array(
				'title' => 'Головна',
				'link' => $this->config->get('config_url')
			);

			$data['menuLinks']['home'] = $url_home;
		}

		$data['telephone'] = $this->config->get('config_telephone');
		$data['language'] = $this->load->controller('common/language');
		$data['open'] = nl2br($this->config->get('config_open'));

		// trofimenk0

		return $this->load->view('common/menu', $data);
	}
}
