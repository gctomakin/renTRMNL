<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Penalty extends CI_Model{

	private $table = "rental_penalties";

	private $id = 'penalty_id';
	private $amt = 'penalty_amt';
	private $type = 'penalty_type';
	private $reserveId = 'reserve_id';

	private $data = array(
		'penalty_id' => '',
		'penalty_amt' => '',
		'penalty_type' => '',
		'reserve_id' => ''
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
		$data = array_filter($this->data, function($value) {
    	return ($value !== null && $value !== false && $value !== ''); 
		});
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

	public function findById($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	// GETTERS AND SETTERS
	public function getTable() { return $this->table; }
	public function getId() { return $this->id; }
	public function getAmt() { return $this->amt; }
	public function getType() { return $this->type; }
	public function getReserveId() { return $this->reserveId; }

	public function setId($value) { $this->data[$this->id] = $value; }
	public function setAmt($value) { $this->data[$this->amt] = $value; }
	public function setType($value) { $this->data[$this->type] = $value; }
	public function setReserveId($value) { $this->data[$this->reserveId] = $value; }

	private function deleteCache() {
		$this->db->cache_delete('admin', 'monitor');
	}
}