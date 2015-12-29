<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Model{
	private $table;

	/** Item columns **/
	private $id;
	private $rate;
	private $pic;
	private $status;
	private $qty;
	private $desc;
	private $cashBond;
	private $rentalMode;
	private $penalty;
	private $shopId;
	private $subscriberId;

	public function __construct() {
		parent::__construct();

		$this->table = "items";
		$this->id = "item_id";
		$this->rate = "item_rate";
		$this->pic = "item_pic";
		$this->status = "item_stats";
		$this->qty = "item_qty";
		$this->desc = "item_desc";
		$this->cashBond = "item_cash_bond";
		$this->rentalMode = "item_rental_mode";
		$this->penalty = "item_penalty";
		$this->shopId = "shop_id";
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

	public function updateStatus($id, $status) {
		$this->db->where($this->id, $id);
		$this->db->update($this->table, array($this->status => $status));
		return $this->db->affected_rows();
	}

	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		return $this->db->affected_rows();
	}

	public function all($select = "*", $status = "") {
		$this->db->select($select);
		if (!empty($status)) {
			$this->db->where("{$this->status} = $status");
		}
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function findId($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function getId() { return $this->id; }
	public function getRate() { return $this->rate; }
	public function getPic() { return $this->pic; }
	public function getStatus() { return $this->status; }
	public function getQty() { return $this->qty; }
	public function getDesc() { return $this->desc; }
	public function getCashBond() { return $this->cashBond; }
	public function getRentalMode() { return $this->rentalMode; }
	public function getPenalty() { return $this->penalty; }
	public function getShopId() { return $this->shopId; }
	public function getSubscriberId() { return $this->subscriberId; }
}