<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Model {

	private $table;

	// Columns
	private $id;
	private $type;

	public function __construct() {
		$this->table = "categories";
		$this->id = "category_id";
		$this->type = "category_type";
		parent::__construct();
	}

	public function create($data) {
		$this->db->insert($this->table, $data);
		$this->deleteCache();
		return $this->db->insert_id();
	}

	public function update($data) {
		$this->db->update($this->table, $data, array(
				$this->id => $data[$this->id]
			)
		);
		$this->deleteCache();
		return $this->db->affected_rows();
	}


	public function delete($id) {
		$this->db->delete($this->table, array($this->id => $id));
		$this->deleteCache();
		return $this->db->affected_rows();
	}

	public function all($select = "*", $like = "") {
		$this->db->select($select);
		$query = $this->db->from($this->table)->like($this->type, $like)->get();
		return $query->result();
	}

	public function isExist($id) {
		$query = $this->db->get_where($this->table, array($this->id => $id));
		return $query->num_rows() > 0;
	}

	public function getId() { return $this->id; }
	public function getType() { return $this->type; }

	private function deleteCache() {
		$this->db->cache_delete('categories','all');
	}

}
