<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation extends CI_Model{

	private $table = "rental_reservations";
	private $id = "reserve_id";
	private $date = "reserve_date";
	private $dateRented = "date_rented";
	private $dateReturned = "date_returned";
	private $totalAmt = "total_amt";
	private $downPayment = "down_payment";
	private $totalBalance = "total_balance";
	private $penalty = "penalty";
	private $status = "status";
	private $lesseeId = "lessee_id";

	private $data = array(
		"reserve_id" => "",
		"reserve_date" => "",
		"date_rented" => "",
		"date_returned" => "",
		"total_amt" => "",
		"down_payment" => "",
		"total_balance" => "",
		"penalty" => "",
		"status" => "",
		"lessee_id"=> ""
	);

	public function clean() {
		foreach($this->data as $key => $value) {
			$this->data[$key] = "";
		}
	}

	public function create() {
		$data = array_filter($this->data);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update() {
		$data = array_filter($this->data);
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		return $this->db->affected_rows();
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

	public function all() {
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function find() {
		$data = array_filter($this->data);
		$query = $this->db->get_where($this->table, $data);
		return $query->result();
	}

	// GETTERS AND SETTERS

	public function getId() { return $this->id; }
	public function getDate() { return $this->date; }
	public function getDateRented() { return $this->dateRented; }
	public function getDateReturned() { return $this->dateReturned; }
	public function getTotalAmt() { return $this->totalAmt; }
	public function getDownPayment() { return $this->downPayment; }
	public function getTotalBalance() { return $this->totalBalance; }
	public function getPenalty() { return $this->penalty; }
	public function getStatus() { return $this->status; }
	public function getLesseeId() { return $this->lesseeId; }

	public function setId($value) { $this->data[$this->id] = $value; }
	public function setDate($value) { $this->data[$this->date] = $value; }
	public function setDateRented($value) { $this->data[$this->dateRented] = $value; }
	public function setDateReturned($value) { $this->data[$this->dateReturned] = $value; }
	public function setTotalAmt($value) { $this->data[$this->totalAmt] = $value; }
	public function setDownPayment($value) { $this->data[$this->downPayment] = $value; }
	public function setTotalBalance($value) { $this->data[$this->totalBalance] = $value; }
	public function setPenalty($value) { $this->data[$this->penalty] = $value; }
	public function setStatus($value) { $this->data[$this->status] = $value; }
	public function setLesseeId($value) { $this->data[$this->lesseeId] = $value; }


}