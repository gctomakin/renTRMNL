<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
  {

    $this->load->view('templates/mainHeader');
    $this->load->view('templates/mainNav');
    $this->load->view('pages/main');
    $this->load->view('templates/mainFooter');

	}

  public function admin()
  {
    $this->load->view('templates/mainHeader');
    $this->load->view('templates/mainNav');
    $this->load->view('pages/admin');
    $this->load->view('templates/mainFooter');
  }


}
