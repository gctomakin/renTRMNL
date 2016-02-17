<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Model{
  private $table = "accounts";
  
  /** Account columns **/
  
  private $id = "account_id";
  private $username = "username";
  private $password = "password";
  private $userId = "user_id";
  private $userType = "user_type";


  private $limit = 5;
  private $offset = 0;

  public function __construct() {
    parent::__construct();
  }

  public function create($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($data) {
    $this->db->update($this->table, $data, array(
        $this->id => $data[$this->id]
      )
    );
    return $this->db->affected_rows();
  }

  public function delete($id) {
    $this->db->delete($this->table, array($this->id => $id));
    $this->clearCache();
    return $this->db->affected_rows();
  }

  public function findUsername($username) {
    $query = $this->db->get_where($this->table, array($this->username => $username));
    return $query->row_array();
  }

  // GETTERS
  public function getId() { return $this->id; }
  public function getUsername() { return $this->username; }
  public function getPassword() { return $this->password; }
  public function getUserId() { return $this->userId; }
  public function getUserType() { return $this->userType; }
}