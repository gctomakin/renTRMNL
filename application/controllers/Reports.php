<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

  public function __construct() {
    parent::__construct(4);
  }

  public function subscriptions() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;
		if ($post['startDate'] && $post['endDate']) {
			$this->load->library('MyDateTime', array($post['startDate'], $post['endDate']));
			$this->load->model('Subscription');
			
			$res = $this->mydatetime->getInterval();
			
			for ($i = 0; $i < count($res['date']); $i++) {
				if (($i + 1) < count($res['date'])) {
					$to = $res['date'][$i + 1];
				} else if ($res['type'] == 'hours') {
					$to = '';
				} else {
					$to = $res['date'][$i];
				}
				$from = $res['date'][$i];
				$subs = $this->Subscription->countTotalByDate($from, $to);
				$label = $post['startDate'] == $post['endDate'] ? date('H:i:s', strtotime($from)) : date('Y-m-d' , strtotime($from));
				$res['intervals'][$label] = $subs;
			}

			$details['subscriptions'] = $this->Subscription->findByDate($post['startDate'], $post['endDate']);
			$res['result'] = TRUE;
			//$res['difference'] = $this->mydatetime->getDifference()->days;
			$res['details'] = $this->load->view('pages/admin/reports/subscriptionDetails', $details, TRUE);
		} else {
			$res['message'] = 'Invalid parameter';
		}
		echo json_encode($res);
	}
}