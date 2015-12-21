<?php

class Lessee extends CI_Model{

  public function create($data)
  {

    $this->db->insert('lessees',$data);

    return TRUE;

  }

  public function login($username,$password)
  {

    $this->db->select()->from('lessees')->where('username', $username);
    $query=$this->db->get();

    return $query->first_row('array');

  }

}