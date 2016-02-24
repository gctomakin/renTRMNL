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
  	$content['item'] = $this->Item->findByIdComplete($itemId);
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
    // TESTING
    // $this->load->library('MyPusher');
    // $notification = array(
    //   'usertype' => 'lessor',
    //   'date' => date('Y/m/d'),
    //   'receiver' => $post['subscriber'],
    //   'notification' => 'New reservation of your item : ', //. implode(', ', $itemReserved),
    //   'sender' => $this->session->userdata('lessee_fname'),
    //   'link' => site_url('lessor/reservations/pending')
    // );
    // $this->mypusher->Message('reservation-channel', 'reservation-event', $notification);
    // $res['message'] = "DONE";
    // echo json_encode($res);
    // exit();
  	if ($res['result'] && empty($post['forRent'])) { 
  		$this->Reservation->setDate(date('Y-m-d H:i:s'));
      $from = empty($post['from']) ? date('Y-m-d H:i:s') : $post['from'];
			$this->Reservation->setDateRented(date('Y-m-d H:i:s', strtotime($from)));
			$this->Reservation->setDateReturned(date('Y-m-d H:i:s', strtotime($post['to'])));
			$this->Reservation->setTotalAmt($post['total']);
			$this->Reservation->setDownPayment($post['total']/2);
			$this->Reservation->setTotalBalance($post['total']);
			$this->Reservation->setPenalty(0);
      $status = empty($post['status']) ? 'pending' : $post['status']; 
			$this->Reservation->setStatus($status);
      $this->Reservation->setLesseeId($this->session->userdata('lessee_id'));
			$this->Reservation->setSubscriberId($post['subscriber']);
			$id = $this->Reservation->create();
			$res['result'] = FALSE;
			if ($id > 0) {
        $error = array();
        $itemReserved = array();
        foreach ($post['details'] as $detail) {
          $this->ReservationDetail->setRentalAmt($detail['amount']);
          $this->ReservationDetail->setQty($detail['qty']);
          $this->ReservationDetail->setItemId($detail['id']);
          $this->ReservationDetail->setReserveId($id);
          if ($this->ReservationDetail->create() <= 0) {
           $error[] = 'Error on item # ' . $detail['id'];
          }
          $itemReserved[] = "Item # " . $detail['id'];
        }
        if (empty($error)) {
          $this->load->library('MyPusher');
          $notification = array(
            'usertype' => 'lessor',
            'date' => date('Y/m/d'),
            'receiver' => $post['subscriber'],
            'notification' => 'New reservation of your items <br>' . implode(', ', $itemReserved),
            'sender' => $this->session->userdata('lessee_fname'),
            'link' => site_url('lessor/reservations/pending')
          );
          $this->mypusher->Message('top-notify-channel', 'top-notify-event', $notification);
          $res['result'] = TRUE;
          $res['message'] = 'Reservation Added';
          $res['resid'] = $id;
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

  public function returnStatus() {
    echo $this->_changeStatus('return');
  }

  public function close() {
    $this->load->model('RentalPayment');
    $payments = $this->RentalPayment->findByReservationId($this->input->post('id'), 'pending');
    if (!empty($payments)) {
      echo json_encode(
        array(
          'result' => FALSE, 
          'message' => 'There are some pending payments, Please confirm it before closing this reservations'
        )
      );
    } else {
      echo $this->_changeStatus('close');
    }
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
        if ($status == 'return' && $reservation[$this->Reservation->getTotalBalance()] > 0) {
          $res['message'] = 'Cannot return this rental if theres balance left';
        } else {
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
      $this->Item->getName() => $obj->item_name,
      $this->Item->getStatus() => $obj->item_stats,
      "qty" => $obj->qty,
      $this->Item->getDesc() => $obj->item_desc,
    );
  }

  private function _validate() {
  	$this->isAjax();
  	$res['result'] = FALSE;
    $this->form_validation->set_rules('from', 'Start Date', 'trim|date|xss_clean');
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