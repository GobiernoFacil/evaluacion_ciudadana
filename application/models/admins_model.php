<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Admins model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Admins_model extends CI_Model{

  const TABLE = 'admins';
  
  function __construct(){
    parent::__construct();
  }

  function get($id){
    $q = $this->db->get_where(self::TABLE, ['id' => $id]);
    return $q->row();
  }

  function get_by_email($email){
    $q = $this->db->get_where(self::TABLE, ['email' => $email]);
    return $q->row();
  }

  function exist($email){
    $this->db->from(self::TABLE);
    $this->db->where('email',$email);
    return $this->db->count_all_results();
  }

  function can_reset_password($pass_key){
    $today = date_format(date_create(null), 'Y-m-d');
    $this->db->from(self::TABLE);
    $this->db->where('expire_date >', $today);
    $this->db->where('pass_key', $pass_key);
    $q = $this->db->get();
    return $q->row();
  }

  function all(){
    $q = $this->db->get(self::TABLE);
    return $q->result();
  }

  function add($admin){
    $this->db->insert(self::TABLE, $admin);
    return $this->db->insert_id();
  }

  function update($id, $admin){
    $this->db->update(self::TABLE, $admin, ['id' => $id]);
    return $this->db->affected_rows();
  }

  function delete($id){
    $this->db->delete(self::TABLE, ['id' => $id]);
    return $this->db->affected_rows();
  }
}