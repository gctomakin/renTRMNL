<?php

class MyInterest extends CI_Model
{

  private $id;
  private $myinterestname;
  private $lessee_id;
  private $item_id;
  private $my_interests_table = 'my_interests';
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

  private function getMyinterestName()
  {
    return $this->myinterestname;
  }

  public function setMyinterestName($myinterestname)
  {
    $this->myinterestname = $myinterestname;
  }

  private function getLesseeId()
  {
    return $this->lessee_id;
  }

  public function setLesseeId($lessee_id)
  {
    $this->lessee_id = $lessee_id;
  }

  private function getItemId()
  {
    return $this->item_id;
  }

  public function setItemId($item_id)
  {
    $this->item_id = $item_id;
  }



  public function insert()
  {
    $data['interest_name'] = $this->getMyInterestName();
    $data['lessee_id']   = $this->getLesseeId();
    $data['item_id']     = $this->getItemId();

    try {

      $this->db->set($data);
      $this->db->insert($this->my_interests_table);
      $this->db->cache_delete('lessee', 'myinterests');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }


    return true;
  }

  private $shopAlias = 's';
  private $subscriberAlias = 'sub';
  private $itemAlias = 'i';
  private $shopId = "shop_id";
  private $subscriberId = "subscriber_id";

  private function _joinShop() {
    $this->load->model('RentalShop');
    $table = $this->RentalShop->getTable() . " as " . $this->shopAlias;
    $on = $this->shopAlias. "." .  $this->RentalShop->getId();
    $on .= " = " . $this->itemAlias . "." . $this->shopId;
    return array('table' => $table, 'on' => $on, 'type' => 'left');
  }

  private function _joinSubscriber() {
    $this->load->model('Subscriber');
    $table = $this->Subscriber->getTable() . " as " . $this->subscriberAlias;
    $on = $this->subscriberAlias. "." . $this->Subscriber->getId();
    $on .= " = " . $this->itemAlias. "." . $this->subscriberId;
    return array('table' => $table, 'on' => $on, 'type' => 'left');
  }

  public function all()
  {
    $joinShop = $this->_joinShop();
    $joinSubs = $this->_joinSubscriber();

    $this->db->select('*');
    $this->db->from('items' . ' as ' . $this->itemAlias);
    $this->db->join($this->my_interests_table, 'my_interests.item_id = i.item_id');
    $this->db->join($joinShop['table'], $joinShop['on'], $joinShop['type']);
    $this->db->join($joinSubs['table'], $joinSubs['on'], $joinSubs['type']);
    $this->db->limit($this->limit, $this->offset);
    $query  = $this->db->get();
    $result = $query->result();

    return $result;
  }

  public function getAllCount() {
    $this->db->from('items');
    $this->db->join($this->my_interests_table, 'my_interests.item_id = items.item_id');
    return $this->db->count_all_results();
  }

  public function getMyInterestId()
  {
    $query  = $this->db->get_where($this->my_interests_table, array(
      'lessee_id' => $this->getLesseeId()
    ), $this->limit, $this->offset);
    $result = array();
    foreach ($query->result_array() as $row):
      $result[] = $row['item_id'];
    endforeach;
    $this->db->cache_delete('lessee', 'items');
    return array_values($result);
  }

  public function delete()
  {

    try {

      $this->db->where('interest_id', $this->getId());
      $this->db->delete($this->my_interests_table);
      $this->db->cache_delete('lessee', 'myinterests');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }


    return TRUE;
  }

  public function getLimit() { return $this->limit; }
  public function setOffset($offset) { $this->offset = $offset; }
}