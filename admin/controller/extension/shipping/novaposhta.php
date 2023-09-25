<?php
class ControllerExtensionShippingNovaposhta extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/novaposhta');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

        // trofimenk0
        $this->load->model('extension/shipping/novaposhta');
        // trofimenk0

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_novaposhta', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            // trofimenk0

            $availableAPIkey = $this->model_extension_shipping_novaposhta->getNovaposhtaApi();

            if (isset($this->request->post['novaposhta_api_key'])) {
                $newApiKey = $this->request->post['novaposhta_api_key'];

                if($availableAPIkey) {
                    $oldApiKey = $availableAPIkey[0]['api_key'];
                    $this->model_extension_shipping_novaposhta->updateNovaposhtaApi($newApiKey);
                } else {
                    $this->model_extension_shipping_novaposhta->setNovaposhtaApi($newApiKey);
                } 
            }

            // trofimenk0

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/novaposhta', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/novaposhta', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_novaposhta_total'])) {
			$data['shipping_novaposhta_total'] = $this->request->post['shipping_novaposhta_total'];
		} else {
			$data['shipping_novaposhta_total'] = $this->config->get('shipping_novaposhta_total');
		}

		if (isset($this->request->post['shipping_novaposhta_geo_zone_id'])) {
			$data['shipping_novaposhta_geo_zone_id'] = $this->request->post['shipping_novaposhta_geo_zone_id'];
		} else {
			$data['shipping_novaposhta_geo_zone_id'] = $this->config->get('shipping_novaposhta_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_novaposhta_status'])) {
			$data['shipping_novaposhta_status'] = $this->request->post['shipping_novaposhta_status'];
		} else {
			$data['shipping_novaposhta_status'] = $this->config->get('shipping_novaposhta_status');
		}

		if (isset($this->request->post['shipping_novaposhta_sort_order'])) {
			$data['shipping_novaposhta_sort_order'] = $this->request->post['shipping_novaposhta_sort_order'];
		} else {
			$data['shipping_novaposhta_sort_order'] = $this->config->get('shipping_novaposhta_sort_order');
		}



        // trofimenk0

        $availableAPIkey = $this->model_extension_shipping_novaposhta->getNovaposhtaApi();

        if($availableAPIkey) {
            $data['novaposhta_api_key'] = $availableAPIkey[0]['api_key'];
        } else {
            $data['novaposhta_api_key'] = '';
        }


        // trofimenk0

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/novaposhta', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/novaposhta')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}



    public function install() {
        $this->load->model('extension/shipping/novaposhta');

        $this->model_extension_shipping_novaposhta->install();
    }

    public function uninstall() {
        $this->load->model('extension/shipping/novaposhta');

        $this->model_extension_shipping_novaposhta->uninstall();
    }


}
