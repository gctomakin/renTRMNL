<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	public function __construct() {
    parent::__construct();
    $this->load->model('Account');
  }

  public function signup() {
  	$this->isAjax();
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

    $res['result'] = FALSE;

    if($this->form_validation->run() == FALSE) {
    	$res['message'] = validation_errors();
    } else {
      $post = $this->input->post(NULL, TRUE);
      $account = $this->Account->findUsername($post['username']);
      if (empty($account)) {
	      $userId = 0;
	      $encryptPassword = $this->encryption->encrypt($post['password']);
	      switch ($post['user_type']) {
	      	case 'lessee' :
		        $this->load->model('Lessee');
		        $this->Lessee->setFname($post['fname']);
		        $this->Lessee->setLname($post['lname']);
		        $this->Lessee->setEmail($post['email']);
		        $this->Lessee->setPhoneno($post['phoneno']);
		        $this->Lessee->setUsername($post['username']);
		        $this->Lessee->setPassword($encryptPassword);        
		        $userId = $this->Lessee->insert();
		        break;
		      case 'lessor' :
		      	$this->load->model('Subscriber');
		        $data = array(
		          'username'=> $post['username'],
		          'password'=> $encryptPassword
		        );
		        $data[$this->Subscriber->getFname()] = $post['fname'];
		        $data[$this->Subscriber->getLname()] = $post['lname'];
		        $data[$this->Subscriber->getEmail()] = $post['email'];
		        $data[$this->Subscriber->getTelno()] = $post['phoneno'];

		        $userId = $this->Subscriber->create($data);
		       	break;
		      case 'admin' :
		      	$this->load->model('Admin');
		      	$this->Admin->setFname($post['fname']);
			      $this->Admin->setLname($post['lname']);
			      $this->Admin->setMidinit($post['midinit']);
			      $this->Admin->setUsername($post['username']);
			      $this->Admin->setPassword($encryptPassword);
			      $userId = $this->Admin->insert();
			      break;
	      }
	      $res['message'] = 'Internal Server Error';
	      if (!empty($userId)) {
	      	$data = array(
	      		$this->Account->getUsername() => $post['username'],
	      		$this->Account->getPassword() => $encryptPassword,
	      		$this->Account->getUserId() => $userId,
	      		$this->Account->getUserType() => $post['user_type'],
	      	);
	      	if ($this->Account->create($data) > 0) {
	      		$res['message'] = 'Success Signup';
	      		$res['result'] = TRUE;
	      	} else {
	      		$res['message'] = 'Internal Server Error # 2';		
	      	}
	      }
	    } else {
	    	$res['message'] = 'Username already taken';
	    }
    }

    echo json_encode($res);	
  }

	public function signin() {
		$post = $this->input->post();
  	if (!empty($post)) {
	  	$username = $post['username'];
	    $password = $post['password'];
	    $account = $this->Account->findUsername($username);
	    
	    if (
	    	!empty( $account ) &&
	    	$this->encryption->decrypt($account[$this->Account->getPassword()]) == $password
	    ) {

	    	$userdata = array();
	    	$redirectTo = "";
	    	$userId = $account[$this->Account->getUserId()];
	    	switch($account[$this->Account->getUserType()]) {
	    		case 'lessor':
	    			$this->load->model('Subscriber');
	    			$user = $this->Subscriber->findId($userId);
	    			$userdata = array(
			      	'lessor_id' => $user[$this->Subscriber->getId()],
			        'lessor_fullname' => $user[$this->Subscriber->getFname()] . ' ' . $user[$this->Subscriber->getLname()],
			        'lessor_logged_in' => TRUE
			      );
			      $redirectTo = 'lessors';
			      break;
			    case 'lessee':
			    	$this->load->model('Lessee');
			    	$redirectTo = 'lessees';
			    	$this->Lessee->setId($userId);
			    	$user = $this->Lessee->findById();
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
			    	break;
			    case 'admin':
			    	$this->load->model('Admin');
			    	$redirectTo = 'admins';
			    	$this->Admin->setId($userId);
			    	$user = $this->Admin->findById();
			    	$userdata = array(
			    		'admin_id' => $user['admin_id'],
              'username' => $user['username'],
              'admin_fname' => $user['admin_fname'],
              'admin_midint' => $user['admin_midint'],
              'admin_lname' => $user['admin_lname'],
              'admin_status' => $user['admin_status'],
              'admin_logged_in' => TRUE
            );
			    	break;
	    	}

	      $this->session->set_userdata($userdata);
	      redirect($redirectTo);
	    } else {
	      $this->session->set_flashdata('error_login', TRUE);
	      redirect('/#wew','refresh');
	    }
	  } else {
	  	redirect('/#lol','refresh');
	  }
	}
}