<?php

class Lessee extends CI_Model
{

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
  private $limit = 20;
  private $offset = 0;

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

  private function getImage()
  {
    return $this->image;
  }

  public function setImage($image)
  {
    $this->image = $image;
  }

  public function authenticate()
  {
    $user = $this->findByUsername($this->getUsername());
    $this->db->cache_delete('lessees', 'signin');

    if (!empty($user) && $this->encryption->decrypt($user['password']) == $this->getPassword()):
      return $user;
    else:
      return false;
    endif;
  }

  public function googleLogin($user)
  {
    $this->setUsername($user['id']);
    $result = $this->findByUsername($this->getUsername());

    if (!empty($result)):
      $this->db->cache_delete('default', 'index');
      return $result;
    else:
      $data['lessee_fname']   = $user['givenName'];
      $data['lessee_lname']   = $user['familyName'];
      $data['lessee_email']   = $user['email'];
      $data['lessee_phoneno'] = "";
      $data['username']       = $user['id'];
      $data['password']       = "";
      $this->db->set($data);
      $this->db->insert($this->lessees_table);
      $lastId = $this->db->insert_id();
      $this->db->cache_delete('default', 'index');

      return $lastId;
    endif;
  }

  public function all()
  {
    $query = $this->db->get('lessees');

    return $query;
  }

  public function allForMonitor() {
    $select = $this->_lesseeSelect();
    $countReservation = $this->_subCountReservation();
    $countPenalty = $this->_subCountPenalty();
    $query = $this->db
      ->select($select . ", $countReservation, $countPenalty")
      ->from($this->lessees_table . ' as ' . $this->lesseeAlias)
      ->get();
    return $query->result();
  }

  public function _subCountReservation() {
    $this->load->model('Reservation');
    $table = $this->Reservation->getTable() . ' as ' . $this->rAlias;
    $where = $this->lesseeAlias . '.lessee_id = ';
    $where .= $this->rAlias . '.' . $this->Reservation->getLesseeId();
    $select = "(SELECT count(1) FROM $table WHERE $where) as total_reservation";
    return $select;
  }

  public function _subCountPenalty() {
    $this->load->model('Penalty');
    $this->load->model('Reservation');
    $table = $this->Penalty->getTable() . ' as ' . $this->pAlias;
    $join = $this->Reservation->getTable() . ' as ' . $this->rAlias;
    $on = $this->rAlias . '.' . $this->Reservation->getId() . ' = ';
    $on .= $this->pAlias . '.' . $this->Penalty->getReserveId();
    $where = $this->rAlias . '.' . $this->Reservation->getId() . ' = ';
    $where .= $this->pAlias . '.' . $this->Penalty->getReserveId();
    $select = "(SELECT count(1) FROM $table LEFT JOIN $join ON $on WHERE $where) as total_penalty";
    return $select;
  }

  private function _lesseeSelect() {
    $select = array(
      'lessee_id', 'image',
      'lessee_fname', 'lessee_lname',
      'lessee_email', 'lessee_phoneno'
    );
    return $this->lesseeAlias . '.' . implode(', ' . $this->lesseeAlias . '.', $select);
  }

  private $lesseeAlias = 'l';
  private $rdAlias = 'rd';
  private $rAlias = 'r';
  private $pAlias = 'p';

  public function insert()
  {
    $data['lessee_fname']   = $this->getFname();
    $data['lessee_lname']   = $this->getLname();
    $data['lessee_email']   = $this->getEmail();
    $data['lessee_phoneno'] = $this->getPhoneno();
    $data['username']       = $this->getUsername();
    $data['password']       = $this->getPassword();

    try {

      $this->db->set($data);
      $this->db->insert($this->lessees_table);
      $this->db->cache_delete('lessee', 'profile');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }

    return $this->db->insert_id();
  }

  public function updateInfo()
  {
    $data['lessee_fname']   = $this->getFname();
    $data['lessee_lname']   = $this->getLname();
    $data['lessee_email']   = $this->getEmail();
    $data['lessee_phoneno'] = $this->getPhoneno();

    try {

      $this->db->where('lessee_id', $this->getId());
      $this->db->update($this->lessees_table, $data);
      $this->db->cache_delete('lessee', 'profile');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }

    return true;
  }

  public function updateAccount()
  {
    $data['username'] = $this->getUsername();
    $data['password'] = $this->getPassword();

    try {

      $this->db->where('lessee_id', $this->getId());
      $this->db->update($this->lessees_table, $data);
      $this->db->cache_delete('lessee', 'profile');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }


    return true;
  }

  public function delete()
  {

    try {

      $this->db->where('lessee_id', $this->getId());
      $this->db->delete($this->lessees_table);

    }
    catch (Exception $err) {

      die($err->getMessage());

    }

    return true;
  }

  public function findByUsername()
  {
    $query = $this->db->get_where($this->lessees_table, array(
      'username' => $this->getUsername()
    ));

    return $query->row_array();
  }

  public function findById()
  {
    $query = $this->db->get_where($this->lessees_table, array(
      'lessee_id' => $this->getId()
    ));

    return $query->row_array();
  }

  public function findByEmail()
  {
    $query = $this->db->get_where($this->lessees_table, array(
      'lessee_email' => $this->getEmail()
    ));

    return $query->row_array();
  }

  public function checkPassword()
  {
    $user  = $this->findById();
    $query = $this->db->get_where($this->lessees_table, array(
      'password' => $user['password']
    ));
    if ($this->getPassword() == $this->encryption->decrypt($query->row()->password)):
      return true;
    else:
      return false;
    endif;
  }

  public function uploadImage()
  {
    $data['image'] = $this->getImage();

    try {

      $this->db->where('lessee_id', $this->getId());
      $this->db->update($this->lessees_table, $data);
      $this->db->cache_delete('lessee', 'profile');

    }
    catch (Exception $err) {

      die($err->getMessage());

    }

    return true;
  }

  public function myInterests()
  {
    $query  = $this->db->get_where($this->myinterests_table, array(
      'lessee_id' => $this->getId()
    ), $this->limit, $this->offset);
    $result = $query->result();

    return $result;
  }

  public function countTotalByDate($from, $to = "") {
    if ($from == $to) {
      $where = "DATE(date_registered) = DATE('$from')";
    } else {
      $where = "date_registered between '$from' and '$to'";
    }
    $query = $this->db
      ->select('count(lessee_id) as total')
      ->from('lessees')
      ->where($where)
      ->get();
    $result = $query->row_array(); 
    return $result['total'];
  }

  public function findByDate($from, $to = "", $status = "") {
    $where = array("DATE(date_registered) >=" => $from);
    if (!empty($to)) {
      $where["DATE(date_registered) <="] = $to;
    }
    if (!empty($status)) {
      $where['status'] = $status;
    }
    $query = $this->db->get_where('lessees', $where);
    return $query->result();
  }

  public function isEmailExist($email, $id = "") {
    if (!empty($id)) {
      $where['lessee_id <>'] = $id;
    }
    $where['lessee_email'] = $email;
    $query = $this->db->get_where($this->lessees_table, $where);
    return $query->num_rows() > 0;
  }

  public function getTable() { return $this->lessees_table; }
}