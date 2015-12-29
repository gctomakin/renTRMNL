<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentalShop extends CI_Model{

	private $table;

	// Columns

	private $id;
	private $name;
	private $branch;
	private $latitude;
	private $longitude;
	private $subscriberId;

	public function __construct() {
		$this->table = "rental_shops";
		$this->id = "shop_id";
		$this->name = "shop_name";
		$this->branch = "shop_branch";
		$this->latitude = "latitude";
		$this->longitude = "longitude";
		$this->subscriberId = "subscriber_id";
	}

	public function create($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
    return $this->db->affected_rows();
	}


	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		return $this->db->affected_rows();
	}

	public function all($select = "*", $status = "") {
		//$this->db->select($select);
		// if (empty($status)) {
		// 	$this->db->where("{$this->status} = $status");
		// }
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function findId($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getBranch() { return $this->branch; }
	public function getLatitude() { return $this->latitude; }
	public function getLongitude() { return $this->longitude; }
	public function getSubscriberId() { return $this->subscriberId; }
}