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
  'pages/main', // Page Script
  'libs/pnotify.core',
  'libs/pnotify.button',
  'common'
);

$data['style'] = array(
	'animate.min', //Plugin CSS
	'creative',
	'libs/pnotify'
);

$data['bodyId'] = "page-top";

$this->load->view('base', $data);