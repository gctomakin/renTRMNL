<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
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
			empty($post['message'])
		) {
			$res['message'] = 'Invalid Parameter';
		} else if (!is_numeric($post['to']) || !is_numeric($post['from'])) {
			$res['message'] = 'To and From must be numeric';
		} else if (
			$post['toType'] != 'lessor' || $post['toType'] != 'lessee' ||
			$post['fromType'] != 'lessor' || $post['fromType'] != 'lessee'
		) {
			$res['message'] = 'To and from type is invalid';
		} else {
			$link = if ($fromType == 'lessor') ?
				site_url('lessor/message?lessor=' . $post['from']) :
				site_url('lessor/message?lessee=' . $post['from']);
			$link .= '&message=' . urlencode($post['message']);
			
			$this->load->library('MyPusher');
			$data = array(
	      'to' => $post['to'],
	      'from' => $post['from'],
	      'toType' => $post['toType'],
	      'fromType' => $post['fromType'],
	      'date' => date('M/d/Y H:i:s'),
	      'message' => $post['message'],
	      'link' => $link
	    );
	    $this->mypusher->Message('message-channel', 'message-event', $data);
			$res['result'] = TRUE;
		}
		echo json_encode($res);
	}
}