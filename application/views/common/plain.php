<?php

$data['content'] = $content;

$data['script'] = array(
  'pages/main' // Page Script
);

$data['style'] = array('creative');

$this->load->view('base', $data);