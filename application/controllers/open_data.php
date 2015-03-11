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
	//$this->output->enable_profiler(TRUE);
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
	if  ( $form_id == false) {
		$data['title'] 			= 'Resultados de las encuestas Tú Evalúas';
		$data['description'] 	= 'Resultados de las encuestas Tú Evalúas';
		$data['body_class'] 	= 'data';
		
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/data/data_view');
		$this->load->view('/templates/footer_view');
	}  
  	
  	else {
	 	$blueprint_id = (int)$form_id;
	 		
	 	$response = $this->open_data_model->get($blueprint_id);
	 		
	 	foreach($response['questions'] as $question){
	 	  $question->options = array_filter($response['options'], function($option) use($question){
	 		  return $option->question_id == $question->id;
	 	  });

      if(empty($question->options)){
        $question->answers = array_filter($response['answers'], function($answer) use($question){
          return $answer->question_id == $question->id;
        });
      }
      else{
        $question->answers = false;
        foreach($question->options as $option){
          $option->answer = array_filter($response['answers'], function($answer) use($question, $option){
            return $answer->question_id == $question->id && $answer->num_value == $option->value;
          });

          $option->answer_num = empty($option->answer) ? 0 : array_pop($option->answer)->total;
        }
      }
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
	 	  
	 	
	 	$data['response'] 				= $r;
  
	 	  
	 	$data['title'] 			= 'Resultados de las encuestas Tú Evalúas';
		$data['description'] 	= 'Resultados de las encuestas Tú Evalúas';
		$data['body_class'] 	= 'data';
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/data/singledata_viz_view');
		$this->load->view('/templates/footer_view');
	 	}
	 	
	 	
	 }	
	 	
  }
}