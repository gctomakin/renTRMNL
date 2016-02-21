<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation extends CI_Model{

	private $table = "rental_reservations";
	private $id = "reserve_id";
	private $date = "reserve_date";
	private $dateRented = "date_rented";
	private $dateReturned = "date_returned";
	private $totalAmt = "total_amt";
	private $downPayment = "down_payment";
	private $totalBalance = "total_balance";
	private $penalty = "penalty";
	private $status = "status";
	private $lesseeId = "lessee_id";
	private $subscriberId = "subscriber_id";

	private $data = array(
		"reserve_id" => "",
		"reserve_date" => "",
		"date_rented" => "",
		"date_returned" => "",
		"total_amt" => "",
		"down_payment" => "",
		"total_balance" => "",
		"penalty" => "",
		"status" => "",
		"lessee_id"=> "",
		"subscriber_id" => ""
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

	public function findComplete($where = "") {
		// $data = array_filter($this->data);
		$joinDetail = $this->_joinReservationDetail();
		$joinItem = $this->_joinItem();
		$joinShop = $this->_joinShop();
		$joinLessee = $this->_joinLessee();
		$select = $this->rAlias.'.*, ' . $this->rsAlias.'.*, ' . $this->lAlias . '.*';
		if (!empty($where)) {
			$this->db->where($where);
		}
		$query = $this->db
			->select($select)
			->from($this->table . ' as ' . $this->rAlias)
			->join($joinLessee['table'], $joinLessee['on'])
			->join($joinDetail['table'], $joinDetail['on'])
			->join($joinItem['table'], $joinItem['on'])
			->join($joinShop['table'], $joinShop['on'], 'LEFT')
			->group_by($this->rAlias .'.'. $this->id)
			->get();
		return $query->result();
	}

	public function findById($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function findBySubscriberId($id, $status = "") {
		$where = array($this->subscriberId => $id);
		if (!empty($status)) {
			$where[$this->status] = $status;
		}
		$query = $this->db->get_where($this->table, $where);
		return $query->result();
	}

	public function findActiveItemBySubscriberId($id) {
		$join = $this->_joinReservationDetail();
		$joinItem = $this->_joinItem();
		$joinLessee = $this->_joinLessee();
		$joinShop = $this->_joinShop();

		$where = $this->rAlias . '.'. $this->subscriberId . ' = ' . $id;
		$where .= " AND '" . date('Y-m-d H:i:s') . "'";
		$where .= " BETWEEN " . $this->rAlias . '.' . $this->getDateRented();
		$where .= " AND " . $this->rAlias . '.' . $this->getDateReturned();
		
		$data['count'] = $this->db
			->from($this->table . ' as ' . $this->rAlias)
			->join($join['table'], $join['on'])
			->join($joinLessee['table'], $joinLessee['on'], 'LEFT')
			->join($joinItem['table'], $joinItem['on'], 'LEFT')
			->where($where)
			->count_all_results();
		
		$data['result'] = $this->db
			->from($this->table . ' as ' . $this->rAlias)
			->join($join['table'], $join['on'])
			->join($joinLessee['table'], $joinLessee['on'], 'LEFT')
			->join($joinItem['table'], $joinItem['on'], 'LEFT')
			->join($joinShop['table'], $joinShop['on'], 'LEFT')
			->where($where)
			->limit($this->limit, $this->offset)
			->get()
			->result();
		return $data;
	}

	public function findActiveItemByLesseeId($id) {
		$join = $this->_joinReservationDetail();
		$joinItem = $this->_joinItem();
		$joinLessee = $this->_joinLessee();
		$joinShop = $this->_joinShop();

		$where = $this->rAlias . '.'. $this->lesseeId . ' = ' . $id;
		$where .= " AND '" . date('Y-m-d H:i:s') . "'";
		$where .= " BETWEEN " . $this->rAlias . '.' . $this->getDateRented();
		$where .= " AND " . $this->rAlias . '.' . $this->getDateReturned();
		
		$data['count'] = $this->db
			->from($this->table . ' as ' . $this->rAlias)
			->join($join['table'], $join['on'])
			->join($joinLessee['table'], $joinLessee['on'], 'LEFT')
			->join($joinItem['table'], $joinItem['on'], 'LEFT')
			->where($where)
			->count_all_results();
		
		$data['result'] = $this->db
			->from($this->table . ' as ' . $this->rAlias)
			->join($join['table'], $join['on'])
			->join($joinLessee['table'], $joinLessee['on'], 'LEFT')
			->join($joinItem['table'], $joinItem['on'], 'LEFT')
			->join($joinShop['table'], $joinShop['on'], 'LEFT')
			->where($where)
			->limit($this->limit, $this->offset)
			->get()
			->result();
		return $data;
	}

	public function findSubscriberById($id, $select = "") {
		$joinSub = $this->_joinSubscriber();
		if (empty($select)) {
			$this->db->select($this->subAlias .".*");
		} else if (is_array($select)) {
			$this->db->select($this->subAlias . "." . implode(" {$this->subAlias}.", $select));
		}
		$query = $this->db
			->from($this->table . ' as ' . $this->rAlias)
			->join($joinSub['table'], $joinSub['on'], 'INNER')
			->where($this->rAlias . '.'. $this->id, $id)
			->get();
		return $query->row_array();
	}

	private $limit = 5;
	private $offset = 0;

	private $rAlias = "r";
	private $subAlias = "sub";
	private $rdAlias = 'rd';
	private $iAlias = 'i';
	private $lAlias = 'l';
	private $rsAlias = 'rs';

	private function _joinSubscriber($id = "") {
		$this->load->model('Subscriber');
		$table = $this->Subscriber->getTable() . ' as ' . $this->subAlias;
		$on = $this->rAlias . '.' . $this->subscriberId . ' = ';
		$on .= empty($id) ? $this->subAlias . '.' . $this->Subscriber->getId() : $id;
		return array('table' => $table, 'on' => $on);
	}

	private function _joinReservationDetail() {
		$this->load->model('ReservationDetail');
		$table = $this->ReservationDetail->getTable() . ' as '. $this->rdAlias;
		$on = $this->rdAlias . '.' . $this->ReservationDetail->getReserveId() . ' = ';
		$on .= $this->rAlias . '.' . $this->id;
		return array('table' => $table, 'on' => $on);
	}

	private function _joinItem() {
		$this->load->model('Item');
		$table = $this->Item->getTable() . ' as ' . $this->iAlias;
		$on = $this->iAlias . '.' . $this->Item->getId() . ' = ';
		$on .= $this->rdAlias . '.' . $this->ReservationDetail->getItemId();
		return array('table' => $table, 'on' => $on);
	}

	private function _joinLessee() {
		$this->load->model('Lessee');
		$table = $this->Lessee->getTable() . ' as ' . $this->lAlias;
		$on = $this->lAlias . '.lessee_id = ';
		$on .= $this->rAlias . '.' . $this->lesseeId;
		return array('table' => $table, 'on' => $on); 
	}

	private function _joinShop() {
		$this->load->model('RentalShop');
		$table = $this->RentalShop->getTable() . ' as ' . $this->rsAlias;
		$on = $this->rsAlias . '.' . $this->RentalShop->getId() . ' = ';
		$on .= $this->iAlias . '.' . $this->Item->getShopId();
		return array('table' => $table, 'on' => $on);
	}

	// GETTERS AND SETTERS

	public function getId() { return $this->id; }
	public function getDate() { return $this->date; }
	public function getDateRented() { return $this->dateRented; }
	public function getDateReturned() { return $this->dateReturned; }
	public function getTotalAmt() { return $this->totalAmt; }
	public function getDownPayment() { return $this->downPayment; }
	public function getTotalBalance() { return $this->totalBalance; }
	public function getPenalty() { return $this->penalty; }
	public function getStatus() { return $this->status; }
	public function getLesseeId() { return $this->lesseeId; }
	public function getSubscriberId() { return $this->subscriberId; }
	public function getTable() { return $this->table; }

	public function setId($value) { $this->data[$this->id] = $value; }
	public function setDate($value) { $this->data[$this->date] = $value; }
	public function setDateRented($value) { $this->data[$this->dateRented] = $value; }
	public function setDateReturned($value) { $this->data[$this->dateReturned] = $value; }
	public function setTotalAmt($value) { $this->data[$this->totalAmt] = $value; }
	public function setDownPayment($value) { $this->data[$this->downPayment] = $value; }
	public function setTotalBalance($value) { $this->data[$this->totalBalance] = $value; }
	public function setPenalty($value) { $this->data[$this->penalty] = $value; }
	public function setStatus($value) { $this->data[$this->status] = $value; }
	public function setLesseeId($value) { $this->data[$this->lesseeId] = $value; }
	public function setSubscriberId($value) { $this->data[$this->subscriberId] = $value; }

	public function getLimit() { return $this->limit; }
	public function getOffset() { return $this->offset; }


	public function setOffset($offset) {
		$this->offset = $offset;
	}
	public function setLimit($limit) {
		$this->limit = $limit;
	}

	private function deleteCache() {
		$this->db->cache_delete('lessee','reserved');
		$this->db->cache_delete('lessor','reservations');
	}

	// MAP
	public function mapDetailItem($res) {
		$this->load->model('ReservationDetail');
		$detail = $this->ReservationDetail->findByReservationId($res->reserve_id);
		return array(
			'info' => $res,
			'detail' => array_map(array($this->Item, 'mapItemRentedSimple'), $detail)
		);
	}
}