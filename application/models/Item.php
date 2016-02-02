<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Model{
	private $table = "items";
	private $tableShop;

	/** Item columns **/
	private $id = "item_id";
	private $rate = "item_rate";
	private $pic = "item_pic";
	private $status = "item_stats";
	private $qty = "item_qty";
	private $desc = "item_desc";
	private $cashBond = "item_cash_bond";
	private $rentalMode = "item_rental_mode";
	private $penalty = "item_penalty";
	private $shopId = "shop_id";
	private $subscriberId = "subscriber_id";

	private $limit = 5;
	private $offset = 0;

	// Alias
	private $itemAlias = 'i';
	private $shopAlias = 's';
	private $subscriberAlias = 'sub';
	private $categoryAlias = 'c';
	private $rdAlias = 'rd';

	public function __construct() {
		parent::__construct();
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

	public function all($select = "", $status = "", $like = "") {
		$where = array();

		if (!empty($status)) {
			$where[] = $this->itemAlias .".". $this->status ." = '$status'";
		}

		if (!empty($like)) {
			$where[] = $this->itemAlias.".".$this->desc ." LIKE '%$like%' " . " OR ".
			 						$this->shopAlias . "." . $this->RentalShop->getName() . " LIKE '%$like%'" . " OR ".
			 						$this->shopAlias . "." . $this->RentalShop->getBranch() . " LIKE '%$like%'";
		}

		if (!empty($where)) {
			$this->db->where(implode('AND ', $where));
		}

		if (empty($select)) {
			$select = $this->_joinSelect();
		}

		$joinShop = $this->_joinShop();
		$joinSubs = $this->_joinSubscriber();
		$data['count'] = $this->db
			->from($this->table ." as i")
			->join($joinShop['table'], $joinShop['on'], $joinShop['type'])
			->count_all_results();
		if (!empty($where)) {
			$this->db->where(implode('AND ', $where));
		}
		$data['data'] = $query = $this->db
			->select($select)
			->from($this->table ." as ". $this->itemAlias)
			->join($joinShop['table'], $joinShop['on'], $joinShop['type'])
			->join($joinSubs['table'], $joinSubs['on'], $joinSubs['type'])
			->limit($this->limit, $this->offset)
			->get()
			->result();
		return $data;
	}

	public function allForMonitor() {
		$select = $this->_selectItem();
		$countRented = $this->_subCountRented();
		$query = $this->db
			->select($select . ", $countRented")
			->from($this->table . ' as ' . $this->itemAlias)
			->get();
		return $query->result();
	}

	private function _selectItem() {
		$select = array(
			$this->id, $this->rate,
			$this->pic, $this->status,
			$this->qty, $this->desc
		);
		return $this->itemAlias . '.' . implode(', ' . $this->itemAlias . '.', $select);
	}

	private function _subCountRented() {
		$this->load->model('ReservationDetail');
		$table = $this->ReservationDetail->getTable() . ' as ' . $this->rdAlias;
		$where = $this->itemAlias . '.' . $this->id . ' = ';
		$where .= $this->rdAlias . '.' . $this->ReservationDetail->getItemId();
		$select = "(SELECT count(1) FROM $table WHERE $where) as total_rented";
		return $select;
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
			->or_like($shopAlias.'.'.$this->RentalShop->getName(), $key)
			->or_like($shopAlias.'.'.$this->RentalShop->getBranch(), $key)
			->limit($this->limit, $this->offset)
			->get()->result();
		return $data;
	}

	public function findById($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function findByIdComplete($id) {
		$select = $this->_joinSelect();
		$joinShop = $this->_joinShop();
		$joinSubs = $this->_joinSubscriber();
		$query = $this->db
			->select($select)
			->from($this->table . " as ". $this->itemAlias)
			->join($joinShop['table'], $joinShop['on'], $joinShop['type'])
			->join($joinSubs['table'], $joinSubs['on'], $joinSubs['type'])
			->where(array($this->itemAlias .".". $this->id => $id))
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

	public function findByShop($id) {
		$query = $this->db
			->from($this->table)
			->where(array($this->shopId => $id))
			->get();
		return $query->result();
	}

	public function findByCategory($categoryId) {
		$joinShop = $this->_joinShop();
		$joinSubs = $this->_joinSubscriber();
		$joinCat 	= $this->_joinItemCategory();

		$where = array(
			//$this->itemAlias . '.'. $this->status => 'active',
			$this->categoryAlias . '.' . $this->ItemCategory->getCategoryId() => $categoryId
		);

		$data['count'] = $this->db
			->from($this->table ." as i")
			->join($joinCat['table'], $joinCat['on'], 'INNER')
			->where($where)
			->count_all_results();
		$data['data'] = $query = $this->db
			->select($this->_joinSelect())
			->from($this->table ." as ". $this->itemAlias)
			->join($joinCat['table'], $joinCat['on'], 'INNER')
			->join($joinShop['table'], $joinShop['on'], $joinShop['type'])
			->join($joinSubs['table'], $joinSubs['on'], $joinSubs['type'])
			->where($where)
			->limit($this->limit, $this->offset)
			->get()
			->result();
		return $data;
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
	public function getTable() { return $this->table; }

	public function getLimit() { return $this->limit; }
	public function getOffset() { return $this->offset; }


	public function setOffset($offset) {
		$this->offset = $offset;
	}
	public function setLimit($limit) {
		$this->limit = $limit;
	}

	public function clearCache() {
		$this->db->cache_delete('lessor','items');
		$this->db->cache_delete('lessee','items');
	}

	private function _joinSelect() {
		return $this->itemAlias .".*, ". $this->shopAlias .".*, " . $this->subscriberAlias . ".*";
	}
	private function _joinShop() {
		$this->load->model('RentalShop');
		$table = $this->RentalShop->getTable() . " as " . $this->shopAlias;
		$on = $this->shopAlias. "." .  $this->RentalShop->getId();
		$on .= " = " . $this->itemAlias . "." . $this->shopId;
		return array('table' => $table, 'on' => $on, 'type' => 'left');
	}

	private function _joinSubscriber() {
		$this->load->model('Subscriber');
		$table = $this->Subscriber->getTable() . " as " . $this->subscriberAlias;
		$on = $this->subscriberAlias. "." . $this->Subscriber->getId();
		$on .= " = " . $this->itemAlias. "." . $this->subscriberId;
		return array('table' => $table, 'on' => $on, 'type' => 'left');
	}

	private function _joinItemCategory() {
		$this->load->model('ItemCategory');
		$table = $this->ItemCategory->getTable() . " as " . $this->categoryAlias;
		$on = $this->itemAlias . "." . $this->id;
		$on .= " = " . $this->categoryAlias . "." . $this->ItemCategory->getItemId();
		return array('table' => $table, 'on' => $on);
	}
}