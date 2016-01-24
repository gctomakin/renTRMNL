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
		$query = $this->db
			->from($this->table . ' as s')
			->join('subscription_plans as sp', 's.' . $this->planId . ' = sp.plan_id')
			->where(array('s.' . $this->subscriberId => $id))
			->get();
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

	/**
	 * Find Subscription and Plan from Active by Subscriber's id
	 * @param  Int $id Subscriber's Id
	 * @return array     SubscriptionPlan
	 */
	public function findActivePlanBySubId($id) {
		$query = $this->db
			->from($this->table . ' as s')
			->join('subscription_plans as sp', 's.' . $this->planId . ' = sp.plan_id')
			->where(array('s.' . $this->subscriberId => $id, 's.' . $this->status => 'active'))
			->get();
		return $query->row_array();
	}

	public function countTotalByDate($from, $to = "") {
		if ($from == $to) {
		 	$where = "DATE({$this->startDate}) = DATE('$from')";
		} else {
			$where = "{$this->startDate} between '$from' and '$to'";
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
		$where = array("DATE({$this->startDate}) >=" => $from);
		if (!empty($to)) {
			$where["DATE({$this->startDate}) <="] = $to;
		}
		if (!empty($status)) {
			$where[$this->status] = $status;
		}
		$query = $this->db
			->from($this->table . ' as s')
			->join('subscription_plans as sp', 's.' . $this->planId . ' = sp.plan_id')
			->where($where)
			->get();
		return $query->result();
	}



	public function findPendings() {
		$join = $this->_joinSubscriber();
		$query = $this->db
			->select('s.*, sub.*')
			->from($this->table . ' as s')
			->join($join['table'], $join['on'], $join['type'])
			->where(array($this->status => 'pending'))
			->get();
		return $query->result();
	}

	private function _joinSubscriber() {
		$this->load->model('Subscriber');
		$table = $this->Subscriber->getTable() . ' as sub';
		$on = 's.' . $this->subscriberId . ' = ' . 'sub.' . $this->Subscriber->getId();
		return array('table' => $table, 'on' => $on, 'type' => 'INNER');
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