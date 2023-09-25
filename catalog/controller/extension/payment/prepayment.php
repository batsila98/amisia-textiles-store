<?php
class ControllerExtensionPaymentPrepayment extends Controller {
	public function index() {
		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/prepayment', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'prepayment') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_prepayment_order_status_id'));
		}
	}
}
