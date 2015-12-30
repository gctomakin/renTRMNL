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
  	$data['content'] = "<h1>Testing</h1>";
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
	    	$this->encrypt->decode($user[$sub->getPassword()]) == $password
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
      'pages/create-form'
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
        'pages/create-form'
      );
      $data['style'] = array('libs/pnotify');
      $this->load->view('common/lessor', $data); 
    } else {
      redirect('lessor/shops/list');
    }
  }

  public function shopList($page = 1) {
    $this->load->model('RentalShop');
    $this->load->library('pagination');

    $data['title'] = "My Shops";
    $lessorId = $this->session->userdata('lessor_id');
    $offset = ($page - 1) * $this->RentalShop->getLimit();
    $this->RentalShop->setOffset($offset); // Setting Rentalshop offset rows

    $shops = $this->RentalShop->findBySubscriberId($lessorId);
    
    // Configuring Pagination
    $config['base_url'] = site_url('lessor/shops/list/');
    $config['total_rows'] = $shops['count'];
    $config['per_page'] = $this->RentalShop->getLimit();
    $this->pagination->initialize($config);
    
    $content['pagination'] = $this->pagination->create_links();
    $content['shops'] = $shops['data'];

    $data['content'] = $this->load->view('pages/shops/list', $content, true);
    $data['style'] = array('libs/pnotify');
    $data['script'] = array(
      'libs/pnotify.core',
      'libs/pnotify.buttons',
      'pages/shops/list'
    );

    $this->load->view('common/lessor', $data);
  }

  public function itemCreate() {
  	$data['title'] = "Creating New Item";
    $content['action'] = "create";
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

  public function itemList() {
  	echo "testing";
  	exit();
  }

}