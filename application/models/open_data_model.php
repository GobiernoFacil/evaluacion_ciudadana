<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Open data model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Open_data_model extends CI_Model{

  const ANSWERS    = 'answers';
  const APPLICANTS = 'applicants';
  const BLUEPRINTS = 'blueprints';
  const OPTIONS    = 'options';
  const QUESTIONS  = 'questions';
  
  function __construct(){
    parent::__construct();
  }

  function get($form_id){
    // [ THE JSON COMPONENTS ]
    // Esta función regresa:
    // + el número de encuestas
    // + la lista de preguntas
    // + la lista de opciones numeradas
    // - FALSE si la encuesta no existe
  }

  private function _get_blueprint($id){
    $pb = $this->db->get_where(self::BLUEPRINTS, ['id' => $id]);
    return $bp->row();
  }

  private function _get_applicants_num($id){
    $this->db->from(self::APPLICANTS);
    $this->db->where('blueprint_id', $id);
    return $this->db->count_all_results();
  }

  private function _get_questions($id){
    $qs = $this->db->get_where(self::QUESTIONS, ['blueprint_id' => $id]);
    return $qs->result();
  }

  private function _get_options($id){
    $op = $this->db->get_where(self::OPTIONS, ['blueprint_id' => $id]);
    return $op->result();
  }

  private function _get_answers($id){
    $query = "SELECT id, question_id, num_value, COUNT(*) AS total 
    FROM answers WHERE num_value IS NOT NULL AND blueprint_id = {$id}
    GROUP BY question_id, num_value";

    $q = $this->db->query($query);

    return $q->result();
  }


}