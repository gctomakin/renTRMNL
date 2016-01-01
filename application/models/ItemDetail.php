<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemDetail extends CI_Model {

	private $table;

	private $id;
	private $type;
	private $size;
	private $brand;
	private $color;
	private $itemId;

	public function __construct() {
		parent::__construct();
		$this->table = "item_details";
		$this->id = "id";
		$this->type = "type";
		$this->size = "size";
		$this->brand = "brand";
		$this->color = "color";
		$this->itemId = "item_id";
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

	public function findByItem($id) {
		$query = $this->db->get_where($this->table, array($this->itemId => $id));
		return $query->result();
	}

	
	public function getTable() { return $this->table; }
	public function getId() { return $this->id; }
	public function getType() { return $this->type; }
	public function getSize() { return $this->size; }
	public function getBrand() { return $this->brand; }
	public function getColor() { return $this->color; }
	public function getItemId() { return $this->itemId; }

	private function deleteCache() {
		$this->db->cache_delete('items','detail');
		$this->db->cache_delete('items','detailSave');
	}
}