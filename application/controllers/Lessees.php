<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessees extends CI_Controller {

  public function __construct()
  {
      parent::__construct(1);
      $this->load->model('Lessee');
      $this->Lessee->setId($this->session->userdata('lessee_id'));
  }

  public function index()
  {
    $data['title'] = 'DASHBOARD';
    $data['content'] = $this->load->view('pages/lessee/dashboard', '', TRUE);
    $this->load->view('common/lessee', $data);
  }

  public function signin()
  {
    $usertype = $this->input->post('usertype',TRUE);
    $this->Lessee->setUsername($this->input->post('username',TRUE));
    $this->Lessee->setPassword($this->input->post('password',TRUE));
    $user = $this->Lessee->authenticate();

    if(!empty($user)):

      if($usertype == 'lessee'):

        $userdata = array('lessee_id' => $user['lessee_id'],
                          'username' => $user['username'],
                          'lessee_fname' => $user['lessee_fname'],
                          'lessee_lname' => $user['lessee_lname'],
                          'lessee_email' => $user['lessee_email'],
                          'lessee_phoneno' => $user['lessee_phoneno'],
                          'image' => $user['image'],
                          'logged_in' => TRUE);

        $this->session->set_userdata($userdata);
        redirect('lessees');

      else:

        $this->session->set_flashdata('warning', TRUE);
        redirect('lessees/signin-page','refresh');

      endif;

    else:

      $this->session->set_flashdata('error', TRUE);
      redirect('lessees/signin-page','refresh');

    endif;
  }

  public function signinPage()
  {
    $content['action'] = site_url('lessees/signin');
    $data['content'] = $this->load->view('pages/signin', $content, TRUE);
    $data['title'] = 'SIGN IN';
    $this->load->view('common/plain', $data);
  }

  public function profilePage()
  {
    //$content['action'] = site_url('signin');
    $content['lessee'] = $this->Lessee->findById();
    $data['content'] = $this->load->view('pages/lessee/profile', $content, TRUE);
    $data['title'] = 'MY PROFILE';
    $this->load->view('common/lessee', $data);
  }

  public function updateInfo()
  {
    $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
    $this->form_validation->set_rules('phoneno', 'Phone No', 'trim|required|xss_clean');
    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('ui_val_error', validation_errors());
      redirect('lessee/profile','refresh');

    else:

      $post = $this->input->post(NULL, TRUE);
      $this->Lessee->setFname($post['fname']);
      $this->Lessee->setLname($post['lname']);
      $this->Lessee->setEmail($post['email']);
      $this->Lessee->setPhoneno($post['phoneno']);
      $result = $this->Lessee->updateInfo();
      if($result):
        $this->session->set_flashdata('ui_success', TRUE);
        redirect('lessee/profile','refresh');
      else:
        $this->session->set_flashdata('ui_error', TRUE);
        redirect('lessee/profile','refresh');
      endif;

    endif;

  }

  public function updateAccount()
  {
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[new_password]|xss_clean');

    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('ua_val_error', validation_errors());
      redirect('lessee/profile','refresh');

    else:

      $post = $this->input->post(NULL, TRUE);
      $this->Lessee->setUsername($post['username']);
      $this->Lessee->setPassword($post['password']);
      if($this->Lessee->checkPassword()):
        $this->Lessee->setPassword($this->encrypt->encode($post['new_password']));
        $result = $this->Lessee->updateAccount();
      else:
        $this->session->set_flashdata('warning', TRUE);
        redirect('lessee/profile','refresh');
      endif;

      if($result):
        $this->session->set_flashdata('ua_success', TRUE);
        redirect('lessee/profile','refresh');
      else:
        $this->session->set_flashdata('ua_error', TRUE);
        redirect('lessee/profile','refresh');
      endif;

    endif;
  }

  public function upload()
  {
     $config['upload_path']          = './uploads/';
     $config['allowed_types']        = 'gif|jpg|png';
     $config['max_size']             = 100;
     $config['max_width']            = 1024;
     $config['max_height']           = 768;
     $config['encrypt_name']         = true;

     $this->load->library('upload', $config);

     if ( ! $this->upload->do_upload('userfile')):
       $this->session->set_flashdata('upload_error', $this->upload->display_errors());
       redirect('lessee/profile','refresh');
     else:
       $file = $this->upload->data();
       $this->Lessee->setImage($file['file_name']);
       $this->Lessee->uploadImage();
       @unlink(FCPATH.'/uploads/'.$this->session->userdata('image'));
       $this->session->set_userdata('image', $file['file_name']);
       $this->session->set_flashdata('upload_success', TRUE);
       redirect('lessee/profile','refresh');
     endif;
  }

}
