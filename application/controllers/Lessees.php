<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessees extends CI_Controller {

  public function __construct()
  {
      parent::__construct(1);
      $this->load->model('Lessee');
      $this->load->model('RentalShop');
      $this->load->model('Item');
      $this->load->model('MyShops');
      $this->Lessee->setId($this->session->userdata('lessee_id'));
      $this->MyShops->setLesseeId($this->session->userdata('lessee_id'));
      LibsLoader();
  }

  public function index()
  {
    $data['title'] = 'DASHBOARD';
    $data['content'] = $this->load->view('pages/lessee/dashboard', '', TRUE);
    //$this->output->cache(1);
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
        redirect('lessee/dashboard');

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
    /* Store values in variables from project created in Google Developer Console */
    $client_id = '420762774972-sng0pvjsvaht2f4aq86qk65j9317qrs7.apps.googleusercontent.com';
    $client_secret = 'jFJJi-BKnEwKUcA6jaCglXq4';
    $redirect_uri = 'http://localhost/rentrmnl/';
    $simple_api_key = 'AIzaSyBWWornRguaHPgQJFRn74qHQD3ZxbelM_Q';

    /* Create Client Request to access Google API */
    $client = new Google_Client();
    $client->setApplicationName("renTRMNL");
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->setDeveloperKey($simple_api_key);
    $client->addScope("https://www.googleapis.com/auth/userinfo.email");
    $client->setApprovalPrompt('auto');

    /* Send Client Request */
    $objOAuthService = new Google_Service_Oauth2($client);
    /* Add Access Token to Session */
    if (isset($_GET['code'])):
      $client->authenticate($_GET['code']);
      /* Get User Data from Google  */
      $user = $objOAuthService->userinfo->get();
      $result = $this->Lessee->googleLogin($user);

      if(is_array($result)):
        $userdata = array('lessee_id' => $result['lessee_id'],
                          'username' => $result['username'],
                          'lessee_fname' => $result['lessee_fname'],
                          'lessee_lname' => $result['lessee_lname'],
                          'lessee_email' => $result['lessee_email'],
                          'lessee_phoneno' => $result['lessee_phoneno'],
                          'image' => $user['picture'],
                          'access_token' => $client->getAccessToken(),
                          'logged_in' => TRUE);
      else:
        $userdata = array('lessee_id' => $result,
                          'username' => $user['id'],
                          'lessee_fname' => $user['givenName'],
                          'lessee_lname' => $user['familyName'],
                          'lessee_email' => $user['email'],
                          'lessee_phoneno' => "",
                          'image' => $user['picture'],
                          'access_token' => $client->getAccessToken(),
                          'logged_in' => TRUE);

      endif;
      $this->session->set_userdata($userdata);
      redirect('lessee/dashboard');
    endif;

    /* Set Access Token to make Request */
    if ($this->session->has_userdata('access_token')):
      $client->setAccessToken($this->session->userdata('access_token'));
    endif;

    if ($client->getAccessToken()):
      $this->session->set_userdata('access_token', $client->getAccessToken());
      redirect('lessee/dashboard');
    endif;

    $authUrl = $client->createAuthUrl();
    $content['authUrl'] = $authUrl;

    $content['action'] = site_url('lessees/signin');
    $data['content'] = $this->load->view('pages/signin', $content, TRUE);
    $data['title'] = 'SIGN IN';
    //$this->output->cache(1);
    $this->load->view('common/plain', $data);
  }

  public function profilePage()
  {
    //$content['action'] = site_url('signin');
    $content['lessee'] = $this->Lessee->findById();
    $data['content'] = $this->load->view('pages/lessee/profile', $content, TRUE);
    $data['title'] = 'MY PROFILE';
    //$this->output->cache(1);
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

  public function myShopsPage()
  {
    $data['title'] = 'MY SHOPS';
    $content['myshops'] = $this->MyShops->all();
    $data['content'] = $this->load->view('pages/lessee/myshops', $content, TRUE);
    //$this->output->cache(1);
    $this->load->view('common/lessee', $data);
  }

  public function myInterestsPage()
  {
    $data['title'] = 'MY INTERESTS';
    $content['myshops'] = $this->Lessee->myInterests();
    $data['content'] = $this->load->view('pages/lessee/myinterests', $content, TRUE);
    //$this->output->cache(1);
    $this->load->view('common/lessee', $data);
  }

  public function inboxPage()
  {
    $data['title'] = 'INBOX';
    $content['myshops'] = $this->Lessee->myInterests();
    $data['content'] = $this->load->view('pages/lessee/inbox', $content, TRUE);
    //$this->output->cache(1);
    $this->load->view('common/lessee', $data);
  }

  public function shopsPage()
  {
    $data['title'] = 'SHOPS';
    $content['shops'] = $this->RentalShop->all($select = "*");
    $content['myshops'] = $this->MyShops->getMyShopsId();
    $content['action'] = site_url('lessee/add-myshop');
    $data['content'] = $this->load->view('pages/lessee/categories/shops', $content, TRUE);
    $data['script'] = array('pages/lessees/shops');
    //$this->output->cache(1);
    $this->load->view('common/lessee', $data);
  }

  public function gownsPage()
  {
    $data['title'] = 'GOWNS';
    $content['items'] = $this->Item->all($select = "*");
    $data['content'] = $this->load->view('pages/lessee/categories/gowns', $content, TRUE);
    //$this->output->cache(1);
    $this->load->view('common/lessee', $data);
  }

  public function sendMessage()
  {
    $app_id = '163140';
    $app_key = 'b3c7fc474d668cd4563e';
    $app_secret = '221d49143b9fcdd747ef';

    $pusher = new Pusher(
      $app_key,
      $app_secret,
      $app_id,
      array('encrypted' => true)
    );

    if($_POST){
      $data['subject'] = $this->input->post('subject');
      $data['message'] = $this->input->post('message');
      $data['receiver'] = $this->input->post('receiver');
      $data['date'] = date("Y/m/d");
      $pusher->trigger('msg_channel', 'onMessage', $data);
    }

    redirect('lessee/inbox','refresh');
  }

  public function addMyShop()
  {
    $post = $this->input->post(NULL, TRUE);
    $this->MyShops->setMyShopName($post['shop_name']);
    $this->MyShops->setShopId($post['shop_id']);
    echo $this->MyShops->insert();
  }

}
