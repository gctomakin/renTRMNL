<?php

$data['content'] = $content;

$data['script'] = array(
  'pages/main' // Page Script
);

$this->load->view('base', $data);