<?php

$data['content'] = $this->load->view('templates/lessor/nav', '', true);
$data['content'] .= $this->load->view('templates/lessor/sidebar', '', true);

$wrapper = array(
	'content' => $content,
	'title' => $title
); 
$data['content'] .= $this->load->view('templates/lessor/wrapper', $wrapper, true);

$data['script'] 	= empty($script) ? array() : $script;
$data['script'][] = 'metisMenu.min';
$data['script'][] = 'sb-admin-2';

$data['style'] 		= empty($style) ? array() : $style;
$data['style'][] 	= 'new-bootstrap.min';
$data['style'][] 	= 'metisMenu.min';
$data['style'][] 	= 'sb-admin-2';

$data['hasNewBootrstap'] = true;




$this->load->view('base', $data);