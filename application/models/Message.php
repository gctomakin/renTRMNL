<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Model{

	private $table = 'messages';

	private $id = 'message_id';
	private $message = 'message';
	private $fromId = 'from_id';
	private $toId = 'to_id';
	private $fromType = 'from_type';
	private $toType = 'to_type';
	private $status = 'status';
	private $sent = 'sent';
	private $received = 'received';

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

	public function findByConversation($lesseeId, $lessorId) {
		$where = "({$this->fromId} = $lessorId && {$this->fromType} = 'lessor' && ";
		$where .= "{$this->toId} = $lesseeId && {$this->toType} = 'lessee') OR ";
		$where .= "({$this->fromId} = $lesseeId && {$this->fromType} = 'lessee' && ";
		$where .= "{$this->toId} = $lessorId && {$this->toType} = 'lessor')";
		$this->db->order_by($this->sent, 'ASC');
		$query = $this->db->get_where($this->table, $where);
		return $query->result();
	}

	// GETTERS
	public function getTable() { return $this->table; }
	public function getId() { return $this->id; }
	public function getMessage() { return $this->message; }
	public function getFromId() { return $this->fromId; }
	public function getToId() { return $this->toId; }
	public function getFromType() { return $this->fromType; }
	public function getToType() { return $this->toType; }
	public function getStatus() { return $this->status; }
	public function getSent() { return $this->sent; }
	public function getReceived() { return $this->received; }

	public function getLimit() { return $this->limit; }
	public function setLimit($limit) { $this->limit = $limit; }

	public function getOffset() { return $this->offset; }
	public function setOffset($offset) { return $this->offset = $offset;}
}