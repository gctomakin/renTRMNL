<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ReservationDetail extends CI_Model{

	private $table = 'reservation_details';
	private $id = 'reserve_detail_id';
	private $rentalAmt = 'rental_amt';
	private $qty = 'qty';
	private $itemId = 'item_id';
	private $reserveId = 'reserve_id';

	private $rdAlias = 'rd';
	private $iAlias = 'i';
	private $subAlias = 'sub';

	private $data = array(
		'reserve_detail_id' => '',
		'rental_amt' => '',
		'qty' => '',
		'item_id' => '',
		'reserve_id' => ''
	);


	public function clean() {
		foreach ($this->data as $key => $value) {
			$this->data[$this->key] = '';
		}
	}

	public function create() {
		$data = array_filter($this->data);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function delete() {
		if (empty($this->data[$this->id])) { 
			return 0;
		} else {
			$this->db->delete($this->table, array(
					$this->id => $this->data[$this->id]
				)
			);
			return $this->db->affected_rows();	
		}
	}

	public function findByReservationId($id) {
		$join = $this->_joinItem();
		$query = $this->db
			->select($this->joinSelect())
			->from($this->table . ' as ' . $this->rdAlias)
			->join($join['table'], $join['on'])
			->where(array($this->rdAlias . '.' . $this->reserveId => $id))
			->get();
		return $query->result();	
	}

	public function findSubscriberByReservationId($id) {
		$joinItem = $this->_joinItem();
		$joinSubs = $this->_joinSubscriber();

		$query = $this->db
			->select($this->subAlias.'.*')
			->from($this->table . ' as ' . $this->rdAlias)
			->join($joinItem['table'], $joinItem['on'], 'INNER')
			->join($joinSubs['table'], $joinSubs['on'], 'INNER')
			->where(array($this->rdAlias . '.' . $this->reserveId => $id))
			->get();
		return $query->row_array();
	}

	private function _joinItem() {
		$this->load->model('Item');
		$table = $this->Item->getTable() . ' as ' . $this->iAlias;
		$on = $this->iAlias . '.' . $this->Item->getId();
		$on .= ' = ' . $this->rdAlias . '.' . $this->itemId;
		return array('table' => $table, 'on' => $on);
	}

	private function _joinSubscriber() {
		$this->load->model('Subscriber');
		$table = $this->Subscriber->getTable() . ' as ' . $this->subAlias;
		$on = $this->subAlias . '.' . $this->Subscriber->getId();
		$on .= ' = ' . $this->iAlias . '.' . $this->Item->getSubscriberId();
		return array('table' => $table, 'on' => $on);
	}

	private function joinSelect() {
		return $this->iAlias . '.*, ' . $this->rdAlias . '.*';
	}

	// GETTERS AND SETTERS
	public function getId() { return $this->id; }
	public function getRentalAmt() { return $this->rentalAmt; }
	public function getQty() { return $this->qty; }
	public function getItemId() { return $this->itemId; }
	public function getReserveId() { return $this->reserveId; }
	public function getTable() { return $this->table; }

	public function setId($value) { $this->data[$this->id] = $value; }
	public function setRentalAmt($value) { $this->data[$this->rentalAmt] = $value; }
	public function setQty($value) { $this->data[$this->qty] = $value; }
	public function setItemId($value) { $this->data[$this->itemId] = $value; }
	public function setReserveId($value) { $this->data[$this->reserveId] = $value; }
}