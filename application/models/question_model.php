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

  function get($id){
    $q = $this->db->get_where(self::TABLE, ['blueprint_id' => $id]);
    return $q->result();
  }
}