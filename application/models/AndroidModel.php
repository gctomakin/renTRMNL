<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AndroidModel extends CI_Model {

	function getItemsPerCategory($id){
		$sql = "SELECT ic.item_id,item_desc,item_rate,item_qty,shop_name FROM `items_categories` ic join items i on ic.item_id = i.item_id join rental_shops s on i.shop_id = s.shop_id WHERE ic.category_id = $id";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getItemsPerShop($id){
		$sql = "SELECT item_id,item_desc,item_rate,item_qty,shop_name FROM items i join rental_shops s on i.shop_id = s.shop_id  WHERE s.shop_id = $id";
		$query = $this->db->query($sql);
		return $query->result();
	}
	function getInterestsItems($id){
		$sql = "SELECT i.item_id,item_desc,item_rate,item_qty,shop_name,m.interest_id FROM `my_interests` m join items i on m.item_id = i.item_id join rental_shops s on s.shop_id = i.shop_id WHERE lessee_id = $id";
		$query = $this->db->query($sql);
		return $query->result();

	}

	function getAllItems(){
		$sql = "SELECT i.item_id,item_desc,item_rate,item_qty,shop_name FROM items i join rental_shops s on s.shop_id = i.shop_id";
		$query = $this->db->query($sql);
		return $query->result();


	}

	function getShopsByLesseeId($id){
		
		$sql = "SELECT myshop_id,s.shop_id,shop_name,shop_branch,latitude,longitude,address FROM `my_shops` m join rental_shops s on m.shop_id = s.shop_id WHERE lessee_id = $id";
		$query = $this->db->query($sql);
		return $query->result();
	}


}
