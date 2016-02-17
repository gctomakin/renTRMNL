<?php

$data['content'] = $this->load->view('templates/mainNav', '', true); // Main Nav
$data['content'] .= $content;

$data['script'] = array(
	'particle',
  'app',
  'jquery.easing.min', // Plugin Script From -->
  'jquery.fittext', 
  'wow.min', // <-- End
  'creative', // Custome Theme
  'libs/pnotify.core',
  'libs/pnotify.buttons',    
  'common',
  'pages/main' // Page Script
);

$data['style'] = array(
	'libs/pnotify',
	'animate.min', //Plugin CSS
	'creative'
);

$data['bodyId'] = "page-top";

$this->load->view('base', $data);