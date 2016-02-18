<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategory extends CI_Model {

	private $table;

	private $id;
	private $itemId;
	private $categoryId;

	private $offset;
	private $limit = 10;


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

	public function findShopByCategory($id) {
		$where = array("{$this->iCategoryAlias}." . $this->categoryId => $id);
		$joinItem = $this->_joinItem();
		$joinShop = $this->_joinShop();
		$from = $this->table . ' as ' . $this->iCategoryAlias;
		$data['count'] = $this->db
			->from($from)
			->join($joinItem['table'], $joinItem['on'])
			->join($joinShop['table'], $joinShop['on'])
			->where($where)
			->group_by("{$this->shopAlias}." . $this->RentalShop->getId())
			->count_all_results();
		$data['data'] = $this->db
			->select("{$this->shopAlias}.*")
			->from($from)
			->join($joinItem['table'], $joinItem['on'])
			->join($joinShop['table'], $joinShop['on'])
			->where($where)
			->group_by("{$this->shopAlias}." . $this->RentalShop->getId())
			->limit($this->limit, $this->offset)
			->get()->result();
		return $data;
	}

	public function findItemByIdAndShop($category, $shop) {
		$where = array(
			"{$this->iCategoryAlias}." . $this->categoryId => $category,
			"{$this->itemAlias}." . $this->Item->getShopId() => $shop,
		);
		$joinItem = $this->_joinItem();
		$from = $this->table . ' as ' . $this->iCategoryAlias;
		$query = $this->db
			->select("{$this->itemAlias}.*")
			->from($from)
			->join($joinItem['table'], $joinItem['on'])
			->where($where)
			->get();
		return $query->result();	
	}

	private $itemAlias = 'i';
	private $iCategoryAlias = 'ic';
	private $shopAlias = 's';

	private function _joinItem() {
		$this->load->model('Item');
		$table = $this->Item->getTable() . " as {$this->itemAlias}";
		$on = "{$this->iCategoryAlias}." . $this->itemId;
		$on .= " = {$this->itemAlias}." . $this->Item->getId();
		return array('table' => $table, 'on' => $on);
	}

	private function _joinShop() {
		$this->load->model('RentalShop');
		$table = $this->RentalShop->getTable() . " as {$this->shopAlias}";
		$on = "{$this->itemAlias}." . $this->Item->getShopId();
		$on .= " = {$this->shopAlias}." . $this->RentalShop->getId();
		return array('table' => $table, 'on' => $on);
	}

	public function getId() { return $this->id; }
	public function getItemId() { return $this->itemId; }
	public function getCategoryId() { return $this->categoryId; }
	public function getTable() { return $this->table; }

	public function setOffset($offset) { $this->offset = $offset; }
	public function setLimit($limit) { $this->limit = $limit; }

	public function getOffset() { return $this->offset; }
	public function getLimit() { return $this->limit; }


	private function deleteCache() {
		$this->db->cache_delete('itemCategories','all');
	}
}