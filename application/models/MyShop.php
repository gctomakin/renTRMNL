<?php

class MyShop extends CI_Model
{

  private $id;
  private $myshopname;
  private $lessee_id;
  private $shop_id;
  private $my_shops_table = 'my_shops';
  private $limit = 10;
  private $offset = 0;

  private function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  private function getMyShopName()
  {
    return $this->myshopname;
  }

  public function setMyShopName($myshopname)
  {
    $this->myshopname = $myshopname;
  }

  private function getLesseeId()
  {
    return $this->lessee_id;
  }

  public function setLesseeId($lessee_id)
  {
    $this->lessee_id = $lessee_id;
  }

  private function getShopId()
  {
    return $this->shop_id;
  }

  public function setShopId($shop_id)
  {
    $this->shop_id = $shop_id;
  }

  public function setOffset($offset) { $this->offset = $offset; }
  public function getLimit() { return $this->limit; }
  public function insert()
  {
    $data['myshop_name'] = $this->getMyShopName();
    $data['lessee_id']   = $this->getLesseeId();
    $data['shop_id']     = $this->getShopId();

    try {

      $this->db->set($data);
      $this->db->insert($this->my_shops_table);
      $this->db->cache_delete('lessee', 'myshops');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }


    return true;
  }

  public function all()
  {
    $this->db->select('rental_shops.shop_id, rental_shops.shop_image, rental_shops.shop_name, rental_shops.shop_desc, rental_shops.shop_branch, rental_shops.subscriber_id, rental_shops.address, my_shops.myshop_id');
    $this->db->from('rental_shops');
    $this->db->join($this->my_shops_table, 'my_shops.shop_id = rental_shops.shop_id');
    $this->db->limit($this->limit, $this->offset);
    $query  = $this->db->get();
    $result = $query->result();

    return $result;
  }

  public function allCount() {
    $count = $this->db
      ->from('rental_shops')
      ->join($this->my_shops_table, 'my_shops.shop_id = rental_shops.shop_id')
      ->count_all_results();
    return $count;
  }

  public function getMyShopsId()
  {
    $query  = $this->db->get_where($this->my_shops_table, array(
      'lessee_id' => $this->getLesseeId()
    ), $this->limit, $this->offset);
    $result = array();
    foreach ($query->result_array() as $row):
      $result[] = $row['shop_id'];
    endforeach;
    $this->db->cache_delete('lessee', 'shops');
    return array_values($result);
  }

  public function delete()
  {

    try {

      $this->db->where('myshop_id', $this->getId());
      $this->db->delete($this->my_shops_table);
      $this->db->cache_delete('lessee', 'myshops');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }


    return TRUE;
  }

}