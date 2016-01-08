<?php

class Subscription extends CI_Model{

	private $table;

	private $id;
	private $startDate;
	private $endDate;
	private $amount;
	private $status;
	private $qty;
	private $subscriberId;
	private $planId;

	public function __construct() {
		parent::__construct();
		$this->id = "subscription_id";
		$this->startDate = "start_date";
		$this->endDate = "end_date";
		$this->amount = "subscription_amt";
		$this->status = "status";
		$this->qty = "qty";
		$this->subscriberId = "subscriber_id";
		$this->planId = "plan_id";
		$this->table = "subscriptions";
	}

	public function create($data) {
		$this->db->insert($this->table, $data);
		$this->db->cache_delete_all();
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
    return $this->db->affected_rows();
	}

	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		return $this->db->affected_rows();
	}

	public function findBySubscriberId($id) {
		$query = $this->db->get_where($this->table, array($this->subscriberId => $id));
		return $query->result();
	}

	public function findActiveBySubscriberId($id) {
		$query = $this->db->get_where($this->table, array(
				$this->subscriberId => $id,
				$this->status => 'active'
			)
		);
		return $query->row_array();
	}

	/** GETTERS **/
	public function getId() { return $this->id; }
	public function getStartDate() { return $this->startDate; }
	public function getEndDate() { return $this->endDate; }
	public function getAmount() { return $this->amount; }
	public function getStatus() { return $this->status; }
	public function getQty() { return $this->qty; }
	public function getSubscriberId() { return $this->subscriberId; }
	public function getPlanId() { return $this->planId; }
}