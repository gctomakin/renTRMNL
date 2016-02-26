<?php

class ITextMo {

	/* SET YOUR API CODE HERE */
	private $apicode = "dawdawdawdwa";

	//##########################################################################
	// ITEXMO SEND SMS API - CURL METHOD
	// Visit www.itexmo.com/developers.php for more info about this API
	//##########################################################################
	public function itexmo($number, $message){
		$itexmo = array('1' => $number, '2' => $message, '3' => $this->apicode);
		$this->_processITextMo($itexmo);
	}
	
	public function itexmo_bal(){
		$itexmo = array('4' => $this->apicode);
		$this->_processITextMo($itexmo);
	}

	private function _processITextMo(request) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
		http_build_query($request));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec ($ch);
		curl_close ($ch);
	}
	//##########################################################################
}