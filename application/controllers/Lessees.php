<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessees extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      $this->load->model('Lessee');
  }

  public function index()
  {
    echo 'TODO';
  }

  public function signIn()
  {
    $username = $this->input->post('username',TRUE);
    $password = $this->input->post('password',TRUE);
    $usertype = $this->input->post('usertype',TRUE);
    $user = $this->Lessee->authenticate($username, $password);

    if(!empty($user)):

      if($usertype == 'lessee'):

        $userdata = array('lessee_id' => $user['lessee_id'],
                          'username' => $user['username'],
                          'lessee_fname' => $user['lessee_fname'],
                          'lessee_lname' => $user['lessee_lname'],
                          'lessee_email' => $user['lessee_email'],
                          'lessee_phoneno' => $user['lessee_phoneno'],
                          'logged_in' => TRUE);

        $this->session->set_userdata($userdata);
        redirect('/lessees/');

      else:

        $this->session->set_flashdata('warning', TRUE);
        redirect('signin-page','refresh');

      endif;

    else:

      $this->session->set_flashdata('error', TRUE);
      redirect('signin-page','refresh');

    endif;
  }

  public function signinPage()
  {
    $content['action'] = site_url('signin');
    $data['content'] = $this->load->view('pages/signin', $content, TRUE);
    $data['title'] = 'SIGN IN';
    $this->load->view('common/plain', $data);
  }

}
