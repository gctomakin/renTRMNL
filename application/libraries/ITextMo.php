<?php

class ITextMo {

	/* SET YOUR API CODE HERE */
	private $apicode = "09326981272_H991U";

	//##########################################################################
	// ITEXMO SEND SMS API - CURL METHOD
	// Visit www.itexmo.com/developers.php for more info about this API
	//##########################################################################
	// public function itexmo($number, $message) {
	// 	$itexmo = array('1' => $number, '2' => $message, '3' => $this->apicode);
	// 	return $this->_processITextMo($itexmo);
	// }
	
	// public function itexmo_bal(){
	// 	$itexmo = array('4' => $this->apicode);
	// 	return $this->_processITextMo($itexmo);
	// }

	// private function _processITextMo($request) {
	// 	$ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
	// 	curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
	// 	curl_setopt($ch, CURLOPT_POST, 1);
	// 	curl_setopt($ch, CURLOPT_POSTFIELDS, 
	// 	http_build_query($request));
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	return curl_exec ($ch);
	// 	curl_close ($ch);
	// }
	//##########################################################################
	
	function itexmo($number,$message, $apicode = ""){
		$apicode = empty($apicode) ? $this->apicode : $apicode;
		$url = 'https://www.itexmo.com/php_api/api.php';
		$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
		$param = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($itexmo),
		    ),
		);
		$context  = stream_context_create($param);
		return file_get_contents($url, false, $context);
	}

	function itexmo_bal($apicode = ""){
		$apicode = empty($apicode) ? $this->apicode : $apicode;
		$url = 'https://www.itexmo.com/php_api/api.php';
		$itexmo = array('4' => $apicode);
		$param = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($itexmo),
		    ),
		);
		$context  = stream_context_create($param);
		return file_get_contents($url, false, $context);
	}
		//##########################################################################
}