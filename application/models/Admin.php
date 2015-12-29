<?php

class Admin extends CI_Model{

  private $id;
  private $fname;
  private $midinit;
  private $lname;
  private $username;
  private $password;
  private $status;
  private $admin_table = 'super_admins';
  private $limit = 20;
  private $offset = 0;

  private function getId(){ return $this->id; }

  public function setId($id){ $this->id = $id; }

  private function getFname(){ return $this->fname; }

  public function setFname($fname){ $this->fname = $fname; }

  private function getLname(){ return $this->lname; }

  public function setLname($lname){ $this->lname = $lname; }

  private function getMidinit(){ return $this->midinit; }

  public function setMidinit($midinit){ $this->midinit = $midinit; }

  private function getUsername(){ return $this->username; }

  public function setUsername($username){ $this->username = $username; }

  private function getPassword(){ return $this->password; }

  public function setPassword($password){ $this->password = $password; }

  private function getStatus(){ return $this->status; }

  public function setStatus($status){ $this->status = $status; }

  public function authenticate()
  {
    $user = $this->findByUsername($this->getUsername());

    if(!empty( $user ) && $this->encrypt->decode($user['password']) == $this->getPassword()):

      return $user;

    else:

      return false;

    endif;
  }

  public function findByUsername()
  {
    $query = $this->db->get_where($this->admin_table, array('username' => $this->getUsername()));

    return $query->row_array();
  }

}