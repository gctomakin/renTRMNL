<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriptions extends CI_Controller {

	private $rentrmnlEmail = 'rentrmnl_seller@gmail.com';

	public function __construct() {
		parent::__construct(21);
   	$this->load->model('Subscription');
  }

  public function index() {
  	redirect('subscriptions/entry');
  }

	public function entry() {
		$this->_isActive();
		$this->load->model('SubscriptionPlan');
		$content['plans'] = $this->SubscriptionPlan->all();
		$data['content'] = $this->load->view('pages/subscriptions/entry', $content, true);
		$data['style'] = array(
			'libs/price-table',
			'libs/form-wizard'
		);
		$data['script'] = array(
			'libs/jquery.smartWizard',
			'libs/pnotify.core',
      'libs/pnotify.buttons',
			'common',
			'pages/subscriptions/entry'
		);
		$this->load->view('common/plain', $data);
	}

	public function processPay() {
		$post = $this->input->post();
		$res['result'] = FALSE;
		if (isset($post['id']) && is_numeric($post['id'])) {
			$this->load->model('SubscriptionPlan');
			$plan = $this->SubscriptionPlan->findById($post['id']);
			if (empty($plan)) {
				$res['message'] = 'Plan ID not found';
			} else {
				$this->load->library('Paypal');
				$res['split_pay'] = $this->paypal->createPacket($plan['plan_rate'], $this->rentrmnlEmail);
				if ($res['split_pay'] === FALSE) {
					$res['message'] = 'Cannot get split pay';
				} else {
					$res['result'] = TRUE;
				}
			}
		} else {
			$res['message'] = 'Invalid parameters';
		}
		echo json_encode($res);
	}

	public function confirmPay() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = false;
		if (
			isset($post['paykey']) &&
			isset($post['plan_id']) && 
			is_numeric($post['plan_id'])
		) {
			$this->load->model('SubscriptionPlan');
			$plan = $this->SubscriptionPlan->findById($post['plan_id']);
			if (empty($plan)) {
				$res['message'] = 'Subscription Plan Not Found.';
			} else {
				$this->load->library('Paypal');
				$res['details'] = $this->paypal->getDetails($post['paykey']);
				if ($res['details']['status'] == 'COMPLETED') {
					$from = date('Y-m-d H:i:s');
					$to = date('Y-m-d H:i:s', strtotime('+' . $plan['plan_duration'] . 'days'));
					$data = array(
						$this->Subscription->getStartDate() => $from,
						$this->Subscription->getEndDate() => $to,
						$this->Subscription->getAmount() => $plan['plan_rate'],
						$this->Subscription->getStatus() => 'pending',
						$this->Subscription->getQty() => '1',
						$this->Subscription->getSubscriberId() => $this->session->userdata('lessor_id'),
						$this->Subscription->getPlanId() => $plan['plan_id']
					);
					$subId = $this->Subscription->create($data);
					if ($subId > 0) {
						$message = "New Subscription # $subId from" . $this->session->userdata('lessor_fullname');
						$this->load->library('MyPusher');
				    $notification = array(
				      'usertype' => 'admin',
				      'date' => date('Y/m/d'),
				      'notification' => $message,
				      'link' => site_url('admin/subscriptions/pending')
				    );
				    $this->mypusher->Message('top-notify-channel', 'top-notify-all-event', $notification);

						$data['plan_name'] = $plan['plan_name'];
						$info['sub'] = $data;
						$res['view'] = $this->load->view('pages/subscriptions/information', $info, TRUE);
						$res['result'] = true;
					} else {
						$res['message'] = 'Internal Server Error';
					}
				} else {
					$res['message'] = 'No Transaction found.';
				}
			}
		} else {
			$res['message'] = 'Invalid parameters';
		}
		echo json_encode($res);
	}

	public function add() {
		$data['content'] = $this->load->view('pages/subscriptions/add', '', TRUE);
		$this->load->view('common/plain', $data);
	}

	public function cancel() {
		$data['content'] = $this->load->view('pages/subscriptions/cancel', '', TRUE);
		$this->load->view('common/plain', $data);
	}

	public function activate() {
		echo $this->_changeStatus('active');
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
			$subs = $this->Subscription->findById($post['id']);
			if (empty($subs)) {
				$res['message'] = "Subscription not found";
			} else {
				$data = array(
					$this->Subscription->getId() => $post['id'],
					$this->Subscription->getStatus() => $status 
				);
				$this->load->model('Subscriber');
					
				if ($status == 'active') {
					$from = date('Y-m-d H:i:s');
					$to = date('Y-m-d H:i:s', strtotime('+' . $subs['plan_duration'] . 'days'));
					$data[$this->Subscription->getStartDate()] = $from;
					$data[$this->Subscription->getEndDate()] = $to;

					$subData = array(
						$this->Subscriber->getId() => $subs[$this->Subscription->getSubscriberId()],
						$this->Subscriber->getStatus() => $status
					);
					$this->Subscriber->update($subData);
				}

				if ($this->Subscription->update($data)) {
					$res['result'] = TRUE;
					$status = $status == 'active' ? 'Activated' : ucfirst($status) . "d";
					$res['message'] = "Subscription is " . $status;
					$txtMessage = "Your Subscription # {$post['id']} has been $status.";
					$subId = $subs[$this->Subscription->getSubscriberId()];
					$this->Subscriber->sendSMSToSubId($subId, $txtMessage);
				} else {
					$res['message'] = 'Internal Server Error';
				}
			}
		}
		return json_encode($res);
	}

	private function _isActive() {
		$lessorId = $this->session->userdata('lessor_id');
		$subs = $this->Subscription->findActiveBySubscriberId($lessorId);
		if (!empty($subs)) {
			redirect('lessors');
			exit();
		}
	}
}