<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Model{
	private $table;
	private $tableShop;

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

	private $limit = 5;
	private $offset = 0;

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
		$this->clearCache();
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		$this->clearCache();
    return $this->db->affected_rows();
	}

	public function updateStatus($id, $status) {
		$this->db->where($this->id, $id);
		$this->db->update($this->table, array($this->status => $status));
		$this->clearCache();
		return $this->db->affected_rows();
	}

	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		$this->clearCache();
		return $this->db->affected_rows();
	}

	public function all($select = "", $status = "") {
		// Load reference Table
		$this->load->model('RentalShop');
		$this->load->model('Subscriber');

		// Aliases
		$itemAlias = 'i';
		$shopAlias = 's';
		$subscriberAlias = 'sub';

		// Conditions
		$itemShopCon = "$shopAlias." .  $this->RentalShop->getId() .  " = $itemAlias." . $this->shopId;
		$itemSubscriberCon = "$subscriberAlias." . $this->Subscriber->getId() . " = $itemAlias.". $this->subscriberId;

		if (!empty($status)) {
			$this->db->where("$itemAlias.{$this->status} = $status");
		}

		if (empty($select)) {
			$select = "$itemAlias.*, $shopAlias.*, $subscriberAlias.*";
		}

		$query = $this->db
			->select($select)
			->from($this->table ." as $itemAlias")
			->join($this->RentalShop->getTable() . " as $shopAlias", $itemShopCon, "left")
			->join($this->Subscriber->getTable() . " as $subscriberAlias", $itemSubscriberCon, "left")
			->get();
		return $query->result();
	}

	public function findBySubscriberId($lessorId, $key = "") {
		$this->load->model('RentalShop');
		$itemAlias = 'i';
		$shopAlias = 's';
		$select = "$itemAlias.*, $shopAlias.{$this->RentalShop->getName()}, $shopAlias.{$this->RentalShop->getBranch()}";
		$joinCondition = "$shopAlias." .  $this->RentalShop->getId() .  
										" = $itemAlias." . $this->shopId;
		$where = array($itemAlias.'.'.$this->subscriberId => $lessorId);
		$data['count'] = $this->db->from($this->table ." as i")->where($where)->like($itemAlias.'.'.$this->desc, $key)->count_all_results();
		$data['data'] = $this->db
			->select($select)
			->from($this->table ." as i")
			->join($this->RentalShop->getTable() . " as s", $joinCondition, 'left')
			->where($where)
			->like($itemAlias.'.'.$this->desc, $key)
			->limit($this->limit, $this->offset)
			->get()->result();
		return $data;
	}

	public function findById($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function findByIdComplete($id) {
		$this->load->model('RentalShop');
		$itemAlias = 'i';
		$shopAlias = 's';
		$select = "$itemAlias.*, $shopAlias." .
							$this->RentalShop->getName() . ", $shopAlias." .
							$this->RentalShop->getBranch();
		$join = "$shopAlias.". $this->RentalShop->getId() . " = $itemAlias." . $this->shopId;
		$query = $this->db
			->select($select)
			->from($this->table . " as $itemAlias")
			->join($this->RentalShop->getTable() . " as $shopAlias", $join, 'LEFT')
			->where(array("$itemAlias.".$this->id => $id))
			->get();

		return $query->row_array();
	}

	public function isExist($id, $from = "") {
		$where = array($this->id => $id);
		if (!empty($from)) {
			$where[$this->subscriberId] = $from;
		}
		$query = $this->db->get_where($this->table, $where);
		return $query->num_rows() > 0;
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

	public function getLimit() { return $this->limit; }
	public function getOffset() { return $this->offset; }

	public function setOffset($offset) {
		$this->offset = $offset;
	}

	public function clearCache() {
		$this->db->cache_delete('lessor','items');
		$this->db->cache_delete('lessee','items');
	}
}