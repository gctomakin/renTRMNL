<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessors extends CI_Controller {

	public function __construct() {
      parent::__construct();
      $this->load->model('Subscriber');
  }

  public function signin() {
  	$content['action'] = site_url('lessors/signinSubmit');
    $data['content'] = $this->load->view('pages/signin', $content, TRUE);
    $data['title'] = 'LESSOR SIGN IN';
    $this->load->view('common/plain', $data);
  }

  public function signinSubmit() {
  	echo "TODO";
  }
}