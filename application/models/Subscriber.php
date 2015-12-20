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

	public function __construct() {
		parent::__construct();
		$this->id = "subscriber_id";
		$this->fname = "subscriber_fname";
		$this->mi = "subscriber_midinit";
		$this->lname = "subscriber_lname";
		$this->email = "subscriber_email_add";
		$this->addr = "subscriber_addr";
		$this->telno = "subscriber_telno";
		$this->type = "subscriber_type";
		$this->paypal = "subscriber_paypal_account";
		$this->status = "subscriber_status";
		$this->username = "username";
		$this->password = "password";
		$this->table = "subscribers";
	}

	public function create($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	/** GETTERS */
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
}