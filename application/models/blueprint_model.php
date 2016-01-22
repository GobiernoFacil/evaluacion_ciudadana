<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Blueprint model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Blueprint_model extends CI_Model{

  const TABLE = 'blueprints';
  
  function __construct(){
    parent::__construct();
  }

  function search($string){
    $this->db->like("title", $string);
    $q = $this->db->get(self::TABLE);
    return $q->result();
  }

  function get($id, $creator = false){
    $where = ['id' => $id, 'is_visible' => 1];
    if($creator) $where['creator'] = $creator;
    $q = $this->db->get_where(self::TABLE, $where);
    return $q->row();
  }

  function all($only_public = false){
    $where = ['is_visible' => 1];
    if($only_public) $where['is_public'] = 1;
    $q = $this->db->get_where(self::TABLE, $where);
    return $q->result();
  }

  function all_public(){
    $q = $this->db->get_where(self::TABLE, ['is_public' => 1, 'is_visible' => 1]);
    return $q->result();
  }

  function all_from($user_id, $public = false){
    $where = ['creator' => $user_id, 'is_visible' => 1];
    if($public) $where['is_public'] = 1;
    $q = $this->db->get_where(self::TABLE, $where);
    return $q->result();
  }

  function add($blueprint){
    $this->db->insert(self::TABLE, $blueprint);
    return $this->db->insert_id();
  }

  function update($id, $blueprint){
    $this->db->update(self::TABLE, $blueprint, ['id' => $id]);
    return $this->db->affected_rows();
  }

  function delete($id){
    $this->db->delete(self::TABLE, ['id' => $id]);
    return $this->db->affected_rows();
  }

  function soft_delete($id){
    $this->db->update(self::TABLE, ['is_visible' => 0], ['id' => $id]);
    return $this->db->affected_rows();
  }

}