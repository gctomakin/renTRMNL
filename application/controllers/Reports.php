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
				if (($i + 1) < count($res['date'])) {
					// minus 1 second for interval date;
					$to = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($res['date'][$i + 1])));
				} else if ($res['type'] == 'hours') {
					$to = '';
				} else {
					$to = $res['date'][$i];
				}
				$from = $res['date'][$i];
				$subs = $this->Subscription->countTotalByDate($from, $to);
				$label = $res['startDate'] == $res['endDate'] ? date('H:i:s', strtotime($from)) : date('Y-m-d' , strtotime($from));
				$res['intervals'][$label] = $subs;
			}

			$details['subscriptions'] = $this->Subscription->findByDate($res['startDate'], $res['endDate']);
			$res['details'] = $this->load->view('pages/admin/reports/subscriptionDetails', $details, TRUE);
		}
		echo json_encode($res);
	}

	public function lessors() {
		$res = $this->_validate();
		if ($res['result']) {			
			$this->load->model('Subscriber');
			for ($i = 0; $i < count($res['date']); $i++) {
				if (($i + 1) < count($res['date'])) {
					// minus 1 second for interval date;
					$to = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($res['date'][$i + 1])));
				} else if ($res['type'] == 'hours') {
					$to = '';
				} else {
					$to = $res['date'][$i];
				}
				$from = $res['date'][$i];
				$subs = $this->Subscriber->countTotalByDate($from, $to);
				$label = $res['startDate'] == $res['endDate'] ? date('H:i:s', strtotime($from)) : date('Y-m-d' , strtotime($from));
				$res['intervals'][$label] = $subs;
			}
			$details['subscribers'] = $this->Subscriber->findByDate($res['startDate'], $res['endDate']);
			$res['details'] = $this->load->view('pages/admin/reports/lessorDetails', $details, TRUE);
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
}