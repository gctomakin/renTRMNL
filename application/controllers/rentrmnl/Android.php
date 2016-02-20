<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Android extends CI_Controller {

	public function __construct() {
	    parent::__construct();
	    LibsLoader();
	    $this->load->model('Lessee');
	    $this->load->model('Category');
	    $this->load->model('RentalShop');
	    $this->load->model('AndroidModel');
	    $this->load->model('MyInterest');
	    $this->load->model('MyShop');
	    $this->load->library('MyPusher');
		$this->Lessee->setId($this->session->userdata('lessee_id'));
		$this->MyShop->setLesseeId($this->session->userdata('lessee_id'));
		$this->MyInterest->setLesseeId($this->session->userdata('lessee_id'));
	}

	public function index(){

		$module = $this->input->post("module") ;
		$data = array();
		$success = 1;

		switch($module){

			case '1': // login
				$this->Lessee->setUsername($this->input->post('username', TRUE));
				$this->Lessee->setPassword($this->input->post('password', TRUE));
				$user = $this->Lessee->authenticate();
				
				if(!empty($user)){
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
					$data['result'] = "Login Successful";
					$data['user_id'] = $user['lessee_id'];
					$success = 1;

				}else{
					$data['result'] = "Login Failed.";
					$success = 0;
				}
				break;
			case '2': // fetch items by category

				$data['items'] = $this->AndroidModel->getItemsPerCategory($this->input->post('id', TRUE));
				$success = 1;
				break;
			case '3': // get all shops
				$data['shops'] = $this->RentalShop->all($select = "*");
				$success = 1;
				break;
			case '4': // get items by shop
				$data['items'] = $this->AndroidModel->getItemsPerShop($this->input->post('id', TRUE));
				$success = 1;
				break;
			case '5': // get interest items 
				$data['items']= $this->AndroidModel->getInterestsItems($this->input->post('id', TRUE));
				$success = 1;
				break;
			case '6': // get all items
				$data['items']= $this->AndroidModel->getAllItems();
				$success = 1;
				break;
			case '40': //  get myshops
				
				$data['shops'] = $this->AndroidModel->getShopsByLesseeId($this->input->post('id', TRUE));

				$success = 1;
				
				break;

			case '41':// add to my shop
				$post = $this->input->post(NULL, TRUE);
				$this->MyShop->setMyShopName($post['shop_name']);
				$this->MyShop->setShopId($post['shop_id']);
				$this->MyShop->setLesseeId($post['id']);
				if($this->notify('Shop has been added','Your shop has been added','lessor')):
				  	$this->MyShop->insert();
					$data['result'] = "Shop successfully added";
					$success = 1;
				else:
					$data['result'] = "Shop addition failed";
				 	$success = 0;
				endif;
				break;
			case '42': // delete from my shop
				$post = $this->input->post(NULL, TRUE);
				$this->MyShop->setId($post['myshop_id']);
				$this->MyShop->setLesseeId($post['id']);
				$result = $this->MyShop->delete();

				if ($result):
				    $data['shops'] = $this->AndroidModel->getShopsByLesseeId($post['id']);
				    $data['result'] = "Shop has been successfully deleted";
				    $success = 1;
				else:
				    $data['result'] = "Shop deletion failed";
				    $success = 0;
				endif;
				break;
			case '51': // add item to interest
					$post = $this->input->post(NULL, TRUE);
					$this->MyInterest->setMyInterestName($post['interest_name']);
					$this->MyInterest->setItemId($post['item_id']);
					$this->MyInterest->setLesseeId($post['id']);
					if($this->notify('Item has been added','Your item has been added','lessor')):
					   	$this->MyInterest->insert();
						$data['result'] = "Item has been added to your Interest";
						$success = 1;
					else:
						$data['result'] = "Item addition failed";
					   $success = 0;
					endif;
				break;
			case '52': // delete item from interest
				$this->MyInterest->setId($this->input->post('interest_id', TRUE));
				$result = $this->MyInterest->delete();

				if ($result):
					$data['items']= $this->AndroidModel->getInterestsItems($this->input->post('id', TRUE));
					$data['result'] = "Item has been deleted successfully";
				    $success = 1;
				else:
				    $data['result'] = "Item deletion failed";
				    $success = 0;
				endif;

				break;		
			default: // dashboard
			
				$data['categories'] = $this->Category->all($select = "category_id,category_type", $like = "");
				$success = 1;

				break;
		}
		$data['success'] = $success;
		echo json_encode($data);
	}

	private function notify($subject,$message,$type) {
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


	/*public function addMyInterest() {
	    $post = $this->input->post(NULL, TRUE);
	    $this->MyInterest->setMyInterestName($post['interest_name']);
	    $this->MyInterest->setItemId($post['item_id']);
	    $this->MyInterest->setLesseeId($post['id']);

	    if($this->notify('Item has been added','Your item has been added','lessor')):
	       echo $this->MyInterest->insert();
	    else:
	      echo FALSE;
	    endif;
	}

	public function removeMyInterest($id) {
	  $this->MyInterest->setId($id);
	  $result = $this->MyInterest->delete();

	  if ($result):
	      $this->session->set_flashdata('success', TRUE);
	      redirect('lessee/myinterests', 'refresh');
	  else:
	      $this->session->set_flashdata('error', TRUE);
	      redirect('lessee/myinterests', 'refresh');
	  endif;
	}*/
	public function download(){

		$file = 'uploads/rentrmnl.apk';

		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    readfile($file);
		   
		}

	}
}