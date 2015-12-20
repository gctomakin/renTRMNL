<?php

$data['content'] = $this->load->view('templates/mainNav', '', true); // Main Nav
$data['content'] .= $content;

$this->load->view('base', $data);