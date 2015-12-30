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

	private $limit = 10;
	private $offset = 0;

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
		$this->db->cache_delete('lessor','shops');
		$this->db->cache_delete('lessee','shops');
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		$this->db->cache_delete('lessor','shops');
    return $this->db->affected_rows();
	}


	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		$this->db->cache_delete('lessor','shops');
		return $this->db->affected_rows();
	}

	public function all($select = "*") {
		$this->db->select($select);
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function findBySubscriberId($lessorId) {
		$where = array($this->subscriberId => $lessorId);
		$data['count'] = $this->db->from($this->table)->where($where)->count_all_results();
		$data['data'] = $this->db->from($this->table)->where($where)->limit($this->limit, $this->offset)->get()->result();
		return $data;
	}

	public function findById($id, $subscriberId = "") {
		$whereArray = array($this->id => $id);
		if ($subscriberId) {
			$whereArray[$this->subscriberId] = $subscriberId;
		}
		$query = $this->db->get_where($this->table, $whereArray);
		return $query->row_array();
	}

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getBranch() { return $this->branch; }
	public function getLatitude() { return $this->latitude; }
	public function getLongitude() { return $this->longitude; }
	public function getSubscriberId() { return $this->subscriberId; }


	public function getLimit() { return $this->limit; }
	public function getOffset() { return $this->offset; }

	public function setOffset($offset) {
		$this->offset = $offset;
	}
}