<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      $check = $this->isLogin();
      if ($check['isLogin'] && $this->uri->segments[1] != 'logout') {
        redirect($check['typeLogin']);
        exit();
      }
      $this->load->model('Lessee');
      LibsLoader();
  }

  public function index()
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
    $this->load->model('Category');
    $authUrl = $client->createAuthUrl();
    $content['authUrl'] = $authUrl;
    $content['categories'] = $this->Category->random();
    $data['content'] = $this->load->view('pages/main', $content, TRUE);
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
      $post = $this->input->post(NULL, TRUE);

      if ($post['user_type'] != 'lessor') {

        $this->load->model('Lessee');

        $this->Lessee->setFname($post['fname']);
        $this->Lessee->setLname($post['lname']);
        $this->Lessee->setEmail($post['email']);
        $this->Lessee->setPhoneno($post['phoneno']);
        $this->Lessee->setUsername($post['username']);
        $this->Lessee->setPassword($this->encryption->encrypt($post['password']));
        $this->Lessee->insert();
      } else {
        $this->load->model('Subscriber');
        $data = array(
          'username'=> $post['username'],
          'password'=> $this->encryption->encrypt($post['password'])
        );
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

  public function logout()
  {
    $this->session->sess_destroy();
    redirect('/', 'refresh');
  }

  public function listByCategory($category, $page = 1) {
    $this->isAjax();
    $res['result'] = FALSE;
    $post = $this->input->post();
    if (
      !is_numeric($category) ||
      !is_numeric($page)
    ) {
      $res['message'] = 'Invalid Parameter';
    } else {
      $this->load->library('pagination');
      $this->load->model('ItemCategory');

      $this->ItemCategory->setLimit(4); // Setting ItemCategory offset rows
      $offset = ($page - 1) * $this->ItemCategory->getLimit();
      $this->ItemCategory->setOffset($offset); // Setting Rentalshop offset rows
            
      $shops = $this->ItemCategory->findShopByCategory($category);
      $content['shops'] = $this->_processGroupShop($shops['data'], $category);

      // Configuring Pagination
      $config['base_url'] = site_url('main/listByCategory/' + $category + '/');
      $config['total_rows'] = $shops['count'];
      $config['per_page'] = $this->ItemCategory->getLimit();
      $this->pagination->initialize($config);

      $content['pagination'] = $this->pagination->create_links();
      
      if (empty($content['shops'])) {
        $res['message'] = 'No Item found in this category';
      } else {
        $res['result'] = TRUE;
        $res['view'] = $this->load->view('pages/items/listByCategory', $content, TRUE);
      }

    }
    echo json_encode($res);
  }

  private function _processGroupShop($shops, $category) {
    $this->load->model('RentalShop');
    $data = array();
    foreach ($shops as $shop) {
      $item = $this->ItemCategory->findItemByIdAndShop($category, $shop->shop_id);
      $result = $this->RentalShop->findById($shop->shop_id);
      $data[$shop->shop_id]['items'] = array_map(array($this, '_processItem'), $item);
      $data[$shop->shop_id]['detail'] = $this->_processShop($result);
    }
    return $data;
  }

  private function _processShop($obj) {
    if (is_array($obj)) {
      $obj = (Object)$obj;
    }
    $img = $obj->shop_image == NULL ? 
      'http://placehold.it/250x150' :
      'data:image/jpeg;base64,' . base64_encode($obj->shop_image);
    return array(
      $this->RentalShop->getName() => $obj->shop_name,
      $this->RentalShop->getBranch() => $obj->shop_branch,
      $this->RentalShop->getImage() => $img,
      $this->RentalShop->getAddress() => $obj->address,
      $this->RentalShop->getSubscriberId() => $obj->subscriber_id
    );
  }
  private function _processItem($obj) {
    if (is_array($obj)) {
      $obj = json_decode(json_encode($obj), FALSE);
    }
    $img = $obj->item_pic == NULL ? 'http://placehold.it/250x150' : 'data:image/jpeg;base64,' . base64_encode($obj->item_pic);
    $this->load->library('RentalModes');
    $this->load->library('Item');

    return array(
      $this->Item->getId() => $obj->item_id,
      $this->Item->getRate() => $obj->item_rate,
      $this->Item->getPic() => $img,
      $this->Item->getStatus() => $obj->item_stats,
      $this->Item->getQty() => $obj->item_qty,
      $this->Item->getDesc() => $obj->item_desc,
      $this->Item->getCashBond() => $obj->item_cash_bond,
      $this->Item->getRentalMode() => $obj->item_rental_mode,
      $this->Item->getPenalty() => $obj->item_penalty,
      $this->Item->getShopId() => $obj->shop_id,
      $this->Item->getSubscriberId() => $obj->subscriber_id,
      $this->Item->getName() => $obj->item_name,
      'mode_label' => $this->rentalmodes->getMode($obj->item_rental_mode)
    );
  }


}
