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

	public function signUp()
  {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]|xss_clean');
    $this->form_validation->set_rules('lessee_fname', 'Firstname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lessee_lname', 'Lastname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lessee_email', 'Email', 'trim|required|valid_email|xss_clean');
    $this->form_validation->set_rules('lessee_phoneno', 'Phone No', 'trim|required|xss_clean');

    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect('main#signup','refresh');

    else:

      $data = array('username'=>$this->input->post('username'),
                    'password'=>$this->encrypt->encode($this->input->post('password')),
                    'lessee_fname'=>$this->input->post('lessee_fname'),
                    'lessee_lname'=>$this->input->post('lessee_lname'),
                    'lessee_email'=>$this->input->post('lessee_email'),
                    'lessee_phoneno'=>$this->input->post('lessee_phoneno'));

      $this->Lessee->create($data);
      $this->session->set_flashdata('success', TRUE);
      redirect('main#signup','refresh');

    endif;
  }

  public function signIn()
  {
    $username = $this->input->post('username',TRUE);
    $password = $this->input->post('password',TRUE);
    $user = $this->Lessee->login($username,$password);

    if(!empty( $user ) && $this->encrypt->decode($user['password']) == $password):

      $userdata = array('lessee_id' => $user['lessee_id'],
                        'username' => $user['username'],
                        'lessee_fname' => $user['lessee_fname'],
                        'lessee_lname' => $user['lessee_lname'],
                        'lessee_email' => $user['lessee_email'],
                        'lessee_phoneno' => $user['lessee_phoneno'],
                        'logged_in' => TRUE);

      $this->session->set_userdata($userdata);
      print_r($this->session->all_userdata());
      //redirect(base_url().'main/lesseeDashboard');

    else:

      $this->session->set_flashdata('error', TRUE);
      redirect('signin-page','refresh');

    endif;
  }

  public function signinPage()
  {
  	$data['title'] = 'SIGN IN';
    $data['content'] = $this->load->view('pages/signin', '', TRUE);
    $this->load->view('common/plain', $data);
  }

}
