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
          if ($type != 'Full') {
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

  public function item() {
    $this->isAjax();
    $res['result'] = FALSE;
    $post = $this->input->post();
    $this->load->model('Subscriber');
    $this->load->model('Item');
    
    $paypalCol = $this->Subscriber->getPaypal();
    
    $item = $this->Item->findById($post['details'][0]['id']);
    $available = $this->_checkItemAvailable(
      $post['details'][0]['id'],
      $post['details'][0]['qty'],
      date('Y-m-d H:i:s'),
      $post['to']
    );
    $subscriber = $this->Subscriber->findId(
      $item['subscriber_id'], array($paypalCol)
    );
    $email = $subscriber[$paypalCol];

    if (empty($email)) {
      $res['message'] = 'Lessor does not have paypal account, please contact him for reservation';
    } else if ($available['available'] == FALSE) {
      $res['message'] = $available['message'];
    } else {
      $details = array( 0 => (object)array(
          'item_desc' => $item['item_desc'],
          'item_name' => $item['item_name'],
          'rental_amt' => $post['details'][0]['amount'],
          'qty' => $post['details'][0]['qty'],
          'item_cash_bond' => $item['item_cash_bond'],
          'item_id' => $item['item_id']
        )
      );
      $this->load->library('Paypal');
      $type = ucfirst($post['type']);
      $cashbond = 0;
      $full = $post['details'][0]['amount'] * $post['details'][0]['qty'];
      $total = ($type != 'Full') ? $full / 2 : $full;//$reservation[$this->Reservation->getTotalAmt()];
      $pDetails = array_map(array($this, '_proPaypalWithCashBond' . $type), $details);
      $cashbond = $this->_calTotalCashBond($details);
      $type .= " With Cashbonds";
      $res['paypal'] = $this->paypal->createPacketDetail(
        $total + $cashbond,
        $email,
        $pDetails,
        $type,
        'rental/returnItemPaypal', // Return
        'rental/cancelPaypal' // Cancel
      );
      $data = array(
        'to' => $post['to'],
        'total' => $full,
        'details' => $post['details'],
        'subscriber' => $post['subscriber'],
        'status' => $post['status']
      );
      $this->session->set_flashdata('reservation', $data);
      $this->session->set_flashdata('reservation_payment', $total);
      $this->session->set_flashdata('reservation_type', $type);
      $this->session->set_flashdata('is_paypal', TRUE);
      $res['email'] = $email;
      $res['result'] = TRUE;
    }
    echo json_encode($res);
  }

  public function returnItemPaypal() {
    $paypal = $this->session->flashdata();
    if (empty($paypal['is_paypal'])) {
      redirect('lessees');
      exit();
    }
    $this->load->model('Reservation');

    $this->Reservation->setDate(date('Y-m-d H:i:s'));
    $from = empty($paypal['reservation']['from']) ?
      date('Y-m-d H:i:s') :
      $paypal['reservation']['from'];
    $this->Reservation->setDateRented(date('Y-m-d H:i:s', strtotime($from)));
    $this->Reservation->setDateReturned(date('Y-m-d H:i:s', strtotime($paypal['reservation']['to'])));
    $this->Reservation->setTotalAmt($paypal['reservation']['total']);
    $this->Reservation->setDownPayment($paypal['reservation']['total']/2);
    $this->Reservation->setTotalBalance($paypal['reservation']['total']);
    $this->Reservation->setPenalty(0);
    $status = empty($paypal['reservation']['status']) ?
      'pending' :$paypal['reservation']['status']; 
    $this->Reservation->setStatus($status);
    $this->Reservation->setLesseeId($this->session->userdata('lessee_id'));
    $this->Reservation->setSubscriberId($paypal['reservation']['subscriber']);
    $id = $this->Reservation->create();
    $res['result'] = FALSE;
    $content['message'] = "";
    if ($id > 0) {
      $this->load->model('ReservationDetail');
      $this->load->model('Item');
      $error = array();
      $itemRented = array();
      foreach ($paypal['reservation']['details'] as $detail) {
        $this->ReservationDetail->setRentalAmt($detail['amount']);
        $this->ReservationDetail->setQty($detail['qty']);
        $this->ReservationDetail->setItemId($detail['id']);
        $this->ReservationDetail->setReserveId($id);
        if ($this->ReservationDetail->create() <= 0) {
         $error[] = 'Error on item # ' . $detail['id'];
        }
        $item = $this->Item->findById($detail['id'], array($this->Item->getName()));
        $itemRented[] = $item[$this->Item->getName()];
      }
      if (empty($error)) {
        $this->load->model('Subscriber');
        $message = 'Your Item ' . implode(', ', $itemRented) . ' is/are rented.';
        $this->Subscriber->sendSMSToSubId($paypal['reservation']['subscriber'], $message);

        $this->session->set_flashdata('is_paypal', TRUE);
        $this->session->set_flashdata('reservation_id', $id);
        $this->session->set_flashdata('reservation_payment', $paypal['reservation_payment']);
        $this->session->set_flashdata('reservation_type', $paypal['reservation_type']);
        $this->returnPaypal();
      } else {
        $content['message'] = "ERROR ON RESERVATION DETAIL";
      }
    } else {
      $content['message'] = "ERROR ON RESERVATION";
    }
    if (!empty($content['message'])) {
      $data['content'] = $this->load->view('pages/paypal/return', $content, TRUE);
      $this->load->view('common/plain', $data);
    }
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
          $this->load->library('MyPusher');
          $message = 'Rental Payment for Reservation # '. $paypal['reservation_id'];
          $message .= ' <br> <b>' . $paypal['reservation_type'] . ' Payment</b>';
          $link = 'lessor/payments/pending?id=' . $paypal['reservation_id'];
          $notification = array(
            'usertype' => 'lessor',
            'date' => date('Y/m/d'),
            'receiver' => $reservation[$this->Reservation->getSubscriberId()],
            'notification' => $message,
            'sender' => $this->session->userdata('lessee_fname'),
            'link' => site_url($link)
          );
          $this->mypusher->Message('top-notify-channel', 'top-notify-event', $notification);

          // INSERT TO DB
          $this->load->model('Notification');
          $data = array(
            $this->Notification->getFromId() => $this->session->userdata('lessee_id'),
            $this->Notification->getToId() => $reservation[$this->Reservation->getSubscriberId()],
            $this->Notification->getFromType() => 'lessee',
            $this->Notification->getToType() => 'lessor',
            $this->Notification->getSent() => date('Y-m-d H:i:s'),
            $this->Notification->getLink() => $link,
            $this->Notification->getNotification() => $message,
            $this->Notification->getStatus() => 'pending'
          );
          $this->Notification->create($data);
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
        $resId = $payment[$this->RentalPayment->getReserveId()];
        if ($status == 'cancel') {
          // UPDATE Reservation return balance from payment
          $amt = $payment[$this->RentalPayment->getAmount()];
          $res['message'] = $this->_addToResBalance($resId, $amt);       
        }

        if (empty($res['message'])) {
          $this->RentalPayment->setId($post['id']);
          $this->RentalPayment->setStatus($status);
          if ($this->RentalPayment->update()) {
            $res['result'] = TRUE;
            $res['message'] = 'Payment has been ' . ucfirst($status);
            $message = "Payment for Reservation # $resId has been " . ucfirst($status);
            $link = 'lessee/reserved?id=' . $resId;
            $this->load->model('Reservation');
            $reservation = $this->Reservation->findById($resId);
            $this->load->library('MyPusher');
            $notification = array(
              'usertype' => 'lessee',
              'date' => date('Y/m/d'),
              'receiver' => $reservation[$this->Reservation->getLesseeId()],
              'notification' => $message,
              'sender' => $this->session->userdata('lessor_fullname'),
              'link' => site_url($link)
            );
            $this->mypusher->Message('top-notify-channel', 'top-notify-event', $notification);
            $this->load->model('Notification');
            $data = array(
              $this->Notification->getFromId() => $this->session->userdata('lessor_id'),
              $this->Notification->getToId() => $reservation[$this->Reservation->getLesseeId()],
              $this->Notification->getFromType() => 'lessor',
              $this->Notification->getToType() => 'lessee',
              $this->Notification->getSent() => date('Y-m-d H:i:s'),
              $this->Notification->getLink() => $link,
              $this->Notification->getNotification() => $message,
              $this->Notification->getStatus() => 'pending'
            );
            $this->Notification->create($data);
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


  public function checkItemRented() {
    $this->isAjax();
    $post = $this->input->post();
    $res['result'] = FALSE;
    if (empty($post['itemId']) && !is_numeric($post['itemId'])) {
      $res['message'] = 'Invalid Parameter';
    } else {
      $date = empty($post['dateFrom']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($post['dateFrom']));
      $itemQty = empty($post['itemQty']) ? '' : $post['itemQty'];
      $res = $this->_checkItemAvailable($post['itemId'], $itemQty, $date);
    }
    echo json_encode($res);
  }

  // PRIVATE METHODS
  private function _checkItemAvailable($itemId, $itemQty = "", $from = "", $to = "") {
    $this->load->model('Reservation');
    $this->load->model('Item');
    $item = $this->Item->findById($itemId);
    $res['result'] = FALSE;
    $res['available'] = FALSE;
    if (empty($item)) {
      $res['message'] = 'Item not exist';
    } else {
      $rentedItem = $this->Reservation->countRentedItem($itemId, $from, $to);
      if (
        (!empty($rentedItem['rented'])) && (
          (!empty($itemQty) && $itemQty > $rentedItem['rented']) ||
          ($rentedItem['rented'] >= $item[$this->Item->getQty()])
        )
      ) {
        $res['message'] = "Not available yet"; 
        //. $rentedItem['rented'] . '-' . $item[$this->Item->getQty()] . '@' . $itemQty;
      } else {
        $res['available'] = TRUE;
      }
      $res['result'] = TRUE;
    }
    return $res;
  }

  private function _addToResBalance($resId, $payment) {
    $this->load->model('Reservation');
          
    $reservation = $this->Reservation->findById($resId);
    $resBalance = $reservation[$this->Reservation->getTotalBalance()];

    $balance = $resBalance + $payment; 

    $this->Reservation->setId($resId);
    $this->Reservation->setTotalBalance($balance);
    $this->Reservation->setStatus('approve');

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

  private function _proPaypalWithCashBondFull($detail) {
    if (is_array($detail)) {
      $detail = (object)$detail;
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
    $details = (object) $details;
    $total = 0;
    foreach ($details as $detail) {
      $total += $detail->item_cash_bond;
    }
    return $total;
  }
}