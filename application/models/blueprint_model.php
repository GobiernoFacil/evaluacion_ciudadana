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
    $q = $this->db->get(self::TABLE, ['id' => $id]);
    return $q->row();
  }
}