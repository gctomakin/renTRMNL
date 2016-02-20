<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentalShops extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
		$this->load->model('RentalShop');
	}

	public function create() {	
		$res = $this->_validate();
		if ($res['result']) {
			$data = $res['post'];
			if ($this->RentalShop->create($data) > 0) {
				$res['message'] = 'New Shop Created.';
				$res['reset'] = true;
			} else {
				$res['message'] = 'Internal Server Error.';
				$res['result'] = false;
			}
			unset($res['post']);
		}
    echo json_encode($res);
	}

	public function update() {
		$res = $this->_validate();
		if ($res['result']) {
			$data = $res['post'];
			$this->RentalShop->update($data);
			$res['message'] = 'Updated Shop ' . $res['post'][$this->RentalShop->getName()];
		}
		unset($res['post']);
    echo json_encode($res);
	}

	public function delete() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = false;
		if ($post && isset($post['id']) && is_numeric($post['id'])) {
			if ($this->RentalShop->delete($post['id'])) {
				$res['result'] = true;
				$res['message'] = "Deleted Shop # : " . $post['id'];
			} else {
				$res['mesasge'] = "Internal Server Error";
			}
		} else {
			$res['message'] = "Invalid Parameter";
		}
		echo json_encode($res);
	}

	private function _validate() {
		$this->isAjax();
		$this->form_validation->set_rules('name', 'Name of the Shop', 'trim|required|min_length[2]|xss_clean');
		$this->form_validation->set_rules('branch', 'Branch of the Shop', 'trim|required|min_length[2]|xss_clean');
		$this->form_validation->set_rules('address', 'Address of the Shop', 'trim|required|xss_clean');
		$this->form_validation->set_rules('latitude', 'Location Latitude', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('longitude', 'Location Longitude', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('description', 'Location Longitude', 'trim|xss_clean');

		$data['result'] = false;

		if ($this->form_validation->run() == FALSE) {
			$data['message'] = validation_errors();
		} else {
			$data['message'] = $this->_validateImage();
			if (empty($data['message'])) {
				$post = $this->input->post();
				$data['post'] = array(
					$this->RentalShop->getName() => $post['name'],
					$this->RentalShop->getBranch() => $post['branch'],
					$this->RentalShop->getAddress() => $post['address'],
					$this->RentalShop->getDesc() => $post['description'],
					$this->RentalShop->getLatitude() => $post['latitude'],
					$this->RentalShop->getLongitude() => $post['longitude'],
					$this->RentalShop->getSubscriberId() => $this->session->userdata('lessor_id')
				);
				if ($_FILES['image']['size'] != 0) {
					$data['post'][$this->RentalShop->getImage()] = file_get_contents($_FILES['image']['tmp_name']);
				}
				if (!empty($post['id'])) {
					$data['post'][$this->RentalShop->getId()] = $post['id'];
				}
				$data['result'] = true;
			}
		}
		return $data;
	}

	public function allByLessor() {
		$this->isAjax();
		$key = $this->input->get('q');
		$lessorId = $this->session->userdata('lessor_id');

		$shops = $this->RentalShop->findBySubscriberId($lessorId, $key);
		$data = array('items' => array_map(array($this, 'mapShops'), $shops['data']));
		echo json_encode($data);
	}
	

	public function approve() {
		echo $this->_changeStatus('active');
	}

	public function disapprove() {
		echo $this->_changeStatus('disapprove');
	}

	private function _changeStatus($status) {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;

		if (empty($post['id']) || !is_numeric($post['id'])) {
			$res['message'] = 'Invalid Parameter';
		} else {
			$data = array(
				$this->RentalShop->getId() => $post['id'],
				$this->RentalShop->getStatus() => $status 
			);
			if ($this->RentalShop->update($data)) {
				$res['result'] = TRUE;
				$res['message'] = 'Updated Shop Status';
			} else {
				$res['message'] = 'Internal Server Error';
			}
		}
		return json_encode($res);
	}

	private function mapShops($data) {
		return array(
			'id' => $data->shop_id,
			'text' => $data->shop_name . ' - ' . $data->shop_branch
		);
	}

	private function _validateImage() {
		$message = empty($this->input->post('id')) ? 'Invalid Image' : '';
		if (!empty($_FILES['image']) && $_FILES['image']['size'] != 0) { // check if image has been upload
			$this->load->library('MyFile', $_FILES['image']); // Load My File Library
			$validate = $this->myfile->validateImage(); // Validate Image
			if (!$validate['result']) {
				$message = implode(', ', $validate['message']); // Specify Image validate errors
			} else {
				$message = '';
			}
		}
		return $message;
	}

}
