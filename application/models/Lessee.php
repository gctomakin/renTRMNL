<?php

class Lessee extends CI_Model{

  private $id;
  private $fname;
  private $lname;
  private $email;
  private $phoneno;
  private $username;
  private $password;
  private $table = 'lessees';

  private function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  private function getFname()
  {
    return $this->fname;
  }

  public function setFname($fname)
  {
    $this->fname = $fname;
  }

  private function getLname()
  {
    return $this->lname;
  }

  public function setLname($lname)
  {
    $this->lname = $lname;
  }

  private function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  private function getPhoneno()
  {
    return $this->phoneno;
  }

  public function setPhoneno($phoneno)
  {
    $this->phoneno = $phoneno;
  }

  private function getUsername()
  {
    return $this->username;
  }

  public function setUsername($username)
  {
    $this->username = $username;
  }

  private function getPassword()
  {
    return $this->password;
  }

  public function setPassword($password)
  {
    $this->password = $password;
  }

  public function authenticate()
  {
    $user = $this->findByUsername($this->getUsername());

    if(!empty( $user ) && $this->encrypt->decode($user['password']) == $this->getPassword()):

      return $user;

    else:

      return FALSE;

    endif;
  }

  public function all()
  {
    $query = $this->db->get('lessees');

    return $query;
  }

  public function insert()
  {
    $data['lessee_fname'] = $this->getFname();
    $data['lessee_lname'] = $this->getLname();
    $data['lessee_email'] = $this->getEmail();
    $data['lessee_phoneno'] = $this->getPhoneno();
    $data['username'] = $this->getUsername();
    $data['password'] = $this->getPassword();
    $this->db->set($data);
    $this->db->insert($this->table);

    return TRUE;
  }

  public function update()
  {
    $data['lessee_fname'] = $this->getFname();
    $data['lessee_lname'] = $this->getLname();
    $data['lessee_email'] = $this->getEmail();
    $data['lessee_phoneno'] = $this->getPhoneno();
    $this->db->where('lessee_id', $this->getId());
    $this->db->update($this->table, $data);

    return TRUE;
  }

  public function delete()
  {
    $this->db->where('lessee_id', $this->getId());
    $this->db->delete($this->table);

    return TRUE;
  }

  public function findByUsername()
  {
    $query = $this->db->get_where($this->table, array('username' => $this->getUsername()));

    return $query->row_array();
  }

  public function findById()
  {
    $query = $this->db->get_where($this->table, array('lessee_id' => $this->getId()));

    return $query->row_array();
  }

  public function findByEmail()
  {
    $query = $this->db->get_where($this->table, array('lessee_email' => $this->getEmail()));

    return $query->row_array();
  }

}