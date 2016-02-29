<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessees extends CI_Controller
{
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
      $this->load->library('MyPusher');
      $this->Lessee->setId($this->session->userdata('lessee_id'));
      $this->MyShop->setLesseeId($this->session->userdata('lessee_id'));
      $this->MyInterest->setLesseeId($this->session->userdata('lessee_id'));
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
    $data['script']           = array(
      'libs/pnotify.buttons',
      'libs/pnotify.core',
      'pages/lessees/shops'
    );
    $data['style'] = array('libs/pnotify');
    $this->load->view('common/lessee', $data);
  }

  public function myInterestsPage($page = 1) {
    $this->load->library('pagination');
    $this->load->library('RentalModes');

    $offset = ($page - 1) * $this->MyInterest->getLimit();
    $this->MyInterest->setOffset($offset);
    
    // Configuring Pagination
    $config['base_url'] = site_url('lessee/myinterests/');
    $config['total_rows'] = $this->MyInterest->getAllCount();
    $config['per_page'] = $this->Item->getLimit();
    $this->pagination->initialize($config);

    $content['items'] = array_map(
      array(
        $this->Item,
        'mapItemsWithCategory'
      ), $this->MyInterest->all()
    );
    // echo "<pre>";
    // print_r($content['items']);
    // echo "</pre>";
    // exit();
    $data['title'] = 'MY INTERESTS';
    $content['pagination'] = $this->pagination->create_links();
    $content['rentalMode'] = $this->rentalmodes->getModes();
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
    $content['items'] = array_map(array($this->Item, 'mapItemRented'), $items['result']);
    $data['content'] = $this->load->view('pages/lessee/items/rented', $content, TRUE);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/lessees/rented'
    );
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');
    $this->load->view('common/lessee', $data);
  }

  public function messagePage()
  {
      $data['title']      = 'MESSAGE';
      
      $content['myshops'] = $this->Lessee->myInterests();
      $content['lessors'] = $this->Subscriber->all($select = "*",$status="active");
      $content['lessor'] = $this->Subscriber->findId($this->input->get('lessor'));
      $content['isDisable'] = empty($content['lessor']) ? 'disabled' : '';
      $content['message'] = $this->input->get('message');
      
      $data['content'] = $this->load->view('pages/lessee/message', $content, TRUE);
      $data['script'] = array(
        'libs/moment.min2',
        'libs/pnotify.core',
        'libs/pnotify.buttons',
        'libs/select2.min',
        'pages/lessees/message'
      );
      $data['style'] = array('libs/select2.min', 'libs/pnotify');
      $this->load->view('common/lessee', $data);
  }

  public function lessorsList() {
    $this->isAjax();
    $this->load->model('Subscriber');
    $select = array(
      $this->Subscriber->getId(),
      $this->Subscriber->getFname(),
      $this->Subscriber->getLname()
    );
    // $str = ;
    $lessors = $this->Subscriber->all(implode(', ', $select), 'active');
    $data = array_map(array($this, '_selectLessor'), $lessors);
    echo json_encode($data);
  }

  private function _selectLessor($data) {
    return array(
      'id' => $data->subscriber_id,
      'text' => $data->subscriber_lname . ', ' . $data->subscriber_fname
    );
  }

  public function shopsPage($page = 1) {
    $this->load->library('pagination');

    $offset = ($page - 1) * $this->RentalShop->getLimit();
    $this->RentalShop->setOffset($offset); // Setting Rentalshop offset rows
     
    // Configuring Pagination
    $config['base_url'] = site_url('lessee/shops/');
    $config['total_rows'] = $this->RentalShop->allCount();
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
      'libs/pnotify.buttons',
      'libs/pnotify.core',
      'pages/lessees/shops'
    );
    $data['style'] = array('libs/pnotify');
    
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
      $itemId = $this->input->get('id');
      $keyword = $this->input->get('item');
      if (!empty($itemId) && is_numeric($itemId)) {
        $items = $this->Item->all($select = "*", '', '', $itemId);
      } else {
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
      }
      $content['items'] = empty($items['data']) ? 
        array() :
        array_map(array(
            $this->Item,
            'mapItemsWithCategory'
          ), $items['data']
        );
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
        'pages/reservations/singledate',
        'pages/lessees/items'
      );
      $this->load->view('common/lessee', $data);
  }


  public function shopsCategoryPage($categoryId, $page = 1) {
      $this->load->library('pagination');
      $this->load->library('RentalModes');
      $this->load->model('ItemCategory');
      $this->load->model('Category');

      $category = $this->Category->findById($categoryId);

      if (empty($category)) {
        redirect(site_url('/lessees'));
        exit();
      }

      $data['title'] = 'SHOPS BY CATEGORY : ' . $category[$this->Category->getType()];
      $this->ItemCategory->setLimit(8); // Setting Rentalshop offset rows
      $offset = ($page - 1) * $this->ItemCategory->getLimit();
      $this->ItemCategory->setOffset($offset); // Setting Rentalshop offset rows
      $shops = $this->ItemCategory->findShopByCategory($categoryId);
      
      // Configuring Pagination
      $config['base_url'] = site_url("lessee/shops/category/$categoryId/");
      $config['total_rows'] = count($shops['count']);
      $config['per_page'] = $this->Item->getLimit();
      $this->pagination->initialize($config);

      $content['pagination'] = $this->pagination->create_links();
      $content['shops'] = $this->RentalShop->processGroupShop($shops['data'], $categoryId);
      $content['myinterests'] = $this->MyInterest->getMyInterestId();
      $content['action'] = site_url('lessee/add-myinterest');
      $content['rentalMode'] = $this->rentalmodes->getModes();
      $content['category'] = $category;
      $data['content'] = $this->load->view('pages/items/listByCategory', $content, TRUE);
      $data['style'] = array('libs/pnotify', 'libs/dataTables.min');
      $data['script'] = array(
        'libs/pnotify.core',
        'libs/pnotify.buttons',
        'libs/jquery.dataTables',
        'pages/lessees/items'
      );
      $this->load->view('common/lessee', $data); 
  }

  public function reservedPage() {
    $this->load->model('Reservation');
    $this->load->model('ReservationDetail');
    $data['title'] = 'Reservation List';
    $lesseId = $this->session->userdata('lessee_id');
    $reservations = $this->Reservation->findComplete(array(
      'r.'. $this->Reservation->getLesseeId() => $lesseId
    ));
    $content['reservations'] = array_map(array($this->Reservation, 'mapDetailItem'), $reservations);
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
          if($this->mypusher->Message('msg-channel', 'msg-event', $data)):
            echo TRUE;
          else:
            echo FALSE;
          endif;
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
          if($this->mypusher->Message('inbox-channel', 'inbox-event', $data)):
            $this->session->set_flashdata('success', TRUE);
            redirect('lessee/inbox', 'refresh');
          else:
            echo FALSE;
          endif;

      }
  }

  /**
   * @param  {String} $subject notification subject
   * @param  {String} $message notification message
   * @param  {String} $type user type who will be notified
   * @return {Boolean} 
   */
  private function notify($subject,$message,$type)
  {
    $data['subject']  = $subject;
    $data['message']  = $message;
    $data['receiver'] = ($this->session->has_userdata('lessee_id')) ? $this->session->userdata('lessee_id') : $this->session->userdata('lessor_id') ;
    $data['date']     = date("Y/m/d");
    $data['usertype'] = $type;
    if($this->mypusher->Message('notify-channel', 'notify-event', $data)):
      return TRUE;
    else:
      return FALSE;
    endif;

  }

  public function addMyShop()
  {
      $post = $this->input->post(NULL, TRUE);
      $this->MyShop->setMyShopName($post['shop_name']);
      $this->MyShop->setShopId($post['shop_id']);
      if($this->notify('Shop added','Your shop has been added','lessor')):
        echo $this->MyShop->insert();
      else:
        echo FALSE;
      endif;
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
      if($this->notify('Item added','Your item has been added','lessor')):
         echo $this->MyInterest->insert();
      else:
        echo FALSE;
      endif;
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
  
}