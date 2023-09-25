<?php
class ControllerExtensionModulePartners extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/partners');

        $data = array();
		
		// description
		$current_language_id = $this->config->get('config_language_id');

		$data['description'] = html_entity_decode($setting['description'][$current_language_id]);

        $data['action'] = $this->url->link('extension/module/partners/addPartner', '', true);

		$this->load->model('catalog/information');
		$data['partners_page'] = array();
		foreach ($this->model_catalog_information->getInformations() as $result) {
			if($result['information_id'] == 9) {
				$data['partners_page']['title'] = $result['title'];
				$data['partners_page']['link'] = $this->url->link('information/information', 'information_id=' . $result['information_id']);
			}
		}

        return $this->load->view('extension/module/partners', $data);
	}

    // trofimenk0 MODAL REQUEST FORM
    public function addPartner() {

		$json = array();

		$this->load->model('extension/module/partners');

		if (isset($this->request->post['company_name'])) {
			$company_name = $this->request->post['company_name'];
		} else {
			$company_name = '';
		}

		if (isset($this->request->post['contact_person'])) {
			$contact_person = $this->request->post['contact_person'];
		} else {
			$contact_person = '';
		}

		if (isset($this->request->post['phone'])) {
			$phone = $this->request->post['phone'];
		} else {
			$phone = '';
		}

		if (isset($this->request->post['email'])) {
			$email = $this->request->post['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->post['city'])) {
			$city = $this->request->post['city'];
		} else {
			$city = '';
		}

		if (isset($this->request->post['cooperation_type'])) {
			$cooperation_type = $this->request->post['cooperation_type'];
		} else {
			$cooperation_type = '';
		}

		if (isset($this->request->post['trade_duration'])) {
			$trade_duration = $this->request->post['trade_duration'];
		} else {
			$trade_duration = '';
		}

		$json['data'] = array(
			'company_name' => $company_name,
			'contact_person' => $contact_person,
			'phone' => $phone,
			'email' => $email,
			'city' => $city,
			'cooperation_type' => $cooperation_type,
			'trade_duration' => $trade_duration,
		);

		// inserter to the database
		$this->model_extension_module_partners->add($json['data']);

		// email sender
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setReplyTo($email);
			$mail->setSender(html_entity_decode($company_name, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf("Нова заявка на партнерство від ", $this->request->post['company_name']), ENT_QUOTES, 'UTF-8'));
			$mail->setText(" Назва компанії: " . $company_name . "\r\n Контактна особа: " . $contact_person . "\r\n Номер телефона: " . $phone . "\r\n Email: " . $email . "\r\n Місто: " . $city . "\r\n Тип співпраці: " . $cooperation_type . "\r\n Тривалість роботи в торгівлі: " . $trade_duration);
			$mail->send();
		}

		// Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
			$json['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		} else {
			$json['captcha'] = '';
		}

		// output
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

    }



    protected function validate() {
        if ((utf8_strlen($this->request->post['company_name']) < 3) || (utf8_strlen($this->request->post['company_name']) > 32)) {
            $this->error['company_name'] = $this->language->get('error_company_name');
        }

        if ((utf8_strlen($this->request->post['contact_person']) < 3) || (utf8_strlen($this->request->post['contact_person']) > 32)) {
            $this->error['contact_person'] = $this->language->get('error_contact_person');
        }

        // Captcha
        if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
            $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

            if ($captcha) {
                $this->error['captcha'] = $captcha;
            }
        }

        return !$this->error;
    }

    // trofimenk0 MODAL REQUEST FORM



}