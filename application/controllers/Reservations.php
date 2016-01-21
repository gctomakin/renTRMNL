<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservations extends CI_Controller {

	public function __construct() {
      parent::__construct(3);
  }

  public function item($itemId = "") {
  	$this->load->model('Item');
  	$content['item'] = $this->Item->findById($itemId);
		$content['itemPic'] = $content['item']['item_pic'] == NULL ? 'http://placehold.it/150x100' : 'data:image/jpeg;base64,'.base64_encode($content['item']['item_pic']);
				  
  	if (empty($content['item'])) {
  		redirect(site_url('lessee/items'));
  	} else {
	  	$data['title'] = "ITEM RESERVATION";
	  	$data['content'] = $this->load->view('pages/reservations/form', $content, TRUE);
	  	$data['script'] = array(
	  		'libs/pnotify.core',
	      'libs/pnotify.buttons',
	      'libs/select2.min',
	      'libs/moment.min2',
	      'libs/daterangepicker',
	      'libs/single-datepicker',
	      'pages/reservations/form'
	    );
	    $data['style'] = array(
	    	'libs/pnotify',
	    	'libs/datepicker',
	    	'libs/select2.min'
	    );
	    $this->load->view('common/lessee', $data);
	  }
  }

}