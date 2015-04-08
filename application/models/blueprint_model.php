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

  function get($id){
    $q = $this->db->get_where(self::TABLE, ['id' => $id]);
    return $q->row();
  }

  function all(){
    $q = $this->db->get(self::TABLE);
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

}