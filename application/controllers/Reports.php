<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

  public function __construct() {
    parent::__construct(4);
  }

  public function subscriptions() {
		$res = $this->_validate();
		if ($res['result']) {			
			$this->load->model('Subscription');
			for ($i = 0; $i < count($res['date']); $i++) {
				$dates = $this->_getFromTo($res['date'], $i, $res['type']);
				$subs = $this->Subscription->countTotalByDate($dates['from'], $dates['to']);
				$label = $res['startDate'] == $res['endDate'] ? date('H:i:s', strtotime($dates['from'])) : date('Y-m-d' , strtotime($dates['from']));
				$res['intervals'][$label] = $subs;
			}

			$details['subscriptions'] = $this->Subscription->findByDate($res['startDate'], $res['endDate']);
			$res['details'] = $this->load->view('pages/admin/reports/subscriptionDetails', $details, TRUE);
		}
		echo json_encode($res);
	}

	public function users() {
		$res = $this->_validate();
		if ($res['result']) {			
			$this->load->model('Subscriber');
			$this->load->model('Lessee');
			for ($i = 0; $i < count($res['date']); $i++) {
				$dates = $this->_getFromTo($res['date'], $i, $res['type']);
				$label = $res['startDate'] == $res['endDate'] ? date('H:i:s', strtotime($dates['from'])) : date('M d' , strtotime($dates['from']));
	
				$res['labels'][] = $label;
				// Count lessors		
				$subs = $this->Subscriber->countTotalByDate($dates['from'], $dates['to']);
				$res['lessor']['intervals'][] = $subs;

				// Count Lessee
				$lessees = $this->Lessee->countTotalByDate($dates['from'], $dates['to']);
				$res['lessee']['intervals'][] = $lessees;
			}
			$details['lessors'] = $this->Subscriber->findByDate($res['startDate'], $res['endDate']);
			$details['lessees'] = $this->Lessee->findByDate($res['startDate'], $res['endDate']);
			$res['details'] = $this->load->view('pages/admin/reports/userDetails', $details, TRUE);
		}
		echo json_encode($res);
	}

	public function rentals() {
		$res = $this->_validate();
		if ($res['result']) {			
			$this->load->model('Reservation');
			for ($i = 0; $i < count($res['date']); $i++) {
				$dates = $this->_getFromTo($res['date'], $i, $res['type']);
				$label = $res['startDate'] == $res['endDate'] ? date('H:i:s', strtotime($dates['from'])) : date('M d' , strtotime($dates['from']));
	
				$res['labels'][] = $label;
				// Count Reservation
				$reservation = $this->Reservation->countTotalByDate($dates['from'], $dates['to']);
				$res['intervals'][$label] = $reservation;

			}
			// $details['lessors'] = $this->Subscriber->findByDate($res['startDate'], $res['endDate']);
			// $res['details'] = $this->load->view('pages/admin/reports/userDetails', $details, TRUE);
		}
		echo json_encode($res);
	}

	private function _validate() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;
		if ($post['startDate'] && $post['endDate']) {
			$this->load->library('MyDateTime', array($post['startDate'], $post['endDate']));
			$res = $this->mydatetime->getInterval();
			$res['result'] = TRUE;
			$res['startDate'] = $post['startDate'];
			$res['endDate'] = $post['endDate'];
		} else {
			$res['message'] = 'Invalid parameter';
		}
		return $res;
	}

	private function _getFromTo($date, $index, $type) {
		if (($index + 1) < count($date)) {
			// minus 1 second for interval date;
			$to = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($date[$index + 1])));
		} else if ($type == 'hours') {
			$to = '';
		} else {
			$to = $date[$index];
		}
		$from = $date[$index];
		return array('from' => $from, 'to' => $to);
	}
}