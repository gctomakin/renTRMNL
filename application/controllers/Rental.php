<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rental extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
	}

	public function pay() {
		$this->isAjax();
		$post = $this->input->post();

		$res['result'] = FALSE;

		if (empty($post['id']) || !is_numeric($post['id'])) {
			$res['message'] = 'Invalid parameter';
		}

		$this->load->model('Item');
		$this->load->model('Subscriber');
		$data = $this->Item->findByIdComplete($post['id']);
		if (empty($data)) {
			$res['message'] = 'Lessor not found';
		} else {
			$this->load->library('Paypal');
			$res['split_pay'] = $this->paypal->createPacket($data[$this->Item->getRate()], $data[$this->Subscriber->getEmail()]);
			if (empty($res['split_pay']) || $res['split_pay'] == FALSE) {
				$res['message'] = 'Internal Server Error';
			} else {
				$res['result'] = true;
			}
		}
		echo json_encode($res);
	}

}