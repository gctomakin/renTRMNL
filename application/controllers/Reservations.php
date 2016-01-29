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
	  $content['startDate'] = date('m/d/Y');
	  if (empty($content['item'])) {
  		redirect(site_url('lessee/items'));
  	} else {
	  	$data['title'] = "ITEM RESERVATION";
	  	$data['content'] = $this->load->view('pages/reservations/form', $content, TRUE);
	  	$data['script'] = array(
	  		'libs/pnotify.core',
	      'libs/pnotify.buttons',
	      'libs/moment.min2',
        'libs/underscore-min',
	      'libs/daterangepicker',
	      'pages/reservations/daterange',
	      'pages/reservations/form'
	    );
	    $data['style'] = array(
	    	'libs/pnotify',
	    	'libs/datepicker',
        'pages/reservations/item'
      );
	    $this->load->view('common/lessee', $data);
	  }
  }

  public function save() {
    $res = $this->_validate();
    $post = $this->input->post();
  	if ($res['result']) { 
  		$this->Reservation->setDate(date('Y-m-d H:i:s'));
			$this->Reservation->setDateRented($post['from']);
			$this->Reservation->setDateReturned($post['to']);
			$this->Reservation->setTotalAmt($post['total']);
			$this->Reservation->setDownPayment($post['total']/2);
			$this->Reservation->setTotalBalance($post['total']);
			$this->Reservation->setPenalty(0);
			$this->Reservation->setStatus('pending');
			$this->Reservation->setLesseeId($this->session->userdata('lessee_id'));
			$id = $this->Reservation->create();
			$res['result'] = FALSE;
			if ($id > 0) {
        $error = array();
        foreach ($post['details'] as $detail) {
          $this->ReservationDetail->setRentalAmt($detail['rate']);
          $this->ReservationDetail->setQty($detail['qty']);
          $this->ReservationDetail->setItemId($detail['id']);
          $this->ReservationDetail->setReserveId($id);
          if ($this->ReservationDetail->create() <= 0) {
           $error[] = 'Error on item # ' . $detail['id'];
          } 
        }
        if (empty($error)) {
          $res['result'] = TRUE;
          $res['message'] = 'Reservation Added';
        } else {
          $this->Reservation->delete($id);
          $res['message'] = "Reservation Detail: Internal Server Error : " . implode(', ', $error);
        }
			} else {
				$res['message'] = 'Reservation: Internal Server Error';
			}
  	}
  	echo json_encode($res);
  }

  public function cancel() {
  	echo $this->_changeStatus('cancel');
  }

  public function approve() {
    echo $this->_changeStatus('approve');
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
      $reservation = $this->Reservation->findById($post['id']);
      if (empty($reservation)) {
        $res['message'] = 'Reservation not found';
      } else {
        if (
          $status == 'cancel' &&
          $reservation[$this->Reservation->getStatus()] == 'payment pending'
        ) {
          $status = 'payment cancel';
        }
        $this->Reservation->setId($post['id']);
        $this->Reservation->setStatus($status);
        if ($this->Reservation->update()) {
          $res['message'] = 'Reservation ' . ucfirst($status);
          $res['result'] = TRUE;
          $res['status'] = $status;
        } else {
          $res['message'] = 'Internal Server Error';
        }
      }
    }
    return json_encode($res);
  }

  public function detail() {
    $this->isAjax();
    $post = $this->input->post();
    $res['result'] = FALSE;
    if (empty($post['id']) || !is_numeric($post['id'])) {
      $res['message'] = 'Invalid Parameter';
    } else {
      $details = $this->ReservationDetail->findByReservationId($post['id']);
      if (empty($details)) {
        $res['message'] = 'Reservation Detail not found';
      } else {
        $content['details'] = array_map(array($this, '_processItem'), $details);
        $res['result'] = TRUE;
        $res['view'] = $this->load->view('pages/reservations/detail', $content, TRUE);
      }
    }
    echo json_encode($res);
  }

  private function _processItem($obj) {
    $this->load->model('Item');
    if (is_array($obj)) {
      $obj = json_decode(json_encode($obj), FALSE);
    }
    $img = $obj->item_pic == NULL ? 'http://placehold.it/250x150' : 'data:image/jpeg;base64,' . base64_encode($obj->item_pic);
    return array(
      $this->Item->getId() => $obj->item_id,
      $this->Item->getRate() => $obj->item_rate,
      $this->ReservationDetail->getRentalAmt() => $obj->rental_amt,
      $this->Item->getPic() => $img,
      $this->Item->getStatus() => $obj->item_stats,
      "qty" => $obj->qty,
      $this->Item->getDesc() => $obj->item_desc,
    );
  }

  private function _validate() {
  	$this->isAjax();
  	$res['result'] = FALSE;
    $this->form_validation->set_rules('from', 'Start Date', 'trim|required|date|xss_clean');
    $this->form_validation->set_rules('to', 'End Date', 'trim|required|date|xss_clean');
    $this->form_validation->set_rules('total', 'Total', 'trim|required|numeric|xss_clean');
    
    if ($this->form_validation->run() == FALSE) {
      $res['message'] = validation_errors();
    } else {
      $res['result'] = TRUE;
    }
  	
  	return $res;
  }
}