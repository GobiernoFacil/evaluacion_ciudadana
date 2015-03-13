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

  public function get($blueprint_id){
    // [ THE JSON COMPONENTS ]
    // Esta función regresa:
    // + el número de encuestas
    // + la lista de preguntas
    // + la lista de opciones numeradas
    // - FALSE si la encuesta no existe
    $blueprint = $this->_get_blueprint($blueprint_id);
    // [1] revisa que exista el blueprint
    if(empty($blueprint)) return false;

    // [2] si existe el blueprint, regresa un arreglo
    //     con todos los elementos que generan los datos
    $response = [
      'blueprint'  => $blueprint,
      'applicants' => $this->_get_applicants_num($blueprint_id),
      'questions'  => $this->_get_questions($blueprint_id),
      'options'    => $this->_get_options($blueprint_id),
      'answers'    => $this->_get_answers($blueprint_id)
    ];
    return $response;
  }

  public function get_blueprints(){
    $pb = $this->db->get(self::BLUEPRINTS);
    return $bp->result();
  }

  private function _get_blueprint($id){
    $this->db->select('id, title, creation_date');
    $pb = $this->db->get_where(self::BLUEPRINTS, ['id' => $id]);
    return $pb->row();
  }

  

  private function _get_applicants_num($id){
    $this->db->from(self::APPLICANTS);
    $this->db->where('blueprint_id', $id);
    return $this->db->count_all_results();
  }

  private function _get_questions($id){
    $this->db->select('id, question');
    $qs = $this->db->get_where(self::QUESTIONS, [
      'blueprint_id' => $id, 
      'is_description' => 0, 
      'type' => 'number'
      ]);
    return $qs->result();
  }

  private function _get_options($id){
    $this->db->select('id, question_id, description, value');
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