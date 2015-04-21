<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Question model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Question_model extends CI_Model{

  const TABLE = 'questions';
  
  function __construct(){
    parent::__construct();
  }

  function get($id, $array = false, $remove_descriptions = false){
    $where = ['blueprint_id' => $id];
    if($remove_descriptions){
      $where['is_description'] = 0;
    }
    $q = $this->db->get_where(self::TABLE, $where);
    return $array ? $q->result_array() : $q->result();
  }

  function validate($id, $blueprint_id){
    $q = $this->db->get_where(self::TABLE, ['id' => $id, 'blueprint_id' => $blueprint_id]);
    return $q->row();
  }

  function add($question){
    $this->db->insert(self::TABLE, $question);
    return $this->db->insert_id();
  }

  function update($id, $question){
    $this->db->update(self::TABLE, $question, ['id' => $id]);
    return $this->db->affected_rows();
  }

  function delete($blueprint_id, $id){
    $this->db->delete(self::TABLE, [
      'id'           => $id, 
      'blueprint_id' => $blueprint_id
    ]);

    return $this->db->affected_rows();
  }
}