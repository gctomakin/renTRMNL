<?php

$data['content'] = $this->load->view('templates/lessee/nav', '', true);
$data['content'] .= $this->load->view('templates/lessee/sidebar', '', true);

$wrapper = array(
	'content' => $content,
	'title' => $title
); 
$data['content'] .= $this->load->view('templates/lessee/wrapper', $wrapper, true);

$data['script'] 	= empty($script) ? array() : $script;
$data['script'][] = 'metisMenu.min';
$data['script'][] = 'sb-admin-2';
$data['script'][] = 'common';

$data['style'] 		= empty($style) ? array() : $style;
$data['style'][] 	= 'new-bootstrap.min';
$data['style'][] 	= 'metisMenu.min';
$data['style'][] 	= 'sb-admin-2';
$data['style'][] 	= 'navs';

$data['hasNewBootrstap'] = true;




$this->load->view('base', $data);