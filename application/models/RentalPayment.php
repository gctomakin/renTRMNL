<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RentalPayment extends CI_Model{

	private $table = "rental_payments";

	private $id = "payment_id";
	private $amount = "payment_amt";
	private $date = "payment_date";
	private $description = "payment_description";
	private $reserveId = "reserve_id";
	private $status = "payment_status";

	private $data = array(
		"payment_id" => "",
		"payment_amt" => "",
		"payment_date" => "",
		"payment_description" => "",
		"reserve_id" => "",
		"payment_status" => ""
	);

	public function clean() {
		foreach($this->data as $key => $value) {
			$this->data[$key] = "";
		}
	}

	public function create() {
		$data = array_filter($this->data);
		$this->db->insert($this->table, $data);
		$this->deleteCache();
		return $this->db->insert_id();
	}

	public function update() {
		$data = array_filter($this->data);
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		$this->deleteCache();
		return $this->db->affected_rows();
	}

	public function delete($id) {
		$this->db->delete($this->table, array(
				$this->id => $id
			)
		);
		$this->deleteCache();
		return $this->db->affected_rows();	
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

	public function findById($id, $status = "") {
		$where =  array($this->id => $id);
		if (!empty($status)) {
			$where[$this->status] = $status;
		}
		$query = $this->db->get_where($this->table, $where);
		return $query->row_array();
	}

	public function findPendingByLessorId($lessorId, $status = "") {
		$joinRes = $this->_joinReservation();
		$joinCon = " AND " . $this->rAlias . "." . $this->Reservation->getSubscriberId() . " = $lessorId";
		if (!empty($status)) {
			$this->db->where($this->status, $status);
		}
		$query = $this->db
			->select($this->rpAlias . '.*')
			->from($this->table . ' as ' . $this->rpAlias)
			->join($joinRes['table'], $joinRes['on'] . $joinCon, 'INNER')
			->get();

		return $query->result();
		
	}

	public function findByReservationId($id) {
		$query = $this->db->get_where($this->table, array($this->reserveId => $id));
		return $query->result();
	}

	private function _joinReservation() {
		$this->load->model('Reservation');
		$table = $this->Reservation->getTable() . ' as ' . $this->rAlias;
		$on = $this->rAlias . '.' . $this->Reservation->getId() . ' = ';
		$on .= $this->rpAlias . '.' . $this->reserveId;

		return array('table' => $table, 'on' => $on);
	}

	private $rAlias ="r";
	private $rpAlias ="rp";

	// GETTERS AND SETTERS
	public function getId() { return $this->id; }
	public function getAmount() { return $this->amount; }
	public function getDate() { return $this->date; }
	public function getReserveId() { return $this->reserveId; }
	public function getTable() { return $this->table; }
	public function getStatus() { return $this->status; }
	public function getDescription() { return $this->description; }

	public function setId($value) { $this->data[$this->id] = $value; }
	public function setAmount($value) { $this->data[$this->amount] = $value; }
	public function setDate($value) { $this->data[$this->date] = $value; }
	public function setReserveId($value) { $this->data[$this->reserveId] = $value; }
	public function setStatus($value) { $this->data[$this->status] = $value; }
	public function setDescription($value) { $this->data[$this->description] = $value; }

	public function deleteCache() {
		$this->db->cache_delete('rental','pay');
		$this->db->cache_delete('rental', 'returnPaypal');
		$this->db->cache_delete('lessor', 'payments');
		$this->db->cache_delete('rental', 'details');
	}
}