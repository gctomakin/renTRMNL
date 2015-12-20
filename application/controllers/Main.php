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

  public function signUp()
  {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]|xss_clean');
    $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
    $this->form_validation->set_rules('phoneno', 'Phone No', 'trim|required|xss_clean');
    $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');

    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect('main#signup','refresh');

    else:
      $post = $this->input->post();
      
      $data = array(
        'username'=> $post['username'],
        'password'=> $this->encrypt->encode($post['password'])
      );

      if ($post['user_type'] != 'lessor') {
        $data['lessee_fname'] = $post['fname'];
        $data['lessee_lname'] = $post['lname'];
        $data['lessee_email'] = $post['email'];
        $data['lessee_phoneno'] = $post['phoneno'];
        
        $this->load->model('Lessee');
        $this->Lessee->create($data);
      } else {
        $this->load->model('Subscriber');
        $data[$this->Subscriber->getFname()] = $post['fname'];
        $data[$this->Subscriber->getLname()] = $post['lname'];
        $data[$this->Subscriber->getEmail()] = $post['email'];
        $data[$this->Subscriber->getTelno()] = $post['phoneno'];
       
        $this->Subscriber->create($data);
      }
      $this->session->set_flashdata('success', TRUE);
      redirect('main#signup','refresh');

    endif;
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
