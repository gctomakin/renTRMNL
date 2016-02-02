<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rental extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
    $this->load->model('RentalPayment');
	}

	public function pay() {
    $this->isAjax();
    $res['result'] = FALSE;
    $post = $this->input->post();
    
    $this->form_validation->set_rules('id', 'Reservation ID', 'trim|required|numeric|xss_clean');
    $this->form_validation->set_rules('type', 'Payment Type', 'trim|required|xss_clean');
    
    if ($this->form_validation->run() == FALSE) {
      $res['message'] = validation_errors();
    } else {
			$this->load->model('Reservation');
      $reservation = $this->Reservation->findById($post['id']);
      if (empty($reservation)) {
        $res['message'] = 'Reservation not found';
      } else {
        $this->load->model('Subscriber');
        
        $paypalCol = $this->Subscriber->getPaypal();
        
        $subscriber = $this->Reservation->findSubscriberById(
          $post['id'], array($paypalCol)
        );
        
        $email = $subscriber[$paypalCol];

        if (empty($email)) {
          $res['message'] = 'Lessor does not have paypal account, please contact him for reservation';
        } else {
          $this->load->library('Paypal');
          $this->load->model('ReservationDetail');
          $this->RentalPayment->deleteCache();       
          $rDetails = $this->ReservationDetail->findByReservationId($post['id']);
          $payments = $this->RentalPayment->findByReservationId($post['id']);
          $type = ucfirst($post['type']);
          $cashbond = 0;
          $total = $reservation[$this->Reservation->getTotalAmt()];
          if ($post['type'] != 'Full') {
            $total = $total / 2;
          }
          if (empty($payments)) {
            $pDetails = array_map(array($this, '_proPaypalWithCashBond' . $type), $rDetails);
            $cashbond = $this->_calTotalCashBond($rDetails);
            $type .= " With Cashbonds";
          } else {
            $pDetails = array_map(array($this, '_proPaypal' . $type), $rDetails);
          }
          $res['paypal'] = $this->paypal->createPacketDetail(
            $total + $cashbond,
            $email,
            $pDetails,
            $type,
            'rental/returnPaypal', // Return
            'rental/cancelPaypal' // Cancel
          );
          $this->session->set_flashdata('is_paypal', TRUE);
          $this->session->set_flashdata('reservation_id', $post['id']);
          $this->session->set_flashdata('reservation_payment', $total);
          $this->session->set_flashdata('reservation_type', $type);
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
        $this->RentalPayment->setAmount($paypal['reservation_payment']);
        $this->RentalPayment->setDate(date('Y-m-d H:i:s'));
        $this->RentalPayment->setReserveId($paypal['reservation_id']);
        $this->RentalPayment->setDescription($paypal['reservation_type']);
        $this->RentalPayment->setStatus('pending');
        if ($this->RentalPayment->create() > 0) {
          $content['message'] = 'Please wait.. while reservation payment for rental is on process..';
        } else {
          $content['message'] = 'Internal Server Error: Reservation Payment';
        } 
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

  public function changeStatus($status) {
    $this->isAjax();
    $post = $this->input->post();
    $res['result'] = FALSE;
    if (empty($post['id']) || !is_numeric($post['id'])) {
      $res['message'] = "Invalid Parameter";
    } else {
      $payment = $this->RentalPayment->findById($post['id'], 'pending');
      if (empty($payment)) {
        $res['message'] = 'Payment not found';
      } else {
        if ($status == 'cancel') {
          // UPDATE Reservation return balance from payment
          $resId = $payment[$this->RentalPayment->getReserveId()];
          $amt = $payment[$this->RentalPayment->getAmount()];
          $res['message'] = $this->_addToResBalance($resId, $amt);       
        }

        if (empty($res['message'])) {
          $this->RentalPayment->setId($post['id']);
          $this->RentalPayment->setStatus($status);
          if ($this->RentalPayment->update()) {
            $res['result'] = TRUE;
            $res['message'] = 'Payment has been ' . ucfirst($status);
          } else {
            $res['message'] = 'Internal Server Error';
          }
        }
      }
    }
    echo json_encode($res);
  }

  public function details() {
    $this->isAjax();
    $post = $this->input->post();
    $res['result'] = FALSE;

    if (empty($post['id']) || !is_numeric($post['id'])) {
      $res['message'] = 'Invalid Parameter';
    } else {
      $content['payments'] = $this->RentalPayment->findByReservationId($post['id']);
      $content['cancelUrl'] = site_url('rental/changeStatus/cancel');
      $content['receiveUrl'] = site_url('rental/changeStatus/receive');
      if ($this->session->has_userdata('lessor_logged_in')) {
        $content['isLessor'] = TRUE;
      }
      if (empty($content['payments'])) {
        $res['message'] = 'No payment history yet';
      } else {
        $res['view'] = $this->load->view('/pages/payments/detail', $content, TRUE);
        $res['result'] = TRUE;
      }
    }
    echo json_encode($res);
  }

  // PRIVATE METHODS
  
  private function _addToResBalance($resId, $payment) {
    $this->load->model('Reservation');
          
    $reservation = $this->Reservation->findById($resId);
    $resBalance = $reservation[$this->Reservation->getTotalBalance()];

    $balance = $resBalance + $payment; 

    $this->Reservation->setId($resId);
    $this->Reservation->setTotalBalance($balance);
    return $this->Reservation->update() ? '' : 'Internal Server Error: Reservation Update';
  }

  /**
   * Process the details for paypal
   * @param  Object $detail Object of details of reservations
   * @return Array         
   */
  private function _proPaypal($detail) {
    if (is_array($detail)) {
      $detail = json_decode(json_encode($detail), FALSE);
    }
    return array(
      'name' => $detail->item_desc . ' '. $detail->rental_amt . ' x ' . $detail->qty,
      'price' => $detail->rental_amt * $detail->qty,
      'identifier' => 'p' . $detail->item_id,
    );
  }

  private function _proPaypalHalf($detail) {
    if (is_array($detail)) {
      $detail = json_decode(json_encode($detail), FALSE);
    }
    return array(
      'name' => $detail->item_desc . ' ('. $detail->rental_amt . ' x ' . $detail->qty . ') / 2',
      'price' => ($detail->rental_amt * $detail->qty)/ 2,
      'identifier' => 'p' . $detail->item_id,
    ); 
  }

  private function _proPaypalWithCashBond($detail) {
    if (is_array($detail)) {
      $detail = json_decode(json_encode($detail), FALSE);
    }
    return array(
      'name' => $detail->item_desc . ' ('. $detail->rental_amt . ' x ' . $detail->qty . ') + ' . $detail->item_cash_bond,
      'price' => ($detail->rental_amt * $detail->qty) + $detail->item_cash_bond,
      'identifier' => 'p' . $detail->item_id,
    ); 
  }

  private function _proPaypalWithCashBondHalf($detail) {
    if (is_array($detail)) {
      $detail = json_decode(json_encode($detail), FALSE);
    }
    return array(
      'name' => $detail->item_desc . ' (('. $detail->rental_amt . ' x ' . $detail->qty . ')/ 2) + ' . $detail->item_cash_bond,
      'price' => (($detail->rental_amt * $detail->qty) / 2) + $detail->item_cash_bond,
      'identifier' => 'p' . $detail->item_id,
    );
  }

  private function _calTotalCashBond($details) {
    $total = 0;
    foreach ($details as $detail) {
      $total += $detail->item_cash_bond;
    }
    return $total;
  }
}