<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$this->load->model('catalog/category');

		$data['informations'] = array();

		$data['footer_left'] = $this->load->controller('common/footer_left');
		$data['footer_right'] = $this->load->controller('common/footer_right');
		$data['ftop_full'] = $this->load->controller('common/ftop_full');
		$data['fbottom_full'] = $this->load->controller('common/fbottom_full');


		$data['informations']['home'] = array(
			'title' => 'Головна',
			'href' => $this->config->get('config_url'),
		);

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}


		// trofimenk0 FOOTER MENU
		$categories = $this->model_catalog_category->getCategories(0);
		$data['informations']['catalog'] = array('title' => "Каталог", 'href' => $this->url->link('product/category', 'path=' . $categories[0]['category_id']));

		$data['informations']['blog'] = array(
			'title' => 'Блог',
			'href' 	=> $this->url->link('information/blogger/blogs'),
		);
		// trofimenk0 FOOTER MENU




		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');


		
		
		return $this->load->view('common/footer', $data);
	}


}
