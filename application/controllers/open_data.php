<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* OPEN DATA
* -------------------------------------------------------
* Aquí se pueden descargar los datos de cualquier formulario,
* ya sea en archivo, en json o visualizarse como gráfica
*
*/
class Open_data extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  * la configuración básica del controller
  *
  */
  function __construct(){
    parent::__construct();
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
  public function index($form_id = false, $format = false){
    $blueprint_id = (int)$form_id;

    $response = $this->open_data_model->get($blueprint_id);

    foreach($response['questions'] as $question){
      $question->options = array_filter($response['options'], function($option) use($question){
        return $option->question_id == $question->id;
      });

      $question->answers = array_filter($response['answers'], function($answer) use($question){
        return $answer->question_id == $question->id;
      });
    }

    $r = [
      'survey'     => $response['blueprint'],
      'applicants' => $response['applicants'],
      'questions'  => $response['questions']
    ];

    if($format == 'json'){
      header('Content-Type: application/json');
      echo json_encode($r);
    }
    elseif($format == 'archivo'){
      $filename = "data.json";
      header("Content-type: application/octet-stream");
      header("Content-disposition: attachment;filename=$filename");
      echo json_encode($r);
    }
    else{
      echo "<pre>";
      var_dump($r);
      echo "</pre>";
    }
  }
}