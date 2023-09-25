<?php
class ControllerExtensionModuleFeedbackForm extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/feedback_form');

        $data = array();
		
		// description
		$current_language_id = $this->config->get('config_language_id');

		if($setting) {
			$data['description'] = html_entity_decode($setting['description'][$current_language_id]);
		} else {
			$data['description'] = "";
		}
		
        $data['action'] = $this->url->link('extension/module/feedback_form/addFeedbackRequest', '', true);

        return $this->load->view('extension/module/feedback_form', $data);
	}



    // trofimenk0 REQUEST FORM
    public function addFeedbackRequest() {

		$json = array();

		$this->load->model('extension/module/feedback_form');

		if (isset($this->request->post['name'])) {
			$name = $this->request->post['name'];
		} else {
			$name = '';
		}

		if (isset($this->request->post['phone'])) {
			$phone = $this->request->post['phone'];
		} else {
			$phone = '';
		}

		$json['data'] = array(
			'name' => $name,
			'phone' => $phone,
		);

		// inserter to the database
		$this->model_extension_module_feedback_form->add($json['data']);

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
			$mail->setReplyTo($this->request->post['phone']);
			$mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf("Нова заявка на зворотній зв'язок від ", $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
			$mail->setText("Ім'я: " . $this->request->post['name'] . "\r\nНомер телефона: " . $this->request->post['phone']);
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
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
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

    // trofimenk0 REQUEST FORM



}