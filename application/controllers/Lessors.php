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
    $this->load->model('Reservation');
    $this->load->model('RentalShop');
    $this->load->model('Item');
    
    $data['title'] = "Dashboard";
    $lessorId = $this->session->userdata('lessor_id');
  	
    $content['subscriber'] = $this->Subscriber->findId($lessorId);
    
    $content['subscription'] = $this->Subscription->findActiveBySubscriberId($lessorId);
    
    $this->RentalShop->setLimit(5);
    $content['shops'] = $this->RentalShop->findBySubscriberId($lessorId, '', "DESC");
    
    $reservations = $this->Reservation->findActiveItemBySubscriberId($lessorId);
    $content['rentedItems'] = array_map(array($this, '_mapItemRented'), $reservations['result']);
  	
    $this->Item->setLimit(5);
    $items = $this->Item->findBySubscriberId($lessorId, '', "DESC");
    $content['items'] = $items['data'];

    $this->load->library('RentalModes');
    $content['modes'] = $this->rentalmodes->getModes();

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
    $data = $this->_commonAsset();
    $data['title'] = "Creating New Shop";
    $content['action'] = "create";
    $data['content'] = $this->load->view('pages/shops/form', $content, true);
    $data['script'][] = 'libs/map';
    $data['script'][] = 'pages/create-form';
    $data['script'][] = 'pages/shops/form';
    $this->load->view('common/lessor', $data);
  }

  public function shopEdit($id) {
    $this->load->model('RentalShop');
    $data = $this->_commonAsset();
    $data['title'] = "Edit Shop";
    $content['action'] = "update";
    $lessorId = $this->session->userdata('lessor_id');
    $rentalShop = $this->RentalShop->findById($id, $lessorId);
    if ($rentalShop) {
      $content['shop'] = $rentalShop;
      $data['content'] = $this->load->view('pages/shops/form', $content, true);
      $data['script'][] = 'libs/map';
      $data['script'][] = 'pages/create-form';
      $data['script'][] = 'pages/shops/form';
      $this->load->view('common/lessor', $data);
    } else {
      redirect('lessor/shops/list');
    }
  }

  public function shopList() {
    $this->load->model('RentalShop');
    $this->load->library('pagination');

    $data = $this->_commonListAsset();
    $data['title'] = "My Shops";
    $lessorId = $this->session->userdata('lessor_id');

    $content['shops'] = $this->RentalShop->findAllBySubscriberId($lessorId);

    $data['content'] = $this->load->view('pages/shops/list', $content, true);
    $data['script'][] = 'pages/shops/list';

    $this->load->view('common/lessor', $data);
  }

  public function itemCreate() {
    $this->load->library('rentalmodes');
  	$data = $this->_formAsset();
    $data['title'] = "Creating New Item";
    $content['action'] = "create";
    $content['rental_modes'] = $this->rentalmodes->getModes();
    $data['content'] = $this->load->view('pages/items/form', $content, true);
    
    $this->load->view('common/lessor', $data);
  }

  public function itemEdit($id) {
    $this->load->model('Item');
    $lessorId = $this->session->userdata('lessor_id');
    $content['item'] = $this->Item->findByIdComplete($id, $lessorId);
    if (empty($content['item'])) {
      redirect('lessor/items/list');
      exit();
    }
    $this->load->model('ItemCategory');
    $this->load->library('rentalmodes');
    $data = $this->_formAsset();
    $data['title'] = "Edit Item";
    $content['action'] = "update";
    $content['categories'] = $this->ItemCategory->findCategoryByItem($id);
    $content['rental_modes'] = $this->rentalmodes->getModes();
    $data['content'] = $this->load->view('pages/items/form', $content, true);
      
    $this->load->view('common/lessor', $data);
  }

  public function itemReport() {
    $this->load->model('Item');
    $lessorId = $this->session->userdata('lessor_id');
    $data = $this->_commonListAsset();
    $data['title'] = "Item Report";
    $content['items'] = $this->Item->findRentedReport($lessorId);
    $data['content'] = $this->load->view('pages/items/report', $content, true);
    $data['script'][] = 'pages/items/report';
    $this->load->view('common/lessor', $data);
  }

  private function _formAsset() {
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
    return $data;
  }

  public function itemList($page = 1) {
    $this->load->model('Item');
    $this->load->library('pagination');
    $this->load->library('rentalmodes');

    $data = $this->_commonAsset();
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
      $config['total_rows'] = $items['count'];
      $config['per_page'] = $this->Item->getLimit();
      $this->pagination->initialize($config);
      $content['pagination'] = $this->pagination->create_links();
    }
    $content['rental_modes'] = $this->rentalmodes->getModes();

    $data['content'] = $this->load->view('pages/items/list', $content, true);
    $data['script'][] = 'pages/items/list';
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
    $data = $this->_commonListAsset();
    $this->load->model('Subscription');
    $lessorId = $this->session->userdata('lessor_id');
    $content['subscriptions'] = $this->Subscription->findBySubscriberId($lessorId);
    $content['plan'] = $this->Subscription->findActivePlanBySubId($lessorId);
    $data['title'] = 'Subscription Informations';
    $data['content'] = $this->load->view('pages/lessor/subscriptions/informations', $content, TRUE);
    
    $data['style'][] = 'libs/price-table';
    $data['script'][] = 'pages/lessor/subscription';

    $this->load->view('common/lessor', $data);
  }

  // RENTALS
  public function rentedItems($page = 1) {
    $this->load->library('pagination');
    $this->load->model('Reservation');

    $data = $this->_commonListAsset();
    $data['title'] = "ITEM CURRENTLY RENTED";
    
    $lessorId = $this->session->userdata('lessor_id');
    
    $offset = ($page - 1) * $this->Reservation->getLimit();
    $this->Reservation->setOffset($offset); // Setting Reservation offset rows

    $reservations = $this->Reservation->findActiveItemBySubscriberId($lessorId);
    
    $config['base_url'] = site_url('lessor/items/rented');
    $config['total_rows'] = $reservations['count'];
    $config['per_page'] = $this->Reservation->getLimit();
    $this->pagination->initialize($config);

    $content['pagination'] = $this->pagination->create_links();
    $content['items'] = array_map(array($this, '_mapItemRented'), $reservations['result']);    
    
    $data['content'] = $this->load->view('pages/lessor/reservations/activeItems', $content, TRUE);
    $this->load->view('common/lessor', $data);
  }

  // Signup
  public function signup() {
    $this->load->model('SubscriptionPlan');
    $content['plans'] = $this->SubscriptionPlan->all();
    $data['content'] = $this->load->view('pages/lessor/signup', $content, true);
    $data['style'] = array(
      'libs/price-table',
      'libs/form-wizard',
      'libs/pnotify'
    );
    $data['script'] = array(
      'libs/jquery.smartWizard',
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'common',
      'pages/lessor/signup'
    );
    $this->load->view('common/plain', $data);
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

  public function pendingReserves() {
    $data = $this->_commonListAsset();
    $data['title'] = "Pending Reservations";
    $lessorId = $this->session->userdata('lessor_id');
    $content['reservations'] = $this->Subscriber->findReservation($lessorId, 'pending', "r.*, l.*, rs.*, r.status as 'rent_status'");
    $data['content'] = $this->load->view('pages/lessor/reservations/pending', $content, TRUE);
    $data['script'][] = 'pages/lessor/reservations/list';
    $this->load->view('common/lessor', $data);
  }

  public function approveReserves() {
    $data = $this->_commonListAsset();
    $data['title'] = "Approved Reservations";
    $lessorId = $this->session->userdata('lessor_id');
    $content['reservations'] = $this->Subscriber->findReservation($lessorId, array('approve', 'rent', 'return'), "r.*, l.*, rs.*, r.status as 'rent_status'");
    $data['content'] = $this->load->view('pages/lessor/reservations/approve', $content, TRUE);
    $data['script'][] = 'pages/lessor/reservations/list';

    $this->load->view('common/lessor', $data);    
  }

  public function reservations($id) {
    $this->load->model('Reservation');
    $data = $this->_commonListAsset();
    $data['title'] = "Reservation";
    $content['reservations'] = $this->Reservation->findComplete("r.reserve_id = $id");
    $data['content'] = $this->load->view('pages/lessor/reservations/approve', $content, TRUE);
    $data['script'][] = 'pages/lessor/reservations/list';
    $this->load->view('common/lessor', $data); 
  }

  public function pendingPayments() {
    $this->load->model('RentalPayment');
    $data = $this->_commonListAsset();
    $data['title'] = "Pending Payment";
    $lessorId = $this->session->userdata('lessor_id');
    $content['payments'] = $this->RentalPayment->findPendingByLessorId($lessorId, 'pending');
    $data['content'] = $this->load->view('pages/lessor/reservations/pendingPayment', $content, TRUE);
    $data['script'][] = 'pages/lessor/payments/list';
    $this->load->view('common/lessor', $data);
  }

  public function historyRental() {
    $this->load->model('Reservation');
    $data = $this->_commonListAsset();
    $data['title'] = "RENTAL HISTORY";
    $lessorId = $this->session->userdata('lessor_id');
    $content['reservations'] = $this->Reservation->findBySubscriberId($lessorId, 'close');
    $data['content'] = $this->load->view('pages/lessor/reservations/history', $content, TRUE);
    $data['script'][] = 'pages/lessor/reservations/list';
    $this->load->view('common/lessor', $data);
  }

  public function account() {
    $data = $this->_commonAsset();
    $data['title'] = "Account Settings";
    $lessorId = $this->session->userdata('lessor_id');
    $content['subscriber'] = $this->Subscriber->findId($lessorId);
    $data['content'] = $this->load->view('pages/lessor/account', $content, TRUE);
    $data['script'][] = 'pages/lessor/account';

    $this->load->view('common/lessor', $data); 
  }

  public function pendingSubscription() {
    $this->load->model('Subscriber');
    $lessorId = $this->session->userdata('lessor_id');
    $lessor = $this->Subscriber->findId($lessorId);

    if ($lessor[$this->Subscriber->getStatus()] == 'active') {
      redirect('/lessor/dashboard', 'refresh');
      exit(); 
    }

    $data['title'] = 'Lessee Pending Subscription';
    $data['content'] = $this->load->view('pages/lessor/pending', '', TRUE);
    $this->load->view('common/plain', $data);
  }

  public function profileEdit() {
    $data = $this->_commonAsset();
    $data['title'] = 'Lessor Profile Edit';
    $content['lessor'] = $this->Subscriber->findId($this->session->userdata('lessor_id'));
    $data['content'] = $this->load->view('pages/lessor/profile/edit', $content, TRUE);
    $data['script'][] = 'pages/lessor/profile';
    $this->load->view('common/lessor', $data);
  }

  public function profileSave() {
    $this->isAjax();
    $post = $this->input->post();
    $res['result'] = FALSE;

    if (
      empty($post['fname']) ||
      empty($post['mi']) ||
      empty($post['lname']) ||
      empty($post['address']) ||
      empty($post['contact'])
    ) {
      $res['message'] = 'Fill everything before saving';
    } else {
      if (!is_numeric($post['contact'])) {
        $res['message'] = 'Contact must be numeric';
      } else if (strlen($post['contact']) != 11) {
        $res['message'] = 'Contact must only consist of 11 digits starts with 09';
      } else {
        $data = array(
          $this->Subscriber->getId() => $this->session->userdata('lessor_id'),
          $this->Subscriber->getFname() => $post['fname'],
          $this->Subscriber->getMi() => $post['mi'],
          $this->Subscriber->getLname() => $post['lname'],
          $this->Subscriber->getAddress() => $post['address'],
          $this->Subscriber->getTelno() => $post['contact']
        );
        $this->Subscriber->update($data);
        $res['message'] = 'Lessor Profile Saved';
        $res['result'] = TRUE;
      }
    }
    echo json_encode($res);
  }

  public function message() {
    $this->load->model('Lessee');
    $data = $this->_commonAsset();
    $data['title'] = 'MESSAGE';
    $content['lessees'] = $this->Lessee->all()->result();

    $this->Lessee->setId($this->input->get('lessee'));

    $content['lessee'] = $this->Lessee->findById();
    $content['isDisable'] = empty($content['lessee']) ? 'disabled' : '';
       
    $data['content'] = $this->load->view('templates/message/detail', '', TRUE);
    $data['content'] .= $this->load->view('pages/lessor/message', $content, TRUE);

    $data['style'][] = 'libs/select2.min';
    $data['script'][] = 'libs/select2.min';
    $data['script'][] = 'libs/moment.min2';
    $data['script'][] = 'pages/lessor/message';

    $this->load->view('common/lessor', $data);
  }

  private function _commonAsset() {
    $data['script'] = array('libs/pnotify.core', 'libs/pnotify.buttons');
    $data['style'] = array('libs/pnotify');
    return $data;
  }

  private function _commonListAsset() {
    $data['script'] = array('libs/pnotify.core', 'libs/pnotify.buttons', 'libs/jquery.dataTables');
    $data['style'] = array('libs/pnotify', 'libs/dataTables.min');
    return $data;
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
            $this->load->model('Account');
            $account = $this->Account->findByUserId($lessorId);
            $accountData = array(
              $this->Account->getId() => $account[$this->Account->getId()],
              $this->Account->getUsername() => $post['username'],
              $this->Account->getPassword() => $data[$this->Subscriber->getPassword()]
            );
            $this->Account->update($accountData);
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