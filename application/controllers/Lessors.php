<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessors extends CI_Controller {

	public function __construct() {
      parent::__construct(2);
      $this->load->model('Subscriber');
  }

  public function index() {
  	redirect('lessor/dashboard');
  }

  public function dashboard() {
    $data['title'] = "Lessor Dashboard";
  	$content['subscriber'] = $this->Subscriber->findId($this->session->userdata('lessor_id'));
  	$data['content'] = $this->load->view('pages/lessor/dashboard', $content, TRUE);
  	$this->load->view('common/lessor', $data);
  }

  public function signin() {
  	$post = $this->input->post();
  	if (!empty($post)) {
	  	$username = $post['username'];
	    $password = $post['password'];
	    $usertype = $post['usertype'];
	    $sub = $this->Subscriber;
	    $user = $sub->findUsername($username);
	    if (
	    	!empty( $user ) &&
	    	$this->encryption->decrypt($user[$sub->getPassword()]) == $password
	    ):

	      $userdata = array(
	      	'lessor_id' => $user[$sub->getId()],
	        'lessor_fullname' => $user[$sub->getFname()] . ' ' . $user[$sub->getLname()],
	        'lessor_logged_in' => TRUE
	      );

	      $this->session->set_userdata($userdata);
	      redirect('/lessors/');

	    elseif ($usertype != 'lessor'):

	      $this->session->set_flashdata('warning', TRUE);
	      redirect('lessors/signin-page','refresh');

	    else:
	      $this->session->set_flashdata('error', TRUE);
	      redirect('lessors/signin-page','refresh');

	    endif;
	  } else {
	  	redirect('lessors/signin-page','refresh');
	  }
  }

  public function signinPage() {
		$content['action'] = site_url('lessors/signin');
    $content['isLessor'] = true;
    $data['content'] = $this->load->view('pages/signin', $content, TRUE);
    $data['title'] = 'LESSOR SIGN IN';
    $this->load->view('common/plain', $data);
  }

  public function shopCreate() {
    $data['title'] = "Creating New Shop";
    $content['action'] = "create";
    $data['content'] = $this->load->view('pages/shops/form', $content, true);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/map',
      'pages/create-form',
      'pages/shops/form'
    );
    $data['style'] = array('libs/pnotify');
    $this->load->view('common/lessor', $data);
  }

  public function shopEdit($id) {
    $this->load->model('RentalShop');
    $data['title'] = "Edit Shop";
    $content['action'] = "update";
    $lessorId = $this->session->userdata('lessor_id');
    $rentalShop = $this->RentalShop->findById($id, $lessorId);
    if ($rentalShop) {
      $content['shop'] = $rentalShop;
      $data['content'] = $this->load->view('pages/shops/form', $content, true);
      $data['script'] = array(
        'libs/pnotify.core',
        'libs/pnotify.buttons',
        'libs/map',
        'pages/create-form',
        'pages/shops/form'
      );
      $data['style'] = array('libs/pnotify');
      $this->load->view('common/lessor', $data);
    } else {
      redirect('lessor/shops/list');
    }
  }

  public function shopList() {
    $this->load->model('RentalShop');
    $this->load->library('pagination');

    $data['title'] = "My Shops";
    $lessorId = $this->session->userdata('lessor_id');

    $content['shops'] = $this->RentalShop->findAllBySubscriberId($lessorId);

    $data['content'] = $this->load->view('pages/shops/list', $content, true);
    $data['style'] = array(
      'libs/pnotify',
      'libs/dataTables.min'
    );
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/shops/list'
    );

    $this->load->view('common/lessor', $data);
  }

  public function itemCreate() {
    $this->load->library('rentalmodes');
  	$data['title'] = "Creating New Item";
    $content['action'] = "create";
    $content['rental_modes'] = $this->rentalmodes->getModes();
    $data['content'] = $this->load->view('pages/items/form', $content, true);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/select2.min',
      'pages/create-form',
      'pages/items/form'
    );
    $data['style'] = array(
      'libs/pnotify',
      'libs/select2.min'
    );
    $this->load->view('common/lessor', $data);
  }

  public function itemEdit($id) {
    if (empty($id) || !is_numeric($id)) {
      redirect('lessor/items/list');
      exit();
    }
    $this->load->model('Item');
    $this->load->model('ItemCategory');
    $this->load->library('rentalmodes');
    $data['title'] = "Edit Item";
    $content['action'] = "update";
    $content['item'] = $this->Item->findByIdComplete($id);
    $content['categories'] = $this->ItemCategory->findCategoryByItem($id);
    $content['rental_modes'] = $this->rentalmodes->getModes();
    $data['content'] = $this->load->view('pages/items/form', $content, true);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/select2.min',
      'pages/create-form',
      'pages/items/form'
    );
    $data['style'] = array(
      'libs/pnotify',
      'libs/select2.min'
    );
    $this->load->view('common/lessor', $data);
  }

  public function itemList($page = 1) {
    $this->load->model('Item');
    $this->load->library('pagination');
  	$this->load->library('rentalmodes');

    $data['title'] = "List All Items";
    $lessorId = $this->session->userdata('lessor_id');
    $keyword = $this->input->get('item');
      
    $offset = ($page - 1) * $this->Item->getLimit();
    $this->Item->setOffset($offset); // Setting Rentalshop offset rows
    
    $items = $this->Item->findBySubscriberId($lessorId, $keyword);
    $content['items'] = array_map(array($this, '_mapItems'), $items['data']);
    if (!empty($items['count'])) {
      // Configuring Pagination
      $config['base_url'] = site_url('lessor/items/list/');
      $config['total_rows'] = $items['count']-1;
      $config['per_page'] = $this->Item->getLimit();
      $this->pagination->initialize($config);
      $content['pagination'] = $this->pagination->create_links();
    }
    $content['rental_modes'] = $this->rentalmodes->getModes();

    $data['content'] = $this->load->view('pages/items/list', $content, true);
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'pages/items/list'
    );
    $data['style'] = array('libs/pnotify');
    $this->load->view('common/lessor', $data);
  }

  private function _mapItems($data) {
    $this->load->model('ItemCategory');
    return array(
      'info' => $data,
      'categories' => $this->ItemCategory->findCategoryByItem($data->item_id)
    );
  }

  public function subscriptions() {
    $this->load->model('Subscription');
    $lessorId = $this->session->userdata('lessor_id');
    $content['subscriptions'] = $this->Subscription->findBySubscriberId($lessorId);
    $content['plan'] = $this->Subscription->findActivePlanBySubId($lessorId);
    $data['title'] = 'Subscription Informations';
    $data['content'] = $this->load->view('pages/lessor/subscriptions/informations', $content, TRUE);
    
    $data['style'] = array(
      'libs/price-table',
      'libs/dataTables.min'
    );

    $data['script'] = array(
      'libs/jquery.dataTables',
      'pages/lessor/subscription'
    );

    $this->load->view('common/lessor', $data);
  }

  // RENTALS
  public function rentedItems() {

  }

  public function pendingReserves() {
    $data['title'] = "Pending Reservations";
    $lessorId = $this->session->userdata('lessor_id');
    $content['reservations'] = $this->Subscriber->findReservation($lessorId, 'pending');
    $data['content'] = $this->load->view('pages/lessor/reservations/pending', $content, TRUE);
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/lessor/reservations/list'
    );
    $this->load->view('common/lessor', $data);
  }

  public function approveReserves() {
    $data['title'] = "Approved Reservations";
    $lessorId = $this->session->userdata('lessor_id');
    $content['reservations'] = $this->Subscriber->findReservation($lessorId, 'approve');
    $data['content'] = $this->load->view('pages/lessor/reservations/approve', $content, TRUE);
    $data['style'] = array('libs/dataTables.min', 'libs/pnotify');
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'libs/jquery.dataTables',
      'pages/lessor/reservations/list'
    );
    $this->load->view('common/lessor', $data);    
  }

  public function historyReserves() {

  }

  public function account() {
    $data['title'] = "Account Settings";
    $lessorId = $this->session->userdata('lessor_id');
    $content['subscriber'] = $this->Subscriber->findId($lessorId);
    $data['content'] = $this->load->view('pages/lessor/account', $content, TRUE);
    $data['style'] = array('libs/pnotify');
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'pages/lessor/account'
    );
    $this->load->view('common/lessor', $data); 
  }

  public function accountSave() {
    $this->isAjax();
    
    $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[4]|valid_email|xss_clean');
    $this->form_validation->set_rules('paypal', 'Paypal', 'trim|required|min_length[4]|valid_email|xss_clean');
    $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|xss_clean');
    $this->form_validation->set_rules('password_old', 'Old Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]|xss_clean');
    $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required|matches[password]|xss_clean');
    
    $res['result'] = FALSE;

    if ($this->form_validation->run() != FALSE) {
      $post = $this->input->post();
      
      $lessorId = $this->session->userdata('lessor_id');
      $isEmailExist = $this->Subscriber->isEmailExist($post['email'], $lessorId);
      $isUsernameExist = $this->Subscriber->isUsernameExist($post['username'], $lessorId);
      $isPaypalExist = $this->Subscriber->isPaypalExist($post['paypal'], $lessorId);
      $error = array();

      if ($isEmailExist) { $error[] = 'Email Already Exist'; }
      if ($isUsernameExist) { $error[] = 'Username Already Exist'; }
      if ($isPaypalExist) { $error[] = 'Paypal Already Exist'; }
      if (empty($error)) {
        $lessor = $this->Subscriber->findId($lessorId);
        if ($this->encryption->decrypt($lessor[$this->Subscriber->getPassword()]) == $post['password_old']) {
          $data = array(
            $this->Subscriber->getEmail() => $post['email'],
            $this->Subscriber->getPaypal() => $post['paypal'],
            $this->Subscriber->getUsername() => $post['username'],
            $this->Subscriber->getPassword() => $this->encryption->encrypt($post['password']),
            $this->Subscriber->getId() => $lessorId
          );
          if ($this->Subscriber->update($data)) {
            $res['message'] = 'Account Updated';  
            $res['result'] = TRUE;
          } else {
            $res['message'] = 'Internal Server Error';
          }
        } else {
          $res['message'] = 'Old password is incorrect';
        }
      } else {
        $res['message'] = implode(', ', $error);
      }
    } else {
      $res['message'] = validation_errors();
    }
    echo json_encode($res);
  }

}