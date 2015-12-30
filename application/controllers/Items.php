<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

	public function __construct() {
		parent::__construct(3);
		$this->load->model('Item');
	}

	public function create() {
		$res = $this->_validate();
		if ($res['result']) {
			$data = $res['post'];
			$itemId = $this->Item->create($data);
			if ($itemId > 0) {
				$categories = $this->input->post('category');
				if (count($categories) > 0) { // Check if there are any categories
					$this->load->model('ItemCategory');
					$this->load->model('Category');
					$this->ItemCategory->deleteAllItem($itemId); // For binding ItemCategories
					foreach ($categories as $category) {
						if (!$this->Category->isExist($category)) { // Check if category exists
							$category = $this->Category->create( // Otherwise Create
								array($this->Category->getType => $category)
							);
						}
						$this->ItemCategory->create(array(
								$this->ItemCategory->getItemId() => $itemId,
								$this->ItemCategory->getCategoryId() => $category,
							)
						);
					}	
				}
				$res['message'] = "New Item Created";
				$res['reset'] = TRUE;
			} else {
				$res['message'] = 'Internal Server Error.';
				$res['result'] = FALSE;
			}
		}
		echo json_encode($res);
	}

	public function update() {

	}

	public function delete() {

	}

	private function _validate() {
		$this->isAjax();
		$this->form_validation->set_rules('shop', 'Shop', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[2]|xss_clean');
		$this->form_validation->set_rules('rate', 'Rate', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('qty', 'Quantity', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('cashbond', 'Cash Bond', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('penalty', 'Penalty', 'trim|xss_clean');
		$this->form_validation->set_rules('rentalmode', 'Rental Mode', 'trim|required|numeric|xss_clean');
		
		$data['result'] = false;

		if ($this->form_validation->run() == FALSE) {
			$data['message'] = validation_erros();
		} else if (!$this->session->has_userdata('lessor_id')) {
			$data['message'] = "Only lessor have the authority to process this.";
		} else {
			$post = $this->input->post();
			$data['post'] = array(
				$this->Item->getShopId() => $post['shop'],
				$this->Item->getDesc() => $post['description'],
				$this->Item->getRate() => $post['rate'],
				$this->Item->getQty() => $post['qty'],
				$this->Item->getCashbond() => $post['cashbond'],
				$this->Item->getPenalty() => $post['penalty'],
				$this->Item->getRentalmode() => $post['rentalmode'],
				$this->Item->getSubscriberId() => $this->session->userdata('lessor_id'),
				$this->Item->getStatus() => 'active'
			);
			$data['result'] = true;
		}
		return $data;
	}

}