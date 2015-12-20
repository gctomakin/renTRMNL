<?php

class Lessees extends CI_Model{

  public function create($data)
  {

    $this->db->insert('rentrmnl_lessee',$data);

    return TRUE;

  }

  public function login($username,$password)
  {

    $this->db->select()->from('rentrmnl_lessee')->where('username', $username);
    $query=$this->db->get();

    return $query->first_row('array');

  }

}