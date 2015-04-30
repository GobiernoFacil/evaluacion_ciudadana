<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Rules model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Rules_model extends CI_Model{

  const TABLE = 'rules';
  
  function __construct(){
    parent::__construct();
  }

  function get($id){
    $q = $this->db->get_where(self::TABLE, ['blueprint_id' => $id]);
    return $q->result();
  }

  function add($rule){
    $this->db->insert(self::TABLE, $rule);
    return $this->db->insert_id();
  }

  function delete($blueprint_id, $id){
    $this->db->delete(self::TABLE, [
      'id'           => $id, 
      'blueprint_id' => $blueprint_id
    ]);

    return $this->db->affected_rows();
  }
}