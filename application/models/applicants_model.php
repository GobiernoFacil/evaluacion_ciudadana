<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Question model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Applicants_model extends CI_Model{

  const TABLE   = 'applicants';
  const MAILGUN = 'mailgun';
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

  function all($blueprint_id, $as_array = false, $only_valid_email = false){
    $url = $this->base_url . 'index.php/cuestionario/';
    $this->db->select("id, blueprint_id, user_email, form_key, is_over, CONCAT('{$url}', form_key) AS url", false);
    if($only_valid_email){
      $this->db->where('user_email !=', '');
      $this->db->group_by("user_email"); 
    }
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

  function register_mailgun_batch($blueprint_id){
    $mailgun = ['blueprint_id' => $blueprint_id, 'completed' => 0];
    $this->db->insert(self::MAILGUN, $mailgun);
    return $this->db->insert_id();
  }

  function close_mailgun_batch($batch_id){
    $this->db->update(self::MAILGUN, ['completed' => 1], ['id' => $batch_id]);
    return $this->db->affected_rows();
  }

  function batch_in_progress($blueprint_id){
    $this->db->from(self::MAILGUN);
    $this->db->where(['blueprint_id' => $blueprint_id, 'completed' => 0]);
    return $this->db->count_all_results();
  }
}