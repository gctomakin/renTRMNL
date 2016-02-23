<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penalties extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
		$this->load->model('Penalty');
	}

	public function add() {
		$this->isAjax();
		$res['result'] = FALSE;
		$post = $this->input->post();

		if (empty($post['resId']) || !is_numeric($post['resId'])) {
			$res['message'] = 'Invalid Parameter';
		} else {
			$this->load->model('Reservation');
			$rd = $this->Reservation->findItemPenaltyById($post['resId']);
			$res['penalty'] = $rd['total_penalty'];
			if (empty($rd)) {
				$res['message'] = 'Rental not found';
			} else if ($rd['total_penalty'] == 0) {
				$res['message'] = 'Total Penalty is 0, Some rented items doesnt have penalty';
			} else {
				$balance = $rd[$this->Reservation->getTotalBalance()];
				$this->Reservation->setTotalBalance($balance + $rd['total_penalty']);
				$this->Reservation->setPenalty($rd['total_penalty']);
				$this->Reservation->setId($post['resId']);
				if ($this->Reservation->update()) {
					$this->Penalty->setReserveId($post['resId']);
					$this->Penalty->setAmt($rd['total_penalty']);
					$this->Penalty->setType('Late Submission');
					if ($this->Penalty->create()) {
						$res['result'] = TRUE;
						$res['message'] = 'Update';
					} else {
						$res['message'] = 'Penalty: Internal Server Error';
					}
				} else {
					$res['message'] = 'Reservation: Internal Server Error';
				}
			}
		}

		echo json_encode($res);
	}
}