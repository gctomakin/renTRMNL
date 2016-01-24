<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

  public function __construct()
  {
      parent::__construct(4);
      $this->load->model('Admin');
      $this->load->model('SubscriptionPlan');
      $this->load->model('RentalShop');
      $this->load->model('Category');
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
    $content['admins'] = $this->Admin->all();
    $data['content'] = $this->load->view('pages/admin/accounts/view', $content, TRUE);
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
    $content['plans'] = $this->SubscriptionPlan->all();
    $data['content'] = $this->load->view('pages/admin/subscription_plans/view', $content, TRUE);
    $this->load->view('common/admin', $data);
  }

  public function rental_shopsAddPage()
  {
    $data['title'] = 'RENTAL SHOPS ADD';
    $data['content'] = $this->load->view('pages/admin/rental_shops/add', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function rental_shopsPendingPage()
  {
    $data['title'] = 'PENDING RENTAL SHOPS';
    $content['shops'] = $this->RentalShop->findByStatus('pending');
    $data['content'] = $this->load->view('pages/admin/rental_shops/pendings', $content, TRUE);
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/admins/shops/pendings'
    );
    $this->load->view('common/admin', $data);
  }

  public function rental_shopsViewPage()
  {
    $data['title'] = 'RENTAL SHOPS LIST';
    $content['shops'] = $this->RentalShop->all($select = "*");
    $data['content'] = $this->load->view('pages/admin/rental_shops/view', $content, TRUE);
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/admins/shops/list'
    );
    $this->load->view('common/admin', $data);
  }

  public function categoriesAddPage()
  {
    $data['title'] = 'CATEGORIES ADD';
    $content['action'] = 'admin/category/add';
    $data['content'] = $this->load->view('pages/admin/categories/form', $content, TRUE);
    $data['script'] = array('pages/admins/categories/form');
    $this->load->view('common/admin', $data);
  }

  public function categoriesEditPage($id) {
    $data['title'] = 'CATEGORIES EDIT';
    $content['category'] = $this->Category->findById($id);
    $content['action'] = 'admin/category/edit';
    $data['content'] = $this->load->view('pages/admin/categories/form', $content, TRUE);
    $data['script'] = array('pages/admins/categories/form');
    $this->load->view('common/admin', $data);
  }

  public function categoriesViewPage()
  {
    $data['title'] = 'CATEGORIES LIST';
    $content['categories'] = $this->Category->all($select = "*", $like = "");
    $data['content'] = $this->load->view('pages/admin/categories/view', $content, TRUE);
    $data['script'] = array(
      'libs/jquery.dataTables',
      'pages/admins/categories/list'
    );
    $data['style'] = array('libs/dataTables.min');
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
      $this->Admin->setPassword($this->encryption->encrypt($post['password']));
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

  public function addRentalShop()
  {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('shop_name', 'Shop Name', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('shop_branch', 'Shop Branch', 'trim|required|xss_clean');
    $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');

    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect('admin/rentalshops/add','refresh');

    else:
      $post = $this->input->post(NULL, TRUE);

      $data = array('shop_name' => $post['shop_name'], 'shop_branch' => $post['shop_branch'], 'address' => $post['address'], 'subscriber_id' => $this->session->userdata('admin_id'));
      $this->RentalShop->create($data);
      $this->session->set_flashdata('success', TRUE);
      redirect('admin/rentalshops/add','refresh');

    endif;
  }

  public function addCategory()
  {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('category', 'Category', 'trim|required|min_length[4]|xss_clean');

    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect('admin/categories/add','refresh');

    else:
      $imageError = $this->_validateImage();
      if (empty($imageError)) {
        $picture = file_get_contents($_FILES['image']['tmp_name']);
        $data = array(
          'category_type' => $this->input->post('category'),
          'category_image' => $picture
        );
        $this->Category->create($data);

        $this->session->set_flashdata('success', TRUE);
        redirect('admin/categories/add','refresh');
      } else {
        $this->session->set_flashdata('error', $imageError);
        redirect('admin/categories/add','refresh');
      }
    endif;
  }

  public function editCategory() {
    /*
    | field name, error message, validation rules
    */
    $this->form_validation->set_rules('category', 'Category', 'trim|required|min_length[4]|xss_clean');
    $id = $this->input->post('id');
    if($this->form_validation->run() == FALSE):

      $this->session->set_flashdata('error', validation_errors());
      redirect("admin/categories/edit/$id",'refresh');

    else:
      $imageError = $this->_validateImage();
      if (empty($imageError)) {
        $picture = file_get_contents($_FILES['image']['tmp_name']);
        $data = array(
          'category_id' => $id,
          'category_type' => $this->input->post('category'),
          'category_image' => $picture
        );
        $this->Category->update($data);
        
        $this->session->set_flashdata('success', TRUE);
        redirect("admin/categories/edit/$id",'refresh');
      } else {
        $this->session->set_flashdata('error', $imageError);
        redirect("admin/categories/add/$id",'refresh');
      }
    endif; 
  }

  private function _validateImage() {
    $message = '';
    if (!empty($_FILES['image']) && $_FILES['image']['size'] != 0) { // check if image has been upload
      $this->load->library('MyFile', $_FILES['image']); // Load My File Library
      $validate = $this->myfile->validateImage(); // Validate Image
      if (!$validate['result']) {
        $message = implode(', ', $validate['message']); // Specify Image validate errors
      }
    } else {
      $message = "Image is required";
    }
    return $message;
  }

  public function reportsSubscriptions() {
    $data = $this->_reportAssets();
    $data['title'] = 'SUBSCRIPTION\'s REPORTS';
    $data['content'] = $this->load->view('pages/admin/reports/subscriptions', '', TRUE);
    array_push($data['script'], 'pages/reports/subscriptions');
    $this->load->view('common/admin', $data);
  }

  public function reportsRentals() {
    $data = $this->_reportAssets();
    $data['title'] = 'RENTAL\'s REPORTS';
    $data['content'] = $this->load->view('pages/admin/reports/rentals', '', TRUE);
    $this->load->view('common/admin', $data);
  }

  public function reportsUsers() {
    $data = $this->_reportAssets();
    $data['title'] = 'User\'s REPORTS';
    $data['content'] = $this->load->view('pages/admin/reports/users', '', TRUE);
    array_push($data['script'], 'pages/reports/users');
    $this->load->view('common/admin', $data);
  }

  public function reportsLessors() {
    $data = $this->_reportAssets();
    $data['title'] = 'LESSOR\'s REPORTS';
    $data['content'] = $this->load->view('pages/admin/reports/lessors', '', TRUE);
    array_push($data['script'], 'pages/reports/lessors');
    $this->load->view('common/admin', $data);
  }

  private function _reportAssets() {
    $data['script'] = array(
      'common',
      'libs/chart.min',
      'libs/moment.min2',
      'libs/daterangepicker',
      'libs/daterange',
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'libs/dataTables.sum'
    );

    $data['style'] = array(
      'style',
      'libs/datepicker',
      'libs/dataTables.min'

    );
    return $data;
  }
}