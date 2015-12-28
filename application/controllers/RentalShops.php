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
			$data = array(
				$this->RentalShop->getName() => $res['post']['name'],
				$this->RentalShop->getBranch() => $res['post']['branch'],
				$this->RentalShop->getLatitude() => $res['post']['latitude'],
				$this->RentalShop->getLongitude() => $res['post']['longitude'],
				$this->RentalShop->getSubscriberId() => $this->session->has_userdata('lessor_id')
			);
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

	public function edit() {
		$data = $this->_validate();
		echo 'TODO';
	}

	public function update() {
		echo 'TODO';
	}

	public function delete() {
		echo 'TODO';
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
			$data['post'] = $this->input->post();
			$data['result'] = true;
		}
		return $data;
	}

}