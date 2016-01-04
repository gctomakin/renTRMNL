<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

  public function __construct()
  {
      parent::__construct(4);
      $this->load->model('Admin');
      $this->load->model('SubscriptionPlan');
      $this->Admin->setId($this->session->userdata('admin_id'));
      LibsLoader();
  }

  public function index()
  {
    $data['title'] = 'DASHBOARD';
    $data['content'] = $this->load->view('pages/admin/dashboard', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function accountsAddPage()
  {
    $data['title'] = 'ACCOUNTS ADD';
    $data['content'] = $this->load->view('pages/admin/accounts/add', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function accountsViewPage()
  {
    $data['title'] = 'ACCOUNTS LIST';
    $data['content'] = $this->load->view('pages/admin/accounts/view', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function subscription_plansAddPage()
  {
    $data['title'] = 'SUBSCRIPTION PLANS ADD';
    $data['content'] = $this->load->view('pages/admin/subscription_plans/add', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function subscription_plansViewPage()
  {
    $data['title'] = 'SUBSCRIPTION PLANS LIST';
    $data['content'] = $this->load->view('pages/admin/subscription_plans/view', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function rental_shopsAddPage()
  {
    $data['title'] = 'RENTAL SHOPS ADD';
    $data['content'] = $this->load->view('pages/admin/rental_shops/add', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function rental_shopsViewPage()
  {
    $data['title'] = 'RENTAL SHOPS LIST';
    $data['content'] = $this->load->view('pages/admin/rental_shops/view', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function categoriesAddPage()
  {
    $data['title'] = 'CATEGORIES ADD';
    $data['content'] = $this->load->view('pages/admin/categories/add', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function categoriesViewPage()
  {
    $data['title'] = 'CATEGORIES LIST';
    $data['content'] = $this->load->view('pages/admin/categories/view', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function signin()
  {
    $usertype = 'admin';
    $this->Admin->setUsername($this->input->post('username',TRUE));
    $this->Admin->setPassword($this->input->post('password',TRUE));
    $user = $this->Admin->authenticate();

    if(!empty($user)):

      if($usertype == 'admin'):

        $userdata = array('admin_id' => $user['admin_id'],
                          'username' => $user['username'],
                          'admin_fname' => $user['admin_fname'],
                          'admin_midint' => $user['admin_midint'],
                          'admin_lname' => $user['admin_lname'],
                          'admin_status' => $user['admin_status'],
                          'admin_logged_in' => TRUE);

        $this->session->set_userdata($userdata);
        redirect('admin/dashboard');

      else:

        $this->session->set_flashdata('warning', TRUE);
        redirect('admin/signin-page','refresh');

      endif;

    else:

      $this->session->set_flashdata('error', TRUE);
      redirect('admin/signin-page','refresh');

    endif;
  }

  public function signinPage()
  {
    $content['action'] = site_url('admin/signin');
    $data['content'] = $this->load->view('pages/admin', $content, TRUE);
    $data['title'] = 'SIGN IN';
    $this->load->view('common/plain', $data);
  }

  public function addAccount()
  {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]|xss_clean');
    $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|xss_clean');
    $this->form_validation->set_rules('midinit', 'Middle Initial', 'trim|required|xss_clean');


    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect('admin/accounts/add','refresh');

    else:
      $post = $this->input->post(NULL, TRUE);

      $this->Admin->setFname($post['fname']);
      $this->Admin->setLname($post['lname']);
      $this->Admin->setMidinit($post['midinit']);
      $this->Admin->setUsername($post['username']);
      $this->Admin->setPassword($this->encrypt->encode($post['password']));
      $this->Admin->insert();

      $this->session->set_flashdata('success', TRUE);
      redirect('admin/accounts/add','refresh');

    endif;
  }

  public function addSubscriptionPlan()
  {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('plan_name', 'Plan Name', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('plan_desc', 'Plan Desc', 'trim|required|xss_clean');
    $this->form_validation->set_rules('plan_type', 'Plan Type', 'trim|required|xss_clean');
    $this->form_validation->set_rules('plan_rate', 'Plan Rate', 'trim|required|xss_clean');


    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect('admin/subscriptions/add','refresh');

    else:
      $post = $this->input->post(NULL, TRUE);

      $this->SubscriptionPlan->setName($post['plan_name']);
      $this->SubscriptionPlan->setDesc($post['plan_desc']);
      $this->SubscriptionPlan->setType($post['plan_type']);
      $this->SubscriptionPlan->setRate($post['plan_rate']);
      $this->SubscriptionPlan->insert();

      $this->session->set_flashdata('success', TRUE);
      redirect('admin/subscriptions/add','refresh');

    endif;
  }

}
