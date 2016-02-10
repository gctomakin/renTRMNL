<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessees extends CI_Controller
{
  private $app_id     = '163140';
  private $app_key    = 'b3c7fc474d668cd4563e';
  private $app_secret = '221d49143b9fcdd747ef';
  private $pusher;

  public function __construct()
  {
      parent::__construct(1);
      LibsLoader();
      $this->load->model('Lessee');
      $this->load->model('RentalShop');
      $this->load->model('Item');
      $this->load->model('MyShop');
      $this->load->model('MyInterest');
      $this->load->model('Category');
      $this->load->model('Subscriber');
      $this->Lessee->setId($this->session->userdata('lessee_id'));
      $this->MyShop->setLesseeId($this->session->userdata('lessee_id'));
      $this->MyInterest->setLesseeId($this->session->userdata('lessee_id'));
      $this->pusher = new Pusher($this->app_key, $this->app_secret, $this->app_id);
  }

  public function index()
  {
      $data['title']   = 'DASHBOARD';
      $content['categories'] = $this->Category->all($select = "*", $like = "");
      $data['content'] = $this->load->view('pages/lessee/dashboard', $content, TRUE);
      $this->load->view('common/lessee', $data);
  }

  public function signin()
  {
      $usertype = $this->input->post('usertype', TRUE);
      $this->Lessee->setUsername($this->input->post('username', TRUE));
      $this->Lessee->setPassword($this->input->post('password', TRUE));
      $user = $this->Lessee->authenticate();

      if (!empty($user)):
          if ($usertype == 'lessee'):
              $userdata = array(
                  'lessee_id' => $user['lessee_id'],
                  'username' => $user['username'],
                  'lessee_fname' => $user['lessee_fname'],
                  'lessee_lname' => $user['lessee_lname'],
                  'lessee_email' => $user['lessee_email'],
                  'lessee_phoneno' => $user['lessee_phoneno'],
                  'image' => $user['image'],
                  'logged_in' => TRUE
              );
              $this->session->set_userdata($userdata);
              redirect('lessee/dashboard');
          else:
              $this->session->set_flashdata('warning', TRUE);
              redirect('lessees/signin-page', 'refresh');
          endif;
      else:
          $this->session->set_flashdata('error', TRUE);
          redirect('lessees/signin-page', 'refresh');
      endif;
  }

  public function signinPage()
  {
      /* Store values in variables from project created in Google Developer Console */
      $client_id      = '420762774972-sng0pvjsvaht2f4aq86qk65j9317qrs7.apps.googleusercontent.com';
      $client_secret  = 'jFJJi-BKnEwKUcA6jaCglXq4';
      $redirect_uri   = 'http://localhost/rentrmnl/';
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
          $user   = $objOAuthService->userinfo->get();
          $result = $this->Lessee->googleLogin($user);

          if (is_array($result)):
              $userdata = array(
                  'lessee_id' => $result['lessee_id'],
                  'username' => $result['username'],
                  'lessee_fname' => $result['lessee_fname'],
                  'lessee_lname' => $result['lessee_lname'],
                  'lessee_email' => $result['lessee_email'],
                  'lessee_phoneno' => $result['lessee_phoneno'],
                  'image' => $user['picture'],
                  'access_token' => $client->getAccessToken(),
                  'logged_in' => TRUE
              );
          else:
              $userdata = array(
                  'lessee_id' => $result,
                  'username' => $user['id'],
                  'lessee_fname' => $user['givenName'],
                  'lessee_lname' => $user['familyName'],
                  'lessee_email' => $user['email'],
                  'lessee_phoneno' => "",
                  'image' => $user['picture'],
                  'access_token' => $client->getAccessToken(),
                  'logged_in' => TRUE
              );
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

      $authUrl            = $client->createAuthUrl();
      $content['authUrl'] = $authUrl;

      $content['action'] = site_url('lessees/signin');
      $data['content']   = $this->load->view('pages/signin', $content, TRUE);
      $data['title']     = 'SIGN IN';
      $this->load->view('common/plain', $data);
  }

  public function profilePage()
  {
      //$content['action'] = site_url('signin');
      $content['lessee'] = $this->Lessee->findById();
      $data['content']   = $this->load->view('pages/lessee/profile', $content, TRUE);
      $data['title']     = 'MY PROFILE';
      $this->load->view('common/lessee', $data);
  }

  public function updateInfo()
  {
      $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|xss_clean');
      $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|xss_clean');
      $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
      $this->form_validation->set_rules('phoneno', 'Phone No', 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE):
          $this->session->set_flashdata('ui_val_error', validation_errors());
          redirect('lessee/profile', 'refresh');
      else:
          $post = $this->input->post(NULL, TRUE);
          $this->Lessee->setFname($post['fname']);
          $this->Lessee->setLname($post['lname']);
          $this->Lessee->setEmail($post['email']);
          $this->Lessee->setPhoneno($post['phoneno']);
          $result = $this->Lessee->updateInfo();
          if ($result):
              $this->session->set_flashdata('ui_success', TRUE);
              redirect('lessee/profile', 'refresh');
          else:
              $this->session->set_flashdata('ui_error', TRUE);
              redirect('lessee/profile', 'refresh');
          endif;
      endif;

  }

  public function updateAccount()
  {
      $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|xss_clean');
      $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
      $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
      $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[new_password]|xss_clean');

      if ($this->form_validation->run() == FALSE):
          $this->session->set_flashdata('ua_val_error', validation_errors());
          redirect('lessee/profile', 'refresh');
      else:
          $post = $this->input->post(NULL, TRUE);
          $this->Lessee->setUsername($post['username']);
          $this->Lessee->setPassword($post['password']);
          if ($this->Lessee->checkPassword()):
              $this->Lessee->setPassword($this->encryption->encrypt($post['new_password']));
              $result = $this->Lessee->updateAccount();
          else:
              $this->session->set_flashdata('warning', TRUE);
              redirect('lessee/profile', 'refresh');
          endif;

          if ($result):
              $this->session->set_flashdata('ua_success', TRUE);
              redirect('lessee/profile', 'refresh');
          else:
              $this->session->set_flashdata('ua_error', TRUE);
              redirect('lessee/profile', 'refresh');
          endif;
      endif;
  }

  public function upload()
  {
      $config['upload_path']   = './uploads/';
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size']      = 100;
      $config['max_width']     = 1024;
      $config['max_height']    = 768;
      $config['encrypt_name']  = true;

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('userfile')):
          $this->session->set_flashdata('upload_error', $this->upload->display_errors());
          redirect('lessee/profile', 'refresh');
      else:
          $file = $this->upload->data();
          $this->Lessee->setImage($file['file_name']);
          $this->Lessee->uploadImage();
          @unlink(FCPATH . '/uploads/' . $this->session->userdata('image'));
          $this->session->set_userdata('image', $file['file_name']);
          $this->session->set_flashdata('upload_success', TRUE);
          redirect('lessee/profile', 'refresh');
      endif;
  }

  public function myShopsPage($page = 1) {
    $this->load->library('pagination');

    $offset = ($page - 1) * $this->MyShop->getLimit();
    $this->MyShop->setOffset($offset); // Setting Rentalshop offset rows
    
    // Configuring Pagination
    $config['base_url'] = site_url('lessee/myshops/');
    $config['total_rows'] = $this->MyShop->allCount() - 1;
    $config['per_page'] = $this->Item->getLimit();
    $this->pagination->initialize($config);

    $data['title']      = 'MY SHOPS';
    $content['pagination'] = $this->pagination->create_links();
    $content['myshops'] = $this->MyShop->all();
    $content['getShopsJson']  = site_url('lessee/getshops');
    $data['content']    = $this->load->view('pages/lessee/myshops', $content, TRUE);
    $data['script']     = array(
        'pages/lessees/shops'
    );
    $this->load->view('common/lessee', $data);
  }

  public function myInterestsPage($page = 1) {
    $this->load->library('pagination');

    $offset = ($page - 1) * $this->MyInterest->getLimit();
    $this->MyInterest->setOffset($offset);
    
    // Configuring Pagination
    $config['base_url'] = site_url('lessee/myinterests/');
    $config['total_rows'] = $this->MyInterest->getAllCount();
    $config['per_page'] = $this->Item->getLimit();
    $this->pagination->initialize($config);

    $data['title'] = 'MY INTERESTS';
    $content['pagination'] = $this->pagination->create_links();
    $content['myinterests'] = $this->MyInterest->all();
    $data['content'] = $this->load->view('pages/lessee/myinterests', $content, TRUE);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/moment.min2',
      'libs/daterangepicker',
      'pages/reservations/daterange',
      'pages/lessees/items'
    );
    $data['style'] = array('libs/pnotify', 'libs/datepicker');
    $this->load->view('common/lessee', $data);
  }

  public function itemRentPage() {
    $this->load->model('Reservation');

    $lesseeId = $this->session->userdata('lessee_id');
    $items = $this->Reservation->findActiveItemByLesseeId($lesseeId);
    
    $data['title'] = 'ITEM CURRENTLY RENTED';
    $content['items'] = array_map(array($this, '_mapItemRented'), $items['result']);
    $data['content'] = $this->load->view('pages/lessee/items/rented', $content, TRUE);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/lessees/items'
    );
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');
    $this->load->view('common/lessee', $data);
  }

  public function inboxPage()
  {
      $data['title']      = 'INBOX';
      $content['myshops'] = $this->Lessee->myInterests();
      $content['lessors'] = $this->Subscriber->all($select = "*",$status="active");
      $data['content']    = $this->load->view('pages/lessee/inbox', $content, TRUE);
      $this->load->view('common/lessee', $data);
  }

  public function shopsPage($page = 1) {
    $this->load->library('pagination');

    $offset = ($page - 1) * $this->RentalShop->getLimit();
    $this->RentalShop->setOffset($offset); // Setting Rentalshop offset rows
     
    // Configuring Pagination
    $config['base_url'] = site_url('lessee/shops/');
    $config['total_rows'] = $this->RentalShop->allCount() - 1;
    $config['per_page'] = $this->Item->getLimit();
    $this->pagination->initialize($config);

      
    $data['title']            = 'SHOPS';
    $content['pagination']    = $this->pagination->create_links();
    $content['shops']         = $this->RentalShop->all($select = "*");
    $content['myshops']       = $this->MyShop->getMyShopsId();
    $content['action']        = site_url('lessee/add-myshop');
    $content['getShopsJson']  = site_url('lessee/getshops');
    $data['content']          = $this->load->view('pages/lessee/categories/shops', $content, TRUE);
    $data['script']           = array(
        'pages/lessees/shops'
    );
    $this->load->view('common/lessee', $data);
  }

  public function shopsJson()
  {
    $result = $this->RentalShop->all($select = "*");
    echo json_encode($result);
  }

  public function itemsPage($page = 1) {
      $this->load->library('pagination');
      $this->load->library('RentalModes');

      $data['title'] = 'ITEMS';
      $keyword = $this->input->get('item');
      $this->Item->setLimit(8); // Setting Rentalshop offset rows
      $offset = ($page - 1) * $this->Item->getLimit();
      $this->Item->setOffset($offset); // Setting Rentalshop offset rows
      $items = $this->Item->all($select = "*", '', $keyword);
      
      // Configuring Pagination
      $config['base_url'] = site_url('lessee/items/');
      $config['total_rows'] = $items['count'];
      $config['per_page'] = $this->Item->getLimit();
      $this->pagination->initialize($config);

      $content['pagination'] = $this->pagination->create_links();
      $content['items'] = array_map(array($this, '_mapItems'), $items['data']);
      $content['myinterests'] = $this->MyInterest->getMyInterestId();
      $content['action'] = site_url('lessee/add-myinterest');
      $content['rentalMode'] = $this->rentalmodes->getModes();
      $data['content'] = $this->load->view('pages/lessee/categories/items', $content, TRUE);
      $data['style'] = array('libs/pnotify', 'libs/datepicker');
      $data['script'] = array(
        'libs/pnotify.core',
        'libs/pnotify.buttons',
        'libs/moment.min2',
        'libs/daterangepicker',
        'pages/reservations/daterange',
        'pages/lessees/items'
      );
      $this->load->view('common/lessee', $data);
  }


  public function itemsCategoryPage($categoryId, $page = 1) {
      $this->load->library('pagination');
      $this->load->library('RentalModes');
      $this->load->model('Category');

      $category = $this->Category->findById($categoryId);

      if (empty($category)) {
        redirect(site_url('/lessees'));
        exit();
      }

      $data['title'] = 'ITEMS BY CATEGORY : ' . $category[$this->Category->getType()];
      $offset = ($page - 1) * $this->Item->getLimit();
      $this->Item->setOffset($offset); // Setting Rentalshop offset rows
      $this->Item->setLimit(8); // Setting Rentalshop offset rows
      $items = $this->Item->findByCategory($categoryId);
      
      // Configuring Pagination
      $config['base_url'] = site_url("lessee/items/category/$categoryId/");
      $config['total_rows'] = $items['count']-1;
      $config['per_page'] = $this->Item->getLimit();
      $this->pagination->initialize($config);

      if (!empty($items['count'])) {
        $content['pagination'] = $this->pagination->create_links();
      }
      $content['items'] = array_map(array($this, '_mapItems'), $items['data']);
      $content['myinterests'] = $this->MyInterest->getMyInterestId();
      $content['action'] = site_url('lessee/add-myinterest');
      $content['rentalMode'] = $this->rentalmodes->getModes();
      $data['content'] = $this->load->view('pages/lessee/categories/items', $content, TRUE);
      $data['style'] = array('libs/pnotify');
      $data['script'] = array(
        'libs/pnotify.core',
        'libs/pnotify.buttons',
        'pages/lessees/items'
      );
      $this->load->view('common/lessee', $data); 
  }

  public function reservedPage() {
    $this->load->model('Reservation');
    $data['title'] = 'Item Reserved';
    $lesseId = $this->session->userdata('lessee_id');
    $this->Reservation->setLesseeId($lesseId);
    $content['reservations'] = $this->Reservation->find();
    $data['content'] = $this->load->view('pages/lessee/categories/reservations', $content, TRUE);
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');  
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',  
      'libs/jquery.dataTables',
      'pages/lessees/reservations'
    );
    $this->load->view('common/lessee', $data);
  }

  public function sendMessage()
  {
      if ($_POST) {
          $data['subject']  = $this->input->post('subject');
          $data['message']  = $this->input->post('message');
          $data['receiver'] = $this->input->post('receiver');
          $data['usertype'] = $this->input->post('user-type');
          $data['date']     = date("Y/m/d");
          $this->pusher ->trigger('msg-channel', 'msg-event', $data);
          echo TRUE;
      }
  }

  public function sendInbox()
  {   
      if ($_POST) {
          $data['subject']  = 'New message - '.$this->input->post('subject');
          $data['message']  = $this->input->post('message');
          $data['receiver'] = $this->input->post('receiver');
          $data['usertype'] = $this->input->post('user-type');
          $data['date']     = date("Y/m/d");
          $this->pusher ->trigger('inbox-channel', 'inbox-event', $data);
             $this->session->set_flashdata('success', TRUE);
          redirect('lessee/inbox', 'refresh');
      }
  }


  private function notify($subject,$message,$type)
  {
    $data['subject']  = $subject;
    $data['message']  = $message;
    $data['receiver'] = ($this->session->has_userdata('lessee_id')) ? $this->session->userdata('lessee_id') : $this->session->userdata('lessor_id') ;
    $data['date']     = date("Y/m/d");
    $data['usertype'] = $type;
    $this->pusher ->trigger('notify-channel', 'notify-event', $data);
  }

  public function addMyShop()
  {
      $post = $this->input->post(NULL, TRUE);
      $this->MyShop->setMyShopName($post['shop_name']);
      $this->MyShop->setShopId($post['shop_id']);
      $this->notify('Shop has been added','Your shop has been added','lessor');
      echo $this->MyShop->insert();
  }

  public function removeMyShop($id)
  {
    $this->MyShop->setId($id);
    $result = $this->MyShop->delete();

    if ($result):
        $this->session->set_flashdata('success', TRUE);
        redirect('lessee/myshops', 'refresh');
    else:
        $this->session->set_flashdata('error', TRUE);
        redirect('lessee/myshops', 'refresh');
    endif;
  }


  public function addMyInterest()
  {
      $post = $this->input->post(NULL, TRUE);
      $this->MyInterest->setMyInterestName($post['interest_name']);
      $this->MyInterest->setItemId($post['item_id']);
      $this->notify('Item has been added','Your item has been added','lessor');
      echo $this->MyInterest->insert();
  }

  public function removeMyInterest($id)
  {
    $this->MyInterest->setId($id);
    $result = $this->MyInterest->delete();

    if ($result):
        $this->session->set_flashdata('success', TRUE);
        redirect('lessee/myinterests', 'refresh');
    else:
        $this->session->set_flashdata('error', TRUE);
        redirect('lessee/myinterests', 'refresh');
    endif;
  }

  private function _mapItems($data) {
    $this->load->model('ItemCategory');
    return array(
      'info' => $data,
      'categories' => $this->ItemCategory->findCategoryByItem($data->item_id)
    );
  }

  private function _mapItemRented($item) {
    if (!empty($item)) {
      return array (
        'rental_amt' => $item->rental_amt,
        'item_rate' => $item->item_rate,
        'qty' => $item->qty,
        'item_id' => $item->item_id,
        'item_pic' => $item->item_pic == NULL ? 'http://placehold.it/320x150' : 'data:image/jpeg;base64,' . base64_encode($item->item_pic),
        'item_desc' => $item->item_desc,
        'shop_id' => $item->shop_id,
        'reserve_by' => $item->lessee_fname . ' ' . $item->lessee_lname,
        'duration' => date('M d, Y', strtotime($item->date_rented)) . ' - ' . date('M d, Y', strtotime($item->date_returned)),
        'reserve_id' => $item->reserve_id,
        'shop' => isset($item->shop_id) ? $item->shop_name . ' - ' . $item->shop_branch : '---'
      );
    } else {
      return NULL;
    }
  }
}