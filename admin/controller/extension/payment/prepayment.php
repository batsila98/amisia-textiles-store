<?php
class ControllerExtensionPaymentPrepayment extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/prepayment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
        // trofimenk0
        $this->load->model('extension/payment/prepayment');
        // trofimenk0

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_prepayment', $this->request->post);

            // trofimenk0

            $availablePrepayment = $this->model_extension_payment_prepayment->getPrepayment();

            if (isset($this->request->post['prepayment_value'])) {
                $newPrepayment = $this->request->post['prepayment_value'];

                if($availablePrepayment) {
                    $oldPrepayment = $availablePrepayment[0]['value'];
                    $this->model_extension_payment_prepayment->updatePrepayment($newPrepayment);
                } else {
                    $this->model_extension_payment_prepayment->setPrepayment($newPrepayment);
                } 
            }

            // trofimenk0

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
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
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/prepayment', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/prepayment', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_prepayment_order_status_id'])) {
			$data['payment_prepayment_order_status_id'] = $this->request->post['payment_prepayment_order_status_id'];
		} else {
			$data['payment_prepayment_order_status_id'] = $this->config->get('payment_prepayment_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_prepayment_status'])) {
			$data['payment_prepayment_status'] = $this->request->post['payment_prepayment_status'];
		} else {
			$data['payment_prepayment_status'] = $this->config->get('payment_prepayment_status');
		}

		if (isset($this->request->post['payment_prepayment_sort_order'])) {
			$data['payment_prepayment_sort_order'] = $this->request->post['payment_prepayment_sort_order'];
		} else {
			$data['payment_prepayment_sort_order'] = $this->config->get('payment_prepayment_sort_order');
		}


        // trofimenk0
        $availablePrepayment = $this->model_extension_payment_prepayment->getPrepayment();

        if($availablePrepayment) {
            $data['prepayment_value'] = $availablePrepayment[0]['value'];
        } else {
            $data['prepayment_value'] = '';
        }
        // trofimenk0



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/prepayment', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/prepayment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


    public function install() {
        $this->load->model('extension/payment/prepayment');

        $this->model_extension_payment_prepayment->install();
    }

    public function uninstall() {
        $this->load->model('extension/payment/prepayment');

        $this->model_extension_payment_prepayment->uninstall();
    }
}