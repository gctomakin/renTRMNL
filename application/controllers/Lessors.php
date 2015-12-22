<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessors extends CI_Controller {

	public function __construct() {
      parent::__construct();
      $this->load->model('Subscriber');
  }

  public function index() {
  	echo "TODO";
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

  /**
   * Checks Lessors method if user has authority, 
   * otherwise user will be redirect to the respected page
   * @param  String $method Name of the method
   * @return method or redirect
   */
  public function _remap($method) {
  	$isLogin = $this->session->has_userdata('lessor_logged_in');
  	if ( // Check for login session except for :
  		$method == "signinPage" ||
  		$method == "signin"
  	) {
  		if ($isLogin) { // Check if login session already exist
  			redirect('lessors/');
  			exit();
  		}
  	} else {
  		if (!$isLogin && $this->input->is_ajax_request()) {
  			echo json_encode(array('result' => '403')); // Returns Forbidden code if not signed in
  		} else if (!$isLogin) { // Redirect to signin page if not signed in
  			redirect('lessors/signin-page');
  			exit();
  		}
  	}
  	$this->$method();
  }
}