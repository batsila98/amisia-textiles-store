<?php
class ControllerExtensionModuleMinimalOrderTotal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/minimal_order_total');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('extension/module/minimal_order_total');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('minimal_order_total', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			// trofimenk0 set minimal_order_total
			$availableMintotal = $this->model_extension_module_minimal_order_total->getMinimalOrderTotal();

			if (isset($this->request->post['mintotal'])) {
				$newMintotal = $this->request->post['mintotal'];

				if($availableMintotal) {
					$oldMinTotal = $availableMintotal[0]['value'];
					$this->model_extension_module_minimal_order_total->updateMinimalOrderTotal($oldMinTotal, $newMintotal);
				} else {
					$this->model_extension_module_minimal_order_total->setMinimalOrderTotal($newMintotal);
				}
			}
			// trofimenk0 set minimal_order_total

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['mintotal'])) {
			$data['error_mintotal'] = $this->error['mintotal'];
		} else {
			$data['error_mintotal'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/minimal_order_total', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/minimal_order_total', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/minimal_order_total', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/minimal_order_total', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

        if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		// set minimal_order_total

		$mintotalData = $this->model_extension_module_minimal_order_total->getMinimalOrderTotal();
        
		$data['mintotal'] = $mintotalData[0]['value'];
		

        // output
		$this->response->setOutput($this->load->view('extension/module/minimal_order_total', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/minimal_order_total')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['mintotal']) < 1) || (utf8_strlen($this->request->post['mintotal']) > 64)) {
			$this->error['mintotal'] = $this->language->get('error_mintotal');
		}

		return !$this->error;
	}

    public function install() {
		$this->load->model('extension/module/minimal_order_total');

		$this->model_extension_module_minimal_order_total->install();
	}

	public function uninstall() {
		$this->load->model('extension/module/minimal_order_total');

		$this->model_extension_module_minimal_order_total->uninstall();
	}

}
