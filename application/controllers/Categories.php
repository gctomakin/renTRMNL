<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
		$this->load->model('Category');
	}

	public function all() {
		$this->isAjax();
		$key = $this->input->get('q');
		$categories = $this->Category->all('*', $key);
		$data = array('items' => array_map(array($this, 'mapCategories'), $categories));
		echo json_encode($data);
	}

	private function mapCategories($data) {
		return array(
			'id' => $data->category_id,
			'text' => $data->category_type
		);
	}
}