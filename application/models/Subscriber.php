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
}