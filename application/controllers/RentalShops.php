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
		$this->form_validation->set_rules('latitude', 'Location Latitude', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('longitude', 'Location Longitude', 'trim|required|numeric|xss_clean');

		$data['result'] = false;

		if ($this->form_validation->run() == FALSE) {
			$data['message'] = validation_errors();
		} else {
			$post = $this->input->post();
			$data['post'] = array(
				$this->RentalShop->getName() => $post['name'],
				$this->RentalShop->getBranch() => $post['branch'],
				$this->RentalShop->getLatitude() => $post['latitude'],
				$this->RentalShop->getLongitude() => $post['longitude'],
				$this->RentalShop->getSubscriberId() => $this->session->userdata('lessor_id')
			);
			if (!empty($post['id'])) {
				$data['post'][$this->RentalShop->getId()] = $post['id'];
			}
			$data['result'] = true;
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
	
	private function mapShops($data) {
		return array(
			'id' => $data->shop_id,
			'text' => $data->shop_name
		);
	}

}
