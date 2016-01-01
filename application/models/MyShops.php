<?php

class MyShops extends CI_Model{

  private $id;
  private $myshopname;
  private $lessee_id;
  private $shop_id;
  private $my_shops_table = 'my_shops';
  private $limit = 20;
  private $offset = 0;

  private function getId(){ return $this->id; }

  public function setId($id){ $this->id = $id; }

  private function getMyShopName(){ return $this->myshopname; }

  public function setMyShopName($myshopname){ $this->myshopname = $myshopname; }

  private function getLesseeId(){ return $this->lessee_id; }

  public function setLesseeId($lessee_id){ $this->lessee_id = $lessee_id; }

  private function getShopId(){ return $this->shop_id; }

  public function setShopId($shop_id){ $this->shop_id = $shop_id; }



  public function insert()
  {
    $data['myshop_name'] = $this->getMyShopName();
    $data['lessee_id'] = $this->getLesseeId();
    $data['shop_id'] = $this->getShopId();
    $this->db->set($data);
    $this->db->insert($this->my_shops_table);
    $this->db->cache_delete('lessee','myshops');

    return true;
  }

  public function all()
  {
    $this->db->select('rental_shops.shop_id, rental_shops.shop_name, rental_shops.shop_branch, rental_shops.subscriber_id, rental_shops.address');
    $this->db->from('rental_shops');
    $this->db->join($this->my_shops_table, 'my_shops.shop_id = rental_shops.shop_id');
    $query = $this->db->get();
    $result = $query->result();

    return $result;
  }

  public function getMyShopsId()
  {
    $query = $this->db->get_where($this->my_shops_table, array('lessee_id' => $this->getLesseeId()), $this->limit, $this->offset);
    $result = array();
    foreach($query->result_array() as $row):
        $result[] = $row['shop_id'];
    endforeach;
    $this->db->cache_delete('lessee','shops');
    return array_values($result);
  }

}