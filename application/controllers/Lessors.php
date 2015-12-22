<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lessors extends CI_Controller {

	public function __construct() {
      parent::__construct(2);
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

}