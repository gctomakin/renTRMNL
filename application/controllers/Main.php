<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
  }

	public function index()
  {
    /*
    | $data['title'] = 'New Title';
    */

    $data['content'] = $this->load->view('pages/main', '', TRUE);

    /* SAMPLE of Additional Styles and Scripts
    | $data['style'] = array(
    |   'folder/thestyle',
    |   'folder/thestyle2'
    | );
    | $data['script'] = array(
    |   'folder/script1',
    |   'folder/script2'
    | );
    */
   
    $this->load->view('common/main', $data);
	}

  public function admin()
  {
    $data['content'] = $this->load->view('pages/admin', '', TRUE);
    $this->load->view('common/main', $data);
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect('/', 'refresh');
  }


}
