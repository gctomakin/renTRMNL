<?php

$data['content'] = $content;

$mainScript 	= empty($script) ? array() : $script;
$pageScript = array(
  'pages/main' // Page Script
);

$mainStyle = empty($style) ? array() : $style;
$pageStyle = array(
	'creative',
);

// Merging
$data['script'] = array_merge($pageScript, $mainScript);
$data['style'] = array_merge($pageStyle, $mainStyle);


$this->load->view('base', $data);