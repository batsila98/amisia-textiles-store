<?php
class ControllerExtensionShippingNovaposhta extends Controller {
	public function index() {
		$this->load->language('extension/shipping/novaposhta');

        $data = array();

		return $data;
	}

    public function novaposhtaApiRequest() {

        // getting api key
        $json = array();
        $postFields = '';

        $this->load->model('extension/shipping/novaposhta');
        $novaposhtaApiData = $this->model_extension_shipping_novaposhta->getNovaposhtaApi();
        $novaposhtaApiKey = $novaposhtaApiData[0]['api_key'];

        // getting value
        if (isset($this->request->post['value'])) {
			$value = $this->request->post['value'];
		} else {
            $value = "";
        }

        // getting request type
        if (isset($this->request->post['type'])) {
			$type = $this->request->post['type'];
		} else {
            $type = "";
        }

        // getting cityRef
        if (isset($this->request->post['cityRef'])) {
			$cityRef = $this->request->post['cityRef'];
		} else {
            $cityRef = "";
        }

        switch ($type) {
            case "area":
                $postFields = array(
                    "apiKey"=> $novaposhtaApiKey,
                    "modelName"=> "Address",
                    "calledMethod"=> "getAreas",
                    "methodProperties"=> array(
                        "Limit" => 50,
                    ),
                );
                break;
            case "city":
                $postFields = array(
                    "apiKey"=> $novaposhtaApiKey,
                    "modelName"=> "Address",
                    "calledMethod"=> "searchSettlements",
                    "methodProperties"=> array(
                        "Limit" => 50,
                    ),
                );

                if($value) {
                    $postFields["methodProperties"]["CityName"] = $value;
                }

                break;
            case "office":
                $postFields = array(
                    "apiKey"=> $novaposhtaApiKey,
                    "modelName"=> "AddressGeneral",
                    "calledMethod"=> "getWarehouses",
                    "methodProperties"=> array(
                        "Limit" => 50,
                    ),
                );

                if($value) {
                    $postFields["methodProperties"]["FindByString"] = $value;
                }

                if($cityRef) {
                    $postFields["methodProperties"]["SettlementRef"] = $cityRef;
                }
                break;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.novaposhta.ua/v2.0/json/",
        CURLOPT_RETURNTRANSFER => True,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",

        CURLOPT_POSTFIELDS => json_encode($postFields),

        CURLOPT_HTTPHEADER => array("content-type: application/json",),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $json['response'] = "cURL Error #:" . $err;
        } else {
            $json['response'] = $response;
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

    }

}