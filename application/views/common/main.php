<?php

$data['content'] = $this->load->view('templates/mainNav', '', true); // Main Nav
$data['content'] .= $content;

/** Additional Styles */
if (!empty($style)) {
	$data['styles'] = "";
	foreach ($style as $key => $value) {
		$data['styles'] .= "<link rel='stylesheet' href='". site_url('assets/css/') ."$value.css'>";
	}
}


/** Additional Scripts */
if (!empty($script)) {
	$data['scripts'] = "";
	foreach ($script as $key => $value) {
		$data['scripts'] .= "<script src='". site_url('assets/js/') ."$value.js'></script>";
	}
}

$this->load->view('base', $data);