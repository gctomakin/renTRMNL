<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategory extends CI_Model {

	private $table;

	private $id;
	private $itemId;
	private $categoryId;

	public function __construct() {
		parent::__construct();
		$this->table = "items_categories";
		$this->id = "id";
		$this->itemId = "item_id";
		$this->categoryId = "category_id";
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

	public function deleteAllItem($id) {
		$this->db->delete($this->table, array($this->itemId => $id));
		$this->deleteCache();
		return $this->db->affected_rows();
	}
	
	public function all($select = "*") {
		$this->db->select($select);
		$query = $this->db->from($this->table)->get();
		return $query->result();
	}

	public function findCategoryByItem($id) {
		$this->load->model('Category');
		$iCategoryAlias = 'ic';
		$categoryAlias = 'c';
		$joinCondition = "$iCategoryAlias." . $this->categoryId .
										 "= $categoryAlias. " . $this->Category->getId();
		$data = $this->db
			->select("$categoryAlias.*")
			->from($this->table . " as $iCategoryAlias")
			->join($this->Category->getTable() . " as $categoryAlias", $joinCondition, 'left')
			->where(array("$iCategoryAlias." . $this->itemId => $id))
			->get()->result();
		return $data;
	}

	public function getId() { return $this->id; }
	public function getItemId() { return $this->itemId; }
	public function getCategoryId() { return $this->categoryId; }
	public function getTable() { return $this->table; }

	private function deleteCache() {
		$this->db->cache_delete('itemCategories','all');
	}
}