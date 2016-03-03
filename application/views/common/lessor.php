<?php

$nav['messageNav'] = $this->Message->findByTo($this->session->userdata('lessor_id'), 'lessor');

$data['content'] = $this->load->view('templates/lessor/nav', $nav, true);
$data['content'] .= $this->load->view('templates/lessor/sidebar', '', true);

$wrapper = array(
	'content' => $content,
	'title' => $title
); 
$data['content'] .= $this->load->view('templates/lessor/wrapper', $wrapper, true);

$mainScript 	= empty($script) ? array() : $script;
$pageScript = array(
	'metisMenu.min',
	'sb-admin-2',
	'common'
);

$mainStyle = empty($style) ? array() : $style;
$pageStyle = array(
	'new-bootstrap.min',
	'metisMenu.min',
	'sb-admin-2',
	'navs'
);

// Merging
$data['script'] = array_merge($pageScript, $mainScript);
$data['style'] = array_merge($pageStyle, $mainStyle);

$data['hasNewBootrstap'] = true;

$this->load->view('base', $data);