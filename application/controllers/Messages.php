<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
		$this->load->model('Message');
	}

	public function send() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;

		if (
			empty($post['to']) ||
			empty($post['from']) ||
			empty($post['toType']) ||
			empty($post['fromType']) ||
			empty($post['message']) ||
			empty($post['name'])
		) {
			$res['message'] = 'Invalid Parameter';
		} else if (!is_numeric($post['to']) || !is_numeric($post['from'])) {
			$res['message'] = 'To and From must be numeric';
		} else if (
			!(
				$post['toType'] == 'lessor' || $post['toType'] == 'lessee' ||
				$post['fromType'] == 'lessor' || $post['fromType'] == 'lessee'
			)
		) {
			$res['message'] = 'To and from type is invalid';
		} else {
			$link = ($post['fromType'] == 'lessor') ?
				site_url('lessee/message?lessor=' . $post['from']) :
				site_url('lessor/message?lessee=' . $post['from']);
			// $link .= '&message=' . urlencode($post['message']);
			
			$this->load->library('MyPusher');
			$data = array(
	      'to' => $post['to'],
	      'from' => $post['from'],
	      'toType' => $post['toType'],
	      'fromType' => $post['fromType'],
	      'date' => date('M/d/Y H:i:s'),
	      'message' => $post['message'],
	      'link' => $link,
	      'name' => $this->session->userdata('first_name')
	    );
	    $this->mypusher->Message('chat-channel', 'chat-event', $data);
	    $messageData = array(
				$this->Message->getMessage() => $post['message'],
				$this->Message->getFromId() => $post['from'],
				$this->Message->getToId() => $post['to'],
				$this->Message->getFromType() => $post['fromType'],
				$this->Message->getToType() => $post['toType'],
				$this->Message->getStatus() => 'active',
				$this->Message->getSent() => date('Y-m-d H:i:s'),
			);
			$this->Message->create($messageData);
			$res['result'] = TRUE;
		}
		echo json_encode($res);
	}

	public function conversation() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;

		if (
			empty($post['lesseeId']) ||
			empty($post['lessorId']) ||
			empty($post['name'])
		) {
			$res['message'] = 'Invalid Parameter';
		} else {
			$content['messages'] = $this->Message->findByConversation(
				$post['lesseeId'],
				$post['lessorId']
			);
			$content['conName'] = $post['name'];
			$res['view'] = $this->load->view('templates/message/conversation', $content, TRUE);
			$res['result'] = TRUE;
		}
		echo json_encode($res);
	}
}