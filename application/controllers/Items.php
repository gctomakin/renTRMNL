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
				$this->_categoryProcess($itemId);
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
		$res = $this->_validate();
		if ($res['result']) {
			$data = $res['post'];
			$itemId = $this->input->post('id');
			if (!$this->Item->isExist($itemId, $this->session->userdata('lessor_id'))) {
				$res['result'] = FALSE;
				$res['message'] = "Item doesn't exist or lessor doesn't have privilege.";
			} else {
				$data[$this->Item->getId()] = $itemId;
				$this->Item->update($data);
				$this->_categoryProcess($itemId);
				$res['message'] = "Item Updated";
			}
		}
		echo json_encode($res);
	}

	public function delete() {
		$this->isAJax();
		$post = $this->input->post();
		$res['result'] = false;
		if (isset($post['id']) && is_numeric($post['id'])) {
			if ($this->Item->delete($post['id'])) {
				$res['result'] = true;
				$res['message'] = 'Delete Item # ' . $post['id'];
			} else {
				$res['message'] = 'Internal Server Error';
			}
		} else {
			$res['message'] = 'Invalid parameter';
		}
		echo json_encode($res);
	}

	public function detail() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = false;
		if (isset($post['id']) && is_numeric($post['id'])) {
			$this->load->model('ItemDetail');
			$content['detail'] = $this->ItemDetail->findByItem($post['id']);
			$content['itemId'] = $post['id'];
			$res['view'] = $this->load->view('pages/items/detail', $content, true);
			$res['result'] = true;
		} else {
			$res['message'] = "Invalid parameter";
		}
		echo json_encode($res);
	}

	public function detailSave() {
		$this->isAjax();
		$res['result'] = false;
		$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('size', 'Size', 'trim|required|xss_clean');
		$this->form_validation->set_rules('brand', 'Brand', 'trim|required|xss_clean');
		$this->form_validation->set_rules('color', 'Color', 'trim|required|xss_clean');
		$this->form_validation->set_rules('itemId', 'Item ID', 'trim|required|numeric|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$data['message'] = validation_erros();
		} else {
			$this->load->model('ItemDetail');
			$post = $this->input->post();
			$data = array(
				$this->ItemDetail->getType() => $post['type'],
				$this->ItemDetail->getSize() => $post['size'],
				$this->ItemDetail->getBrand() => $post['brand'],
				$this->ItemDetail->getColor() => $post['color'],
				$this->ItemDetail->getItemId() => $post['itemId']
			);
			if (empty($post['id'])) {
				if ($this->ItemDetail->create($data)) {
					$res['message'] = 'Added New Item Detail';
					$res['result'] = true;
				}
			} else {
				$data[$this->ItemDetail->getId()] = $post['id'];
				if ($this->ItemDetail->update($data)) {
					$res['message'] = 'Updated Item Detail';
					$res['result'] = true;
				}
			}
			if (!$res['result']) {
				$res['message'] = 'Internal Server Error';
			} else {
				$content['detail'] = $this->ItemDetail->findByItem($post['itemId']);
				$content['itemId'] = $post['itemId'];
				$res['view'] = $this->load->view('pages/items/detail', $content, true);	
			}
		}
		echo json_encode($res);
	}

	public function detailRemove() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = false;
		if (isset($post['id']) && is_numeric($post['id'])) {
			$this->load->model('ItemDetail');
			if ($this->ItemDetail->delete($post['id'])) {
				$res['result'] = true;
				$res['message'] = "Deleted Item Detail # {$post['id']}";
			} else {
				$res['message'] = "Internal Server Error";
			}
		} else {
			$res['message'] = "Invalid parameters";
		}
		echo json_encode($res);
	}

	public function shopItems() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;
		$this->form_validation->set_rules('shopId', 'Shop ID', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('start', 'Start Date', 'trim|required|date|xss_clean');
		$this->form_validation->set_rules('to', 'End Date', 'trim|required|date|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$res['message'] = validation_errors();
		} else {
			$shopItems = $this->Item->findByShop($post['shopId']);
			if (empty($shopItems)) {
				$res['message'] = 'No Item found';
			} else {
				$res['message'] = 'Here are the items';
				$res['result'] = TRUE;
				$res['items'] = array_map(array($this, '_processItem'), $shopItems);
			}
		}
		echo json_encode($res);
	}

	private function _processItem($obj) {
		if (is_array($obj)) {
			$obj = json_decode(json_encode($obj), FALSE);
		}
		$img = $obj->item_pic == NULL ? 'http://placehold.it/250x150' : 'data:image/jpeg;base64,' . base64_encode($obj->item_pic);
		return array(
			$this->Item->getId() => $obj->item_id,
			$this->Item->getRate() => $obj->item_rate,
			$this->Item->getPic() => $img,
			$this->Item->getStatus() => $obj->item_stats,
			$this->Item->getQty() => $obj->item_qty,
			$this->Item->getDesc() => $obj->item_desc,
			$this->Item->getCashBond() => $obj->item_cash_bond,
			$this->Item->getRentalMode() => $obj->item_rental_mode,
			$this->Item->getPenalty() => $obj->item_penalty,
			$this->Item->getShopId() => $obj->shop_id,
			$this->Item->getSubscriberId() => $obj->subscriber_id
		);
	}

	public function find() {
		$this->isAjax();
		$post = $this->input->post();
		$res['result'] = FALSE;
		$this->form_validation->set_rules('id', 'Item ID', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('start', 'Start Date', 'trim|required|date|xss_clean');
		$this->form_validation->set_rules('to', 'End Date', 'trim|required|date|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$res['message'] = validation_errors();
		} else {
			$items = $this->Item->findById($post['id']);
			if (empty($items)) {
				$res['message'] = 'No Item found';
			} else {
				$res['message'] = 'Here are the items';
				$res['result'] = TRUE;
				$res['items'] = array_map(array($this, '_processItem'), $items);
			}
		}
		echo json_encode($res);
	}

	private function _validate() {
		$this->isAjax();
		$this->form_validation->set_rules('shop', 'Shop', 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[2]|xss_clean');
		$this->form_validation->set_rules('rate', 'Rate', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('qty', 'Quantity', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('cashbond', 'Cash Bond', 'trim|required|numeric|xss_clean');
		$this->form_validation->set_rules('penalty', 'Penalty', 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('rentalmode', 'Rental Mode', 'trim|required|numeric|xss_clean');
		
		$data['result'] = false;

		if ($this->form_validation->run() == FALSE) {
			$data['message'] = validation_erros();
		} else if (!$this->session->has_userdata('lessor_id')) {
			$data['message'] = "Only lessor have the authority to process this.";
		} else {
			$data['message'] = $this->_validateImage();
			if (empty($data['message'])) {
				$post = $this->input->post();
				$data['post'] = array(
					$this->Item->getShopId() => empty($post['shop']) ? null : $post['shop'],
					$this->Item->getDesc() => $post['description'],
					$this->Item->getRate() => $post['rate'],
					$this->Item->getQty() => $post['qty'],
					$this->Item->getCashbond() => $post['cashbond'],
					$this->Item->getPenalty() => $post['penalty'],
					$this->Item->getRentalmode() => $post['rentalmode'],
					$this->Item->getSubscriberId() => $this->session->userdata('lessor_id'),
					$this->Item->getStatus() => 'active'
				);
				if (isset($_FILES['picture'])) {
					$picture = file_get_contents($_FILES['picture']['tmp_name']);
					$data['post'][$this->Item->getPic()] = $picture;
				}
				$data['result'] = true;
			}
		}
		return $data;
	}

	private function _categoryProcess($itemId) {
		$categories = $this->input->post('category');
			if (count($categories) > 0) { // Check if there are any categories
				$this->load->model('ItemCategory');
				$this->load->model('Category');
				$this->ItemCategory->deleteAllItem($itemId); // For binding ItemCategories
				foreach ($categories as $category) {
					if (!$this->Category->isExist($category)) { // Check if category exists
						$category = $this->Category->create( // Otherwise Create
							array($this->Category->getType() => $category)
						);
					}
					$this->ItemCategory->create(array(
							$this->ItemCategory->getItemId() => $itemId,
							$this->ItemCategory->getCategoryId() => $category,
						)
					);
				}	
			}
	}

	private function _validateImage() {
		$message = '';
		if (!empty($_FILES['picture']) && $_FILES['picture']['size'] != 0) { // check if image has been upload
			$this->load->library('MyFile', $_FILES['picture']); // Load My File Library
			$validate = $this->myfile->validateImage(); // Validate Image
			if (!$validate['result']) {
				$message = implode(', ', $validate['message']); // Specify Image validate errors
			}
		}
		return $message;
	}

}