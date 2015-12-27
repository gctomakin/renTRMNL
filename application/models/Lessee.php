<?php

class Lessee extends CI_Model{

  private $id;
  private $fname;
  private $lname;
  private $email;
  private $phoneno;
  private $username;
  private $password;
  private $image;
  private $lessees_table = 'lessees';
  private $myinterests_table = 'my_interests';
  private $myshops_table = 'my_shops';
  private $limit = 20;
  private $offset = 0;

  private function getId(){ return $this->id; }

  public function setId($id){ $this->id = $id; }

  private function getFname(){ return $this->fname; }

  public function setFname($fname){ $this->fname = $fname; }

  private function getLname(){ return $this->lname; }

  public function setLname($lname){ $this->lname = $lname; }

  private function getEmail(){ return $this->email; }

  public function setEmail($email){ $this->email = $email; }

  private function getPhoneno(){ return $this->phoneno; }

  public function setPhoneno($phoneno){ $this->phoneno = $phoneno; }

  private function getUsername(){ return $this->username; }

  public function setUsername($username){ $this->username = $username; }

  private function getPassword(){ return $this->password; }

  public function setPassword($password){ $this->password = $password; }

  private function getImage(){ return $this->image; }

  public function setImage($image){ $this->image = $image; }

  public function authenticate()
  {
    $user = $this->findByUsername($this->getUsername());

    if(!empty( $user ) && $this->encrypt->decode($user['password']) == $this->getPassword()):

      return $user;

    else:

      return false;

    endif;
  }

  public function googleLogin($user)
  {
    $this->setUsername($user['id']);
    $result = $this->findByUsername($this->getUsername());

    if(!empty($result)):

      return $result;

    else:

      $data['lessee_fname'] = $user['givenName'];
      $data['lessee_lname'] = $user['familyName'];
      $data['lessee_email'] = $user['email'];
      $data['lessee_phoneno'] = "";
      $data['username'] = $user['id'];
      $data['password'] = "";
      $this->db->set($data);
      $this->db->insert($this->lessees_table);
      $lastId = $this->db->insert_id();

      return $lastId;

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
    $this->db->insert($this->lessees_table);

    return true;
  }

  public function updateInfo()
  {
    $data['lessee_fname'] = $this->getFname();
    $data['lessee_lname'] = $this->getLname();
    $data['lessee_email'] = $this->getEmail();
    $data['lessee_phoneno'] = $this->getPhoneno();
    $this->db->where('lessee_id', $this->getId());
    $this->db->update($this->lessees_table, $data);

    return true;
  }

  public function updateAccount()
  {
    $data['username'] = $this->getUsername();
    $data['password'] = $this->getPassword();
    $this->db->where('lessee_id', $this->getId());
    $this->db->update($this->lessees_table, $data);

    return true;
  }

  public function delete()
  {
    $this->db->where('lessee_id', $this->getId());
    $this->db->delete($this->lessees_table);

    return true;
  }

  public function findByUsername()
  {
    $query = $this->db->get_where($this->lessees_table, array('username' => $this->getUsername()));

    return $query->row_array();
  }

  public function findById()
  {
    $query = $this->db->get_where($this->lessees_table, array('lessee_id' => $this->getId()));

    return $query->row_array();
  }

  public function findByEmail()
  {
    $query = $this->db->get_where($this->lessees_table, array('lessee_email' => $this->getEmail()));

    return $query->row_array();
  }

  public function checkPassword()
  {
    $user = $this->findById();
    $query = $this->db->get_where($this->lessees_table, array('password' => $user['password']));
    if($this->getPassword() == $this->encrypt->decode($query->row_array()['password'])):
      return true;
    else:
      return false;
    endif;
  }

  public function uploadImage()
  {
    $data['image'] = $this->getImage();
    $this->db->where('lessee_id', $this->getId());
    $this->db->update($this->lessees_table, $data);

    return true;
  }

  public function myInterests()
  {
     $query = $this->db->get_where($this->myinterests_table, array('lessee_id' => $this->getId()), $this->limit, $this->offset);
     $result = $query->result();

     return $result;
  }

  public function myShops()
  {
     $query = $this->db->get_where($this->myshops_table, array('lessee_id' => $this->getId()), $this->limit, $this->offset);
     $result = $query->result();

     return $result;
  }

  public function getAllRents()
  {
     echo 'TODO';
  }

  public function getAllReserves()
  {
     echo 'TODO';
  }

  public function getAllReturns()
  {
     echo 'TODO';
  }

  public function getAllNotifications()
  {
     echo 'TODO';
  }


}