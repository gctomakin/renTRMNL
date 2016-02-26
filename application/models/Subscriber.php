<?php

class Subscriber extends CI_Model{

	private $table = "subscribers";

	/** @var String Columns */
	private $id = "subscriber_id";
	private $fname = "subscriber_fname";
	private $mi = "subscriber_midint";
	private $lname = "subscriber_lname";
	private $email = "subscriber_email";
	private $addr = "subscriber_addr";
	private $telno = "subscriber_telno";
	private $type = "subscriber_type";
	private $paypal = "subscriber_paypal_account";
	private $status = "subscriber_status";
	private $username = "username";
	private $password = "password";
	private $date = "date_registered";
	private $address = "address";

	public function __construct() {
		parent::__construct();
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

	public function allForMonitor() {
		$countShop = $this->_subCountShops();
		$countSubscription = $this->_subCountSubscription();
		$select = $this->_selectSub();
		$query = $this->db
			->select($select . ", $countShop, $countSubscription")
			->from($this->table . ' as ' . $this->subAlias)
			->get();
		return $query->result();
	}

	private function _selectSub() {
		$select = array(
			$this->id,
			$this->fname, $this->lname,
			$this->telno, $this->email,
			$this->status, $this->date
		);
		return $this->subAlias . '.' . implode(', ' . $this->subAlias . '.', $select);
	}

	private function _subCountShops() {
		$this->load->model('RentalShop');
		$table = $this->RentalShop->getTable() . ' as ' . $this->shopAlias;
		$where = $this->subAlias . '.' . $this->id . ' = ';
		$where .= $this->shopAlias . '.' . $this->RentalShop->getSubscriberId();
		$select = "(SELECT count(1) FROM $table WHERE $where) as total_shops";
		return $select;
	}

	private function _subCountSubscription() {
		$this->load->model('Subscription');
		$table = $this->Subscription->getTable() . ' as '. $this->ssAlias;
		$where = $this->subAlias . '.' . $this->id . ' = ';
		$where .= $this->ssAlias . '.' . $this->Subscription->getSubscriberId();
		$select = "(SELECT count(1) FROM $table WHERE $where) as total_subscriptions";
		return $select;
 	}

	private $subAlias = 'sub';
	private $shopAlias = 'shop';
	private $ssAlias = 'ss';

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

	public function findReservation($id, $status, $select = 'r.*') {
		$joinItem = $this->_joinItem();
		$joinResDetail = $this->_joinReservationDetail();
		$joinRes = $this->_joinReservation();
		$joinLessee = $this->_joinLessee();
		$joinShop = $this->_joinShop();
		$resStat = 'r.' . $this->Reservation->getStatus();
		$whereStatus = is_array($status) ? $resStat ." = '" . implode("' OR $resStat = '", $status) . "'" : "$resStat = '$status'"; 
		$where = 's.'.$this->id . " = '$id' AND ($whereStatus)";
		$query = $this->db
			->select($select)
			->from($this->table . ' as s')
			->join($joinItem['table'], $joinItem['on'])
			->join($joinResDetail['table'], $joinResDetail['on'])
			->join($joinRes['table'], $joinRes['on'])
			->join($joinLessee['table'], $joinLessee['on'])
			->join($joinShop['table'], $joinShop['on'])
			->where($where)
			->group_by('r.'. $this->Reservation->getId())
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
		$this->load->model('Item');
		$table = $this->Item->getTable() . ' as i';
		$on = 'i.' . $this->Item->getSubscriberId() . ' = ';
		$on .= 's.' . $this->id;
		return array('table' => $table, 'on' => $on, 'type' => 'INNER');
	}

	private function _joinReservationDetail() {
		$this->load->model('ReservationDetail');
		$table = $this->ReservationDetail->getTable() . ' as rd';
		$on = 'rd.' . $this->ReservationDetail->getItemId() . ' = ';
		$on .= 'i.' . $this->Item->getId();
		return array('table' => $table, 'on' => $on, 'type' => 'INNER');
	}

	private function _joinReservation() {
		$this->load->model('Reservation');
		$table = $this->Reservation->getTable() . ' as r';
		$on = 'r.' . $this->Reservation->getId() . ' = ';
		$on .= 'rd.' . $this->ReservationDetail->getReserveId();
		return array('table' => $table, 'on' => $on);
	}

	private function _joinLessee() {
		$this->load->model('Lessee');
		$table = $this->Lessee->getTable() . ' as l';
		$on = 'r.' . $this->Reservation->getLesseeId() . ' = ';
		$on .= 'l.lessee_id';
		return array('table' => $table, 'on' => $on);
	}

	private function _joinShop() {
		$this->load->model('RentalShop');
		$table = $this->RentalShop->getTable() . ' as rs';
		$on = 'rs.'. $this->RentalShop->getId() . ' = ';
		$on .= 'i.' . $this->Item->getShopId();
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
	public function getAddress() { return $this->address; }

	private function _deleteCache() {
		$this->db->cache_delete('lessors','accountSave');
		$this->db->cache_delete('lessor','account');
		$this->db->cache_delete('lessors','signin');
	}

	public function sendSMSToSubId($subId, $message) {
		$this->load->library('ITextMo');
		// $this->load->model('Subscriber');
		$subs = $this->findId($subId);
		$number = $subs[$this->telno];
		return empty($number) ? -1 : $this->itextmo->itexmo($number, $message);
	}
}