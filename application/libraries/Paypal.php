<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Paypal {

	private $apiUrl = "https://svcs.sandbox.paypal.com/AdaptivePayments/";
	private $paypalUrl = "https://www.sandbox.paypal.com/webscr?cmd=_ap-payment&paykey=";

	public function createPacket($amount, $email) {
		// create the pay packet
		if (is_numeric($amount) && $amount > 0) {
			$createPacket = array(
				'actionType' => 'PAY',
				'currencyCode' => 'PHP',
				'receiverList' => array(
					'receiver' => array(
						array('amount' => $amount, 'email' => $email)
					)
				),
				"returnUrl" => site_url('subscriptions/add'),
				// Where the Sender is redirected to upon a canceled payment
				"cancelUrl" => site_url('subscriptions/cancel') ,
				"requestEnvelope" => $this->_envelops()
			);
			return $this->_paypalSend($createPacket, 'Pay');
		}
		return false;
	}

	public function getDetails($payKey) {
		$packet = array(
			"requestEnvelope" => $this->_envelops(),
			"payKey" => $payKey
		);
		return $this->_paypalSend($packet, "PaymentDetails");
	}

	private function _paypalSend($data, $call) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->apiUrl.$call);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_headers());

		return json_decode(curl_exec($ch), TRUE);
	}

	private function _headers() {
		return array(
			"X-PAYPAL-SECURITY-USERID : rentrmnl_seller_api1.gmail.com",
			"X-PAYPAL-SECURITY-PASSWORD : VHNBWK22BK67DXB8",
			"X-PAYPAL-SECURITY-SIGNATURE : AFcWxV21C7fd0v3bYYYRCpSSRl31ADsA7si4gE6Ni66fX-RNRTetjuuK",

			// Global Sandbox Application ID
			"X-PAYPAL-APPLICATION-ID : APP-80W284485P519543T",

			// Input and output formats
			"X-PAYPAL-REQUEST-DATA-FORMAT : JSON",
			"X-PAYPAL-RESPONSE-DATA-FORMAT : JSON"
		);
	}

	private function _envelops() {
		return array(
			"errorLanguage" => "en_US",    // Language used to display errors
			"detailLevel" =>"ReturnAll"
		);
	}

}