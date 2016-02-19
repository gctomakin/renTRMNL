<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RentalShop extends CI_Model{

	private $table = "rental_shops";

	// Columns

	private $id = "shop_id";
	private $name = "shop_name";
	private $branch = "shop_branch";
	private $image = "shop_image";
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

	public function findBySubscriberId($lessorId, $key = "", $order = "ASC") {
		$where = array($this->subscriberId => $lessorId);
		$data['count'] = $this->db->from($this->table)->where($where)->like($this->name, $key)->count_all_results();
		$data['data'] = $this->db
			->from($this->table)
			->where($where)
			->like($this->name, $key)
			->limit($this->limit, $this->offset)
			->order_by($this->id, $order)
			->get()->result();
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
	public function getImage() { return $this->image; }
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

	public function setLimit($limit) {
		$this->limit = $limit;
	}

	private function deleteCache() {
		$this->db->cache_delete('rentalshops','allByLessor');
		$this->db->cache_delete('lessor','shops');
		$this->db->cache_delete('lessee','shops');
		$this->db->cache_delete('admin','rentalshops');
	}

	// MAPPING
	public function processShop($obj) {
    if (is_array($obj)) {
      $obj = (Object)$obj;
    }
    $img = $obj->shop_image == NULL ? 
      'http://placehold.it/250x150' :
      'data:image/jpeg;base64,' . base64_encode($obj->shop_image);
    return array(
      $this->name => $obj->shop_name,
      $this->branch => $obj->shop_branch,
      $this->image => $img,
      $this->address => $obj->address,
      $this->subscriberId => $obj->subscriber_id
    );
  }

  public function processGroupShop($shops, $category) {
    $this->load->model('Item');
    $this->load->model('ItemCategory');
    $data = array();
    foreach ($shops as $shop) {
      $item = $this->ItemCategory->findItemByIdAndShop($category, $shop->shop_id);
      $result = $this->findById($shop->shop_id);
      $data[$shop->shop_id]['items'] = array_map(array($this->Item, 'processItem'), $item);
      $data[$shop->shop_id]['detail'] = $this->processShop($result);
    }
    return $data;
  }
}