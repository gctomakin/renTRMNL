<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ReservationDetail extends CI_Model{

	private $table = 'reservation_details';
	private $reserveDetailId = 'reserve_detail_id';
	private $rentalAmt = 'rental_amt';
	private $qty = 'qty';
	private $itemId = 'item_id';
	private $reserveId = 'reserve_id';

	public $data = array(
		'reserve_detail_id' => '',
		'rental_amt' => '',
		'qty' => '',
		'item_id' => '',
		'reserve_id' => ''
	);


	public function clean() {
		foreach ($this->data as $key => $value) {
			$this->data[$this->key] = '';
		}
	}

	public function create() {
		$data = array_filter($this->data);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function delete() {
		if (empty($this->data[$this->id])) { 
			return 0;
		} else {
			$this->db->delete($this->table, array(
					$this->id => $this->data[$this->id]
				)
			);
			return $this->db->affected_rows();	
		}
	}

	// GETTERS AND SETTERS
	public function getReserveDetailId() { return $this->reserveDetailId; }
	public function getRentalAmt() { return $this->rentalAmt; }
	public function getQty() { return $this->qty; }
	public function getItemId() { return $this->itemId; }
	public function getReserveId() { return $this->reserveId; }

	public function setReserveDetailId($value) { $this->data[$this->reserveDetailId] = $value; }
	public function setRentalAmt($value) { $this->data[$this->rentalAmt] = $value; }
	public function setQty($value) { $this->data[$this->qty] = $value; }
	public function setItemId($value) { $this->data[$this->itemId] = $value; }
	public function setReserveId($value) { $this->data[$this->reserveId] = $value; }
}