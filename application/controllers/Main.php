<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
  {
    // $data['title'] = 'New Title';
    
    $data['content'] = $this->load->view('pages/main', '', true);
    
    /* SAMPLE of Additional Styles and Scripts */
    
    // $data['style'] = array(
    //   'folder/thestyle',
    //   'folder/thestyle2'
    // );
    // $data['script'] = array(
    //   'folder/script1',
    //   'folder/script2'
    // );
    
    $this->load->view('common/main', $data);
	}

  public function admin()
  {
    $data['content'] = $this->load->view('pages/admin', '', true);
    $this->load->view('common/main', $data);
  }


}
