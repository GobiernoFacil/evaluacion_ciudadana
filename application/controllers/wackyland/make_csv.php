<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* MAKE CSV
* -------------------------------------------------------
* Esto debería crear un CSV, a ver qué tal
*
*/
use League\Csv\Writer;

class Make_csv extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  * la configuración básica del controller
  *
  */
  function __construct(){
    parent::__construct();
    if (! ini_get("auto_detect_line_endings")){
      ini_set("auto_detect_line_endings", '1');
    }
  }

  /**
  * INDEX
  * -----------------------------------------------------
  * si no se uncluye un ID, debería mostrar una lista de
  * elementos disponibles. Si incluye un ID, el default es
  * la visualización; si incluye el formato, este puede ser
  * archivo o json. 
  *
  */
  public function index($form_id = false){
    $blueprint_id = (int)$form_id;
    $questions    = $this->question_model->get($blueprint_id, false, true);
    $options      = $this->question_options_model->get($blueprint_id);
    $applicants   = array_column($this->answers_model->get_applicant_list($blueprint_id), 'form_key');
  
    $writer = Writer::createFromFileObject(new SplTempFileObject()); //the CSV file will be created into a temporary File
    $writer->setDelimiter(","); //the delimiter will be the tab character
    $writer->setNewline("\r\n"); //use windows line endings for compatibility with some csv libraries
    $writer->setEncodingFrom("utf-8");
    $headers = $this->get_csv_header($questions);
    $writer->insertOne($headers);

    $datos = [];

    foreach($applicants as $applicant){
      $answers    = $this->answers_model->get($applicant, true);
      $answers_id = array_column($answers, 'question_id');

      $row = [];
      foreach($questions as $q){
        $q->options = array_filter($options, function($opt) use($q){
          return $opt->question_id == $q->id;
        });
        
        $q_id = array_search($q->id, $answers_id);
        $res  = $q->type == 'text' ? $answers[$q_id]['text_value'] : $answers[$q_id]['num_value'];
        if(! empty($q->options) && $q->type == 'number'){
          $res = array_filter($q->options, function($opt) use($res){
            return $opt->value == $res;
          });
          $res = empty($res) ? false : array_shift($res)->description;
        }
        
        $row[] = $res;
      }
      $datos[] = $row;
    }

    $writer->insertAll($datos);
    echo $writer . PHP_EOL;
  }

  //
  // [ HEADER CONTRUCTOR ]
  //
  //
  private function get_csv_header($questions){
    $columns = array_map(function($question){
      return $question->question;
    },$questions);

    return $columns;
  }
}