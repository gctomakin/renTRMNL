<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentalShop extends CI_Model{

	private $table = "rental_shops";

	// Columns

	private $id = "shop_id";
	private $name = "shop_name";
	private $branch = "shop_branch";
	private $address = "address";
	private $latitude = "latitude";
	private $longitude = "longitude";
	private $subscriberId = "subscriber_id";
	private $status = "status";

	private $limit = 10;
	private $offset = 0;

	public function __construct() {
		parent::__construct();
	}

	public function create($data) {
		$data[$this->status] = 'pending';
		$this->db->insert($this->table, $data);
		$this->deleteCache();
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		$this->deleteCache();
		return $this->db->affected_rows();
	}


	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		$this->deleteCache();
		return $this->db->affected_rows();
	}

	public function all($select = "*") {
		$this->db->select($select);
		$query = $this->db->from($this->table)->limit($this->limit, $this->offset)->get();
		return $query->result();
	}

	public function allCount() {
		return $this->db->from($this->table)->count_all_results();
	}

	public function findBySubscriberId($lessorId, $key = "") {
		$where = array($this->subscriberId => $lessorId);
		$data['count'] = $this->db->from($this->table)->where($where)->like($this->name, $key)->count_all_results();
		$data['data'] = $this->db->from($this->table)->where($where)->like($this->name, $key)->limit($this->limit, $this->offset)->get()->result();
		return $data;
	}

	public function findAllBySubscriberId($lessorId, $key = "") {
		$where = array($this->subscriberId => $lessorId);
		$query = $this->db->from($this->table)->where($where)->like($this->name, $key)->get();
		return $query->result();
	}

	public function findById($id, $subscriberId = "") {
		$whereArray = array($this->id => $id);
		if ($subscriberId) {
			$whereArray[$this->subscriberId] = $subscriberId;
		}
		$query = $this->db->get_where($this->table, $whereArray);
		return $query->row_array();
	}

	public function findByStatus($status) {
		$query = $this->db->get_where($this->table, array(
				$this->status => $status
			)
		);
		return $query->result();
	}

	public function getTable() { return $this->table; }

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getBranch() { return $this->branch; }
	public function getAddress() { return $this->address; }
	public function getLatitude() { return $this->latitude; }
	public function getLongitude() { return $this->longitude; }
	public function getSubscriberId() { return $this->subscriberId; }
	public function getStatus() { return $this->status; }

	public function getLimit() { return $this->limit; }
	public function getOffset() { return $this->offset; }

	public function setOffset($offset) {
		$this->offset = $offset;
	}

	private function deleteCache() {
		$this->db->cache_delete('rentalshops','allByLessor');
		$this->db->cache_delete('lessor','shops');
		$this->db->cache_delete('lessee','shops');
		$this->db->cache_delete('admin','rentalshops');
	}
}