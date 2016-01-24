<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservations extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
		$this->load->model('Reservation');
		$this->load->model('ReservationDetail');
	}

  public function item($itemId = "") {
  	$this->load->model('Item');
  	$content['item'] = $this->Item->findById($itemId);
		$content['itemPic'] = $content['item']['item_pic'] == NULL ? 'http://placehold.it/150x100' : 'data:image/jpeg;base64,'.base64_encode($content['item']['item_pic']);
		$content['action'] = site_url('reservations/save');
	  $content['startDate'] = date('Y-m-d', strtotime('+1 days'));
	  if (empty($content['item'])) {
  		redirect(site_url('lessee/items'));
  	} else {
	  	$data['title'] = "ITEM RESERVATION";
	  	$data['content'] = $this->load->view('pages/reservations/form', $content, TRUE);
	  	$data['script'] = array(
	  		'libs/pnotify.core',
	      'libs/pnotify.buttons',
	      'libs/select2.min',
	      'libs/moment.min2',
	      'libs/daterangepicker',
	      'libs/daterange',
	      'pages/reservations/form'
	    );
	    $data['style'] = array(
	    	'libs/pnotify',
	    	'libs/datepicker',
	    	'libs/select2.min'
	    );
	    $this->load->view('common/lessee', $data);
	  }
  }

  public function save() {
  	$res = $this->_validate();
  	if ($res['result']) { 
  		$item = $res['item'];
  		unset($res['item']);
  		$total = $item['item_rate'] * $res['qty'];
  		$this->Reservation->setDate(date('Y-m-d H:i:s'));
			$this->Reservation->setDateRented($res['dateFrom']);
			$this->Reservation->setDateReturned($res['dateTo']);
			$this->Reservation->setTotalAmt($total);
			$this->Reservation->setDownPayment($total/2);
			$this->Reservation->setTotalBalance($total);
			$this->Reservation->setPenalty(0);
			$this->Reservation->setStatus('pending');
			$this->Reservation->setLesseeId($this->session->userdata('lessee_id'));
			$id = $this->Reservation->create();
			$res['result'] = FALSE;
			if ($id > 0) {
				$this->ReservationDetail->setRentalAmt($item['item_rate']);
				$this->ReservationDetail->setQty($res['qty']);
				$this->ReservationDetail->setItemId($item['item_id']);
				$this->ReservationDetail->setReserveId($id);
				if ($this->ReservationDetail->create() > 0) {
					$res['result'] = TRUE;
					$res['message'] = 'Reservation Created';
				} else {
					$res['message'] = 'Reservation Detail: Internal Server Error';
				}
			} else {
				$res['message'] = 'Reservation: Internal Server Error';
			}
  	}
  	echo json_encode($res);
  }

  public function cancel() {
  	$this->isAjax();
  	$post = $this->input->post();
  	$res['result'] = FALSE;
  	if (empty($post['id']) || !is_numeric($post['id'])) {
  		$res['message'] = 'Invalid Parameter';
  	} else {
  		$this->Reservation->setId($post['id']);
  		$this->Reservation->setStatus('cancel');
  		if ($this->Reservation->update()) {
  			$res['message'] = 'Reservation Deleted';
  			$res['result'] = TRUE;
  		} else {
  			$res['message'] = 'Internal Server Error';
  		}
  	}
  	echo json_encode($res);
  }

  private function _validate() {
  	$this->isAjax();
  	$post = $this->input->post();
  	$res['result'] = FALSE;

  	if (empty($post['itemId'])) {
  		$res['message'][] = "Item Id must not be empty";
  	} else if (!is_numeric($post['itemId'])) {
  		$res['message'][] = "Item ID must be numeric";
  	}

  	if (empty($post['quantity'])) {
  		$res['message'][] = "Quantity must not be empty";
  	} else if (!is_numeric($post['quantity'])) {
  		$res['message'][] = "Quantity must be numeric";
  	}
  	
  	if(empty($post['date'])) {
  		$res['message'][] = 'Date must not be empty';
  	}

  	if (empty($res['message'])){
  		$date = explode(' - ', $post['date']);
  		if (
  			empty($date) || count($date) <= 1 ||
  			!$this->validateDate($date[0], 'Y-m-d') ||
  			!$this->validateDate($date[1], 'Y-m-d')
  		) {
  			$res['message'] = 'Invalid Date Format';
  		} else if (strtotime($date[0]) <= strtotime(date('Y-m-d'))) {
  			$res['message'] = 'Date must be greater than today';
  		} else {
	  		$this->load->model('Item');
  			$res['dateFrom'] = $date[0];
  			$res['dateTo'] = $date[1];
	  		$res['qty'] = $post['quantity'];
	  		$res['item'] = $this->Item->findById($post['itemId']);
	  		if (empty($res['item'])) {
	  			$res['message'] = 'Item not exist.';
	  		} else {
	  			$res['result'] = TRUE;
	  		}
	  	}
  	} else {
  		$res['message'] = implode('. <br>', $res['message']) . '.';
  	}
  	return $res;
  }
}