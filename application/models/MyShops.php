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

}