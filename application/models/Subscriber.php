<?php

class Subscriber extends CI_Model{

	private $table;

	/** @var String Columns */
	private $id;
	private $fname;
	private $mi;
	private $lname;
	private $email;
	private $addr;
	private $telno;
	private $type;
	private $paypal;
	private $status;
	private $username;
	private $password;
	private $date;

	public function __construct() {
		parent::__construct();
		$this->id = "subscriber_id";
		$this->fname = "subscriber_fname";
		$this->mi = "subscriber_midint";
		$this->lname = "subscriber_lname";
		$this->email = "subscriber_email";
		$this->addr = "subscriber_addr";
		$this->telno = "subscriber_telno";
		$this->type = "subscriber_type";
		$this->paypal = "subscriber_paypal_account";
		$this->status = "subscriber_status";
		$this->username = "username";
		$this->password = "password";
		$this->date = "date_registered";
		$this->table = "subscribers";

	}

	public function create($data) {
		$this->db->insert($this->table, $data);
		$this->_deleteCache();
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		$this->_deleteCache();
    return $this->db->affected_rows();
	}

	public function updateStatus($id, $status) {
		$this->db->where($this->id, $id);
		$this->db->update($this->table, array($this->status => $status));
		$this->_deleteCache();
		return $this->db->affected_rows();
	}

	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		$this->_deleteCache();
		return $this->db->affected_rows();
	}

	public function all($select = "*", $status = "") {
		$this->db->select($select);
		if (empty($status)) {
			$this->db->where("{$this->status} = $status");
		}
		$query = $this->db->get($this->table);
		return $query->result();
	}

	public function findId($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->row_array();
	}

	public function findUsername($username) {
		$query = $this->db->get_where($this->table, array($this->username => $username));
		return $query->row_array();
	}

	public function countTotalByDate($from, $to = "") {
		if ($from == $to) {
		 	$where = "DATE({$this->date}) = DATE('$from')";
		} else {
			$where = "{$this->date} between '$from' and '$to'";
		}
		$query = $this->db
			->select('count('. $this->id .') as total')
			->from($this->table)
			->where($where)
			->get();
		$result = $query->row_array(); 
		return $result['total'];
	}

	public function findByDate($from, $to = "", $status = "") {
		$where = array("DATE({$this->date}) >=" => $from);
		if (!empty($to)) {
			$where["DATE({$this->date}) <="] = $to;
		}
		if (!empty($status)) {
			$where[$this->status] = $status;
		}
		$query = $this->db->get_where($this->table, $where);
		return $query->result();
	}

	public function findReservation($id, $status) {
		$this->load->model('ReservationDetail');
		$this->load->model('Reservation');
		$this->load->model('Item');
		$joinItem = $this->_joinItem();
		$joinResDetail = $this->_joinReservationDetail();
		$joinRes = $this->_joinReservation();
		$where = array(
			's.'.$this->id => $id,
			'r.'.$this->Reservation->getStatus() => $status
		);
		$query = $this->db
			->select('r.*')
			->from($this->table . ' as s')
			->join($joinItem['table'], $joinItem['on'])
			->join($joinResDetail['table'], $joinResDetail['on'])
			->join($joinRes['table'], $joinRes['on'])
			->where($where)
			->get();
		return $query->result();
	}

	public function isEmailExist($email, $id = "") {
		if (!empty($id)) {
			$where[$this->id . ' <>'] = $id;
		}
		$where[$this->email] = $email;
		$query = $this->db->get_where($this->table, $where);
		return $query->num_rows() > 0;
	}

	public function isUsernameExist($username, $id = "") {
		if (!empty($id)) {
			$where[$this->id . ' <>'] = $id;
		}
		$where[$this->username] = $username;
		$query = $this->db->get_where($this->table, $where);
		return $query->num_rows() > 0;
	}

	public function isPaypalExist($paypal, $id = "") {
		if (!empty($id)) {
			$where[$this->id . ' <>'] = $id;
		}
		$where[$this->paypal] = $paypal;
		$query = $this->db->get_where($this->table, $where);
		return $query->num_rows() > 0;
	}

	private function _joinItem() {
		$table = $this->Item->getTable() . ' as i';
		$on = 'i.' . $this->Item->getSubscriberId() . ' = ';
		$on .= 's.' . $this->id;
		return array('table' => $table, 'on' => $on, 'type' => 'INNER');
	}

	private function _joinReservationDetail() {
		$table = $this->ReservationDetail->getTable() . ' as rd';
		$on = 'rd.' . $this->ReservationDetail->getItemId() . ' = ';
		$on .= 'i.' . $this->Item->getId();
		return array('table' => $table, 'on' => $on, 'type' => 'INNER');
	}

	private function _joinReservation() {
		$table = $this->Reservation->getTable() . ' as r';
		$on = 'r.' . $this->Reservation->getId() . ' = ';
		$on .= 'rd.' . $this->ReservationDetail->getReserveId();
		return array('table' => $table, 'on' => $on);
	}

	/** GETTERS */
	public function getTable() { return $this->table; }
	public function getId() { return $this->id; }
	public function getFname() { return $this->fname; }
	public function getMi() { return $this->mi; }
	public function getLname() { return $this->lname; }
	public function getEmail() { return $this->email; }
	public function getAddr() { return $this->addr; }
	public function getTelno() { return $this->telno; }
	public function getType() { return $this->type; }
	public function getPaypal() { return $this->paypal; }
	public function getStatus() { return $this->status; }	
	public function getUsername() { return $this->username; }	
	public function getPassword() { return $this->password; }	
	public function getDate() { return $this->date; }

	private function _deleteCache() {
		$this->db->cache_delete('lessors','accountSave');
		$this->db->cache_delete('lessor','account');
		$this->db->cache_delete('lessors','signin');
	}
}