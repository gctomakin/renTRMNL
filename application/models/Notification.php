<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Model {

	private $table = 'notifications';

	private $id = 'notification_id';
	private $fromId = 'from_id';
	private $toId = 'to_id';
	private $fromType = 'from_type';
	private $toType = 'to_type';
	private $sent = 'sent';
	private $link = 'link';
	private $notification = 'notification';
	private $status = 'status';

	private $limit = 5;
	private $offset = 0;

	public function __construct() {
		parent::__construct();
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

	public function findByTo($id, $type, $order = 'DESC') {
		$where = array(
			$this->toId => $id,
			$this->toType => $type
		);
		
		// if ($type == 'lessee') {
			$joinLessor = $this->_joinFromLessor();
		// } else {
			$joinLessee = $this->_joinFromLessee();
		// }

		$data['count'] = $this->db
			->from($this->table)
			->where($where)
			->count_all_results();

		$data['result'] = $this->db
			// ->select($join['alias'] . '.*, ' . $this->nAlias . '.*, ' . $join['fname'])
			->from($this->table . ' as ' . $this->nAlias)
			->join($joinLessor['table'], $joinLessor['on'], 'LEFT')
			->join($joinLessee['table'], $joinLessee['on'], 'LEFT')
			->where($where)
			->limit($this->limit)
			->offset($this->offset)
			->order_by($this->id, $order)
			->get()
			->result();
		return $data;
	}

	private function _joinFromLessee() {
		$this->load->model('Lessee');
		$data['table'] = 'lessees as ' . $this->lAlias;
		$data['on'] = $this->lAlias . '.lessee_id = ';
		$data['on'] .= $this->nAlias . '.' . $this->fromId;
		// $data['alias'] = $this->lAlias;
		// $data['fname'] = $this->lAlias .'.lessee_fname as first_name';
		return $data; 
	}

	private function _joinFromLessor() {
		$this->load->model('Subscriber');
		$data['table'] = $this->Subscriber->getTable() . ' as ' . $this->sAlias;
		$data['on'] = $this->sAlias . '.' . $this->Subscriber->getId() . ' = ';
		$data['on'] .= $this->nAlias . '.' . $this->fromId;
		// $data['alias'] = $this->sAlias;
		// $data['fname'] = $this->sAlias . '.' . $this->Subscriber->getFname() . ' as first_name';
		return $data; 
	}

	private $lAlias = 'l';
	private $nAlias = 'n';
	private $sAlias = 's';

	//GETTERS
	public function getTable() { return $this->table; }
	public function getId() { return $this->id; }
	public function getFromId() { return $this->fromId; }
	public function getToId() { return $this->toId; }
	public function getFromType() { return $this->fromType; }
	public function getToType() { return $this->toType; }
	public function getSent() { return $this->sent; }
	public function getLink() { return $this->link; }
	public function getNotification() { return $this->notification; }
	public function getStatus() { return $this->status; }

	public function getLimit() { return $this->limit; }
	public function getOffset() { return $this->offset; }

	public function setLimit($limit) { return $this->limit = $limit; }
	public function setOffset($offset) { return $this->offset = $offset; }
}