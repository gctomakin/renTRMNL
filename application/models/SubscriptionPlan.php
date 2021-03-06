<?php

class SubscriptionPlan extends CI_Model{

  private $id;
  private $name;
  private $desc;
  private $type;
  private $rate;
  private $status;
  private $subscription_plans_table = 'subscription_plans';
  private $limit = 20;
  private $offset = 0;

  private function getId(){ return $this->id; }

  public function setId($id){ $this->id = $id; }

  private function getName(){ return $this->name; }

  public function setName($name){ $this->name = $name; }

  private function getDesc(){ return $this->desc; }

  public function setDesc($desc){ $this->desc = $desc; }

  private function getType(){ return $this->type; }

  public function setType($type){ $this->type = $type; }

  private function getRate(){ return $this->rate; }

  public function setRate($rate){ $this->rate = $rate; }

  private function getStatus(){ return $this->status; }

  public function setStatus($status){ $this->status = $status; }

  public function getTable() { return $this->subscription_plans_table; }

  public function getIdCol() { return 'plan_id'; }

  public function insert()
  {
    $data['plan_name'] = $this->getName();
    $data['plan_desc'] = $this->getDesc();
    $data['plan_type'] = $this->getType();
    $data['plan_rate'] = $this->getRate();

    try {

      $this->db->set($data);
      $this->db->insert($this->subscription_plans_table);
      $this->db->cache_delete('admins', 'subscriptions');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }

    return true;
  }

  public function all()
  {
    $query = $this->db->get($this->subscription_plans_table);

    return $query->result();
  }

  public function findById($id) {
    $query = $this->db->get_where($this->subscription_plans_table, array('plan_id' => $id));
    return $query->row_array();
  }

}