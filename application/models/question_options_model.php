<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Question options model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Question_options_model extends CI_Model{

  const TABLE = 'options';
  
  function __construct(){
    parent::__construct();
  }

  function get($id){
    $q = $this->db->get_where(self::TABLE, ['blueprint_id' => $id]);
    return $q->result();
  }

  function save($option){
    $this->db->insert(self::TABLE, $option);
    return $this->db->insert_id();
  }

  function delete($id){
    $this->db->delete(self::TABLE, ['id' => $id]);
  }
}