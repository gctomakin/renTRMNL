<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rental extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
	}

	public function pay() {
    $this->isAjax();
    $res['result'] = FALSE;
    $post = $this->input->post();
    
    $this->form_validation->set_rules('id', 'Reservation ID', 'trim|required|numeric|xss_clean');
    
    if ($this->form_validation->run() == FALSE) {
      $res['message'] = validation_errors();
    } else {
			$this->load->model('Reservation');
      $reservation = $this->Reservation->findById($post['id']);
      if (empty($reservation)) {
        $res['message'] = 'Reservation not found';
      } else {
        $this->load->library('Paypal');
        $this->load->Model('Subscriber');
        $this->load->Model('ReservationDetail');
        $rDetails = $this->ReservationDetail->findByReservationId($post['id']);
        $subscriber = $this->ReservationDetail->findSubscriberByReservationId($post['id']);
        $email = $subscriber[$this->Subscriber->getPaypal()];
        $total = $reservation[$this->Reservation->getTotalAmt()];
        $pDetails = array_map(array($this, '_proccessForPaypal'), $rDetails);
        if (empty($email)) {
          $res['message'] = 'Lessor does not have paypal account, please contact him for reservation';
        } else {
          $res['paypal'] = $this->paypal->createPacketDetail(
            $total,
            $email,
            $pDetails,
            'rental/returnPaypal', // Return
            'rental/cancelPaypal' // Cancel
          );
          $this->session->set_flashdata('is_paypal', TRUE);
          $this->session->set_flashdata('reservation_id', $post['id']);
          $this->session->set_flashdata('reservation_payment', $total);
          
          $res['email'] = $email;
          $res['result'] = TRUE;
        }
      }
    }
    echo json_encode($res);
  }

  public function returnPaypal() {
    $paypal = $this->session->flashdata();
    if (empty($paypal['is_paypal'])) {
      redirect('lessees');
      exit();
    }
    $this->load->model('Reservation');
    $reservation = $this->Reservation->findById($paypal['reservation_id']);
    $content = "";
    if (empty($reservation)) {
      $content['message'] = 'Cannot found Reservation ID';
    } else {
      $balance = $reservation[$this->Reservation->getTotalBalance()] - $paypal['reservation_payment'];
      $this->Reservation->setId($paypal['reservation_id']);
      $this->Reservation->setTotalBalance($balance);
      if ($this->Reservation->update()) {
        $content['message'] = 'Please wait.. while reservation payment for rental is on process..';
      } else {
        $content['message'] = 'Internal Server Error: Reservation Update';
      }
    }
    $data['content'] = $this->load->view('pages/paypal/return', $content, TRUE);
    $this->load->view('common/plain', $data);   
  }

  public function cancelPaypal() {
    $paypal = $this->session->flashdata();
    if (empty($paypal['is_paypal'])) {
      redirect('lessees');
      exit();
    }    
    $content['message'] = 'Your payment for rental has been cancelled..';
    $data['content'] = $this->load->view('pages/paypal/cancel', $content, TRUE);
    $this->load->view('common/plain', $data);
  }


  private function _proccessForPaypal($detail) {
    if (is_array($detail)) {
      $detail = json_decode(json_encode($detail), FALSE);
    }
    return array(
      'name' => $detail->item_desc . ' '. $detail->rental_amt . ' x ' . $detail->qty,
      'price' => $detail->rental_amt * $detail->qty,
      'identifier' => 'p' . $detail->item_id,
    );
  }
}