<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Question model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Answers_model extends CI_Model{

  const TABLE = 'answers';
  
  function __construct(){
    parent::__construct();
  }

  function save($data){
    $where = [
      'blueprint_id' => $data['blueprint_id'],
      'question_id'  => $data['question_id'],
      'form_key'     => $data['form_key']
    ];

    $q = $this->db->get_where(self::TABLE, $where);

    if($q->num_rows() > 0){
      $this->db->update(self::TABLE, $data, $where);
    }
    else{
      $this->db->insert(self::TABLE, $data); 
    }

    return $this->db->affected_rows();
  }

  function get($form_key){
    $q = $this->db->get_where(self::TABLE, ['form_key' => $form_key]);
    return $q->result();
  }
}