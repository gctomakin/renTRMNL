<?php

class Lessee extends CI_Model{

  private $id;
  private $fname;
  private $lname;
  private $email;
  private $phoneno;
  private $username;
  private $password;

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

  public function setLname($lname){
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

  public function authenticate($username, $password)
  {
    $user = $this->findByUsername($username);

    if(!empty( $user ) && $this->encrypt->decode($user['password']) == $password):

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
    $this->db->insert('lessees');

    return TRUE;
  }

  public function update()
  {
    $data['lessee_fname'] = $this->getFname();
    $data['lessee_lname'] = $this->getLname();
    $data['lessee_email'] = $this->getEmail();
    $data['lessee_phoneno'] = $this->getPhoneno();
    $this->db->where('lessee_id', $this->getId());
    $this->db->update('lessees', $data);

    return TRUE;
  }

  public function delete()
  {
    $this->db->where('lessee_id', $this->getId());
    $this->db->delete('lessees');

    return TRUE;
  }

  public function findByUsername()
  {
    $query = $this->db->get_where('lessees', array('username' => $this->getUsername()));

    return $query->first_row('array');
  }

  public function findById()
  {
    $query = $this->db->get_where('lessees', array('lessee_id' => $this->getId()));

    return $query->first_row('array');
  }

  public function findByEmail()
  {
    $query = $this->db->get_where('lessees', array('lessee_email' => $this->getEmail());

    return $query->first_row('array');
  }

}