<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Question model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Applicants_model extends CI_Model{

  const TABLE = 'applicants';
  static $base_url;
  
  function __construct(){
    parent::__construct();
    $this->base_url = base_url();
  }

  function get($form_key){
    $q = $this->db->get_where(self::TABLE, ['form_key' => $form_key]);
    return $q->row();
  }

  function get_key($user_email, $blueprint_id){
    $this->db->select('form_key');
    $this->db->where(['user_email' => $user_email, 'blueprint_id' => $blueprint_id]);
    $q = $this->db->get(self::TABLE);

    return $q->row();
  }

  function save($applicant){
    $this->db->insert(self::TABLE, $applicant);
    return $this->db->insert_id();
  }

  function all($blueprint_id, $as_array = false){
    $url = $this->base_url . 'index.php/cuestionario/';
    $this->db->select("id, blueprint_id, user_email, form_key, is_over, CONCAT('{$url}', form_key) AS url", false);
    $q = $this->db->get_where(self::TABLE, ['blueprint_id' => $blueprint_id]);
    if($as_array){
      return $q->result_array();
    }
    else{
      return $q->result();
    }
  }

  function email_list($blueprint_id){
    $this->db->select("user_email");
    $this->db->where('blueprint_id', $blueprint_id);
    $this->db->where('user_email !=', '');
    $q = $this->db->get(self::TABLE);
    return $q->result_array();
  }


  function count_all($blueprint_id){
    $this->db->from(self::TABLE);
    $this->db->where(['blueprint_id' => $blueprint_id]);
    return $this->db->count_all_results();
  }

  function delete_from_blueprint($blueprint_id){
    $this->db->delete(self::TABLE, ['blueprint_id' => $blueprint_id]); 
  }
}