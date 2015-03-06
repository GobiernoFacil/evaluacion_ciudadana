<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Question model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Applicants_model extends CI_Model{

  const TABLE = 'applicants';
  
  function __construct(){
    parent::__construct();
  }

  function get($form_key){
    $q = $this->db->get_where(self::TABLE, ['form_key' => $form_key]);
    return $q->row();
  }

  function save($applicant){
    $this->db->insert(self::TABLE, $applicant);
    return $this->db->insert_id();
  }

  function all($blueprint_id){
    $q = $this->db->get_where(self::TABLE, ['blueprint_id' => $blueprint_id]);
    return $q->result();
  }
}