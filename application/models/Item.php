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
	private $name = "item_name";
	private $desc = "item_desc";
	private $cashBond = "item_cash_bond";
	private $rentalMode = "item_rental_mode";
	private $penalty = "item_penalty";
	private $shopId = "shop_id";
	private $subscriberId = "subscriber_id";

	private $limit = 5;
	private $offset = 0;

	private $startDate = "";
	private $endDate = "";

	// Alias
	private $itemAlias = 'i';
	private $shopAlias = 's';
	private $subscriberAlias = 'sub';
	private $categoryAlias = 'c';
	private $rdAlias = 'rd';
	private $rAlias = 'r';

	public function __construct() {
		parent::__construct();
		$this->startDate = date('Y-m-d H:i:s');
		// $this->endDate = date('Y-m-d H:i:s');
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

	public function all($select = "", $status = "", $like = "", $id = "") {
		$where = array();

		if (!empty($status)) {
			$where[] = $this->itemAlias .".". $this->status ." = '$status'";
		}

		if (!empty($like)) {
			$where[] = $this->itemAlias.".".$this->desc ." LIKE '%$like%' " . " OR ".
			 						$this->itemAlias . "." . $this->name . " LIKE '%$like%'" . " OR ".
			 						$this->shopAlias . "." . $this->RentalShop->getName() . " LIKE '%$like%'" . " OR ".
			 						$this->shopAlias . "." . $this->RentalShop->getBranch() . " LIKE '%$like%'";
		}

		if (!empty($id)) {
			$where[] = $this->itemAlias . "." . $this->id . " = $id";
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

	public function countTotal() {
		return $this->db->from($this->table)->count_all_results();
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

	public function findBySubscriberId($lessorId, $key = "", $order = "ASC") {
		$this->load->model('RentalShop');
		$itemAlias = 'i';
		$shopAlias = 's';
		$select = "$itemAlias.*, $shopAlias.{$this->RentalShop->getName()}, $shopAlias.{$this->RentalShop->getBranch()}";
		$joinCondition = "$shopAlias." .  $this->RentalShop->getId() .  
										" = $itemAlias." . $this->shopId;
		$where = "(" . $itemAlias.'.'.$this->subscriberId .' = '. $lessorId . ") ";
		if (!empty($key)) {
			$where .= "AND (" . $itemAlias . '.' . $this->desc . " like '%" . $key . "%' ";
			$where .= "OR " . $shopAlias . '.' . $this->RentalShop->getName() . " like '%" . $key . "%' ";
			$where .= "OR " . $shopAlias . '.' . $this->RentalShop->getBranch() . " like '%" . $key . "%' )";
		}
		$data['count'] = $this->db
			->from($this->table ." as i")
			->join($this->RentalShop->getTable() . " as s", $joinCondition, 'left')
			->where($where)
			->count_all_results();
		$data['data'] = $this->db
			->select($select)
			->from($this->table ." as i")
			->join($this->RentalShop->getTable() . " as s", $joinCondition, 'left')
			->where($where)
			->limit($this->limit, $this->offset)
			->order_by($this->id, $order)
			->get()->result();
		return $data;
	}

	public function findById($id, $select = "") {
		if (!empty($select)) {
			$this->db->select(implode(', ', $select));
		}
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function findByIdComplete($id, $byLessor = "") {
		$select = $this->_joinSelect();
		$joinShop = $this->_joinShop();
		$joinSubs = $this->_joinSubscriber();
		$where = array($this->itemAlias .".". $this->id => $id);
		
		if (!empty($byLessor)) {
			$where[$this->itemAlias .".". $this->subscriberId] = $byLessor;
		}

		$query = $this->db
			->select($select)
			->from($this->table . " as ". $this->itemAlias)
			->join($joinShop['table'], $joinShop['on'], $joinShop['type'])
			->join($joinSubs['table'], $joinSubs['on'], $joinSubs['type'])
			->where($where)
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

	public function findRentedReport($lessorId) {
		$joinResDetail = $this->_joinReservationDetail();
		$joinRes = $this->_joinReservation();
		$joinShop = $this->_joinShop();
		$select = array(
			$this->id,
			$this->desc
		);
		$select = "{$this->itemAlias}." . implode(', ' . $this->itemAlias . '.', $select) . ', ';
		$select .= "{$this->shopAlias}.{$this->RentalShop->getName()}, {$this->shopAlias}.{$this->RentalShop->getBranch()}, ";
		
		$rdA = $this->rdAlias;
		$when = $this->rAlias . '.' . $this->Reservation->getStatus() ." = 'close'";
		$rdQty = $rdA .'.'. $this->ReservationDetail->getQty();
		$rdAmtTotal = $rdA .'.'.$this->ReservationDetail->getQty() .' * '. $rdA .'.'. $this->ReservationDetail->getRentalAmt();
		$select .= "SUM(IF($when, $rdQty , 0)) as rentedQty, ";
		$select .= "SUM(IF($when, ($rdAmtTotal), 0)) as rentedAmt ";
		
		$query = $this->db
			->select($select)
			->from($this->table . ' as ' . $this->itemAlias)
			->join($joinShop['table'], $joinShop['on'], 'LEFT')
			->join($joinResDetail['table'], $joinResDetail['on'], 'LEFT')
			->join($joinRes['table'], $joinRes['on'], 'LEFT')
			->where(array($this->itemAlias . '.' . $this->subscriberId => $lessorId))
			->group_by($this->itemAlias.'.'.$this->id)
			->get();
		return $query->result();
	}

	private function _joinReservation() {
		$this->load->model('Reservation');
		$table = $this->Reservation->getTable() . ' as ' . $this->rAlias;
		$on = $this->rAlias . '.' . $this->Reservation->getId();
		$on .= ' = ' . $this->rdAlias . '.' . $this->ReservationDetail->getReserveId();
		return array('table' => $table, 'on' => $on);
	}

	private function _joinReservationDetail() {
		$this->load->model('ReservationDetail');
		$table = $this->ReservationDetail->getTable() . ' as ' . $this->rdAlias;
		$on = $this->rdAlias . '.' . $this->ReservationDetail->getItemId();
		$on .= ' = ' . $this->itemAlias . '.' . $this->id;
		return array('table' => $table, 'on' => $on);
	}

	public function getId() { return $this->id; }
	public function getRate() { return $this->rate; }
	public function getPic() { return $this->pic; }
	public function getStatus() { return $this->status; }
	public function getQty() { return $this->qty; }
	public function getDesc() { return $this->desc; }
	public function getName() { return $this->name; }
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

	public function setStartDate($date) {
		$this->startDate = date('Y-m-d H:i:s', strtotime($date));
	}

	public function setEndDate($date) {
		$this->endDate = date('Y-m-d H:i:s', strtotime($date));
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

	// MAPPING
	public function processItem($obj) {
    if (is_array($obj)) {
      $obj = (object)$obj;
    }
		$this->load->model('Reservation');
		$rentedQty = $this->Reservation->countRentedItem($obj->item_id, $this->startDate, $this->endDate);
    $img = $obj->item_pic == NULL ? 'http://placehold.it/250x150' : 'data:image/jpeg;base64,' . base64_encode($obj->item_pic);
    $this->load->library('RentalModes');
    
    return array(
      $this->id => $obj->item_id,
      $this->rate => $obj->item_rate,
      $this->pic => $img,
      $this->status => $obj->item_stats,
      $this->qty => $obj->item_qty,
      $this->desc => $obj->item_desc,
      $this->cashBond => $obj->item_cash_bond,
      $this->rentalMode => $obj->item_rental_mode,
      $this->penalty => $obj->item_penalty,
      $this->shopId => $obj->shop_id,
      "shop" => $obj->shop_name . ' - ' . $obj->shop_branch,
      $this->subscriberId => $obj->subscriber_id,
      $this->name => $obj->item_name,
      'mode_label' => $this->rentalmodes->getMode($obj->item_rental_mode),
      'rented_qty' => $rentedQty['rented'],
      'item_qty_left' => $obj->item_qty - $rentedQty['rented'],
      'startDate' => $this->startDate
    );
  }

  public function mapItemsWithCategory($data) {
    $this->load->model('ItemCategory');
    return array(
      'info' => $this->processItem($data), //$data,
      'categories' => $this->ItemCategory->findCategoryByItem($data->item_id)
    );
  }

  public function mapItemRented($item) {
    if (!empty($item)) {
      return array (
        'rental_amt' => $item->rental_amt,
        'item_rate' => $item->item_rate,
        'qty' => $item->qty,
        'item_id' => $item->item_id,
        'item_pic' => $item->item_pic == NULL ? 'http://placehold.it/320x150' : 'data:image/jpeg;base64,' . base64_encode($item->item_pic),
        'item_desc' => $item->item_desc,
        'shop_id' => $item->shop_id,
        'reserve_by' => $item->lessee_fname . ' ' . $item->lessee_lname,
        'duration' => date('M d, Y', strtotime($item->date_rented)) . ' - ' . date('M d, Y', strtotime($item->date_returned)),
        'reserve_id' => $item->reserve_id,
        'shop' => isset($item->shop_id) ? $item->shop_name . ' - ' . $item->shop_branch : '---'
      );
    } else {
      return NULL;
    }
  }

  public function mapItemRentedSimple($item) {
  	return empty($item->item_name) ? $item->item_desc : $item->item_name; 
  }

}