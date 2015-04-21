<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* OPEN DATA
* -------------------------------------------------------
* Aquí se pueden descargar los datos de cualquier formulario,
* ya sea en archivo, en json o visualizarse como gráfica
*
*/
use League\Csv\Writer;

class Open_data extends CI_Controller {

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
	if  ( $form_id == false) 
	{
		
		$blueprints				=  $this->open_data_model->get_blueprints();
		
		$data['title'] 			= 'Resultados de las encuestas Tú Evalúas';
		$data['description'] 	= 'Resultados de las encuestas Tú Evalúas';
		$data['body_class'] 	= 'data';
		$data['response'] 				= $blueprints;
		
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
    elseif($format == 'csv'){
      $writer = Writer::createFromFileObject(new SplTempFileObject()); //the CSV file will be created into a temporary File
      $writer->setDelimiter(","); //the delimiter will be the tab character
      $writer->setNewline("\r\n"); //use windows line endings for compatibility with some csv libraries
      $writer->setEncodingFrom("utf-8");
      $headers = ["pregunta" , "respuesta", "numero"];
      $writer->insertOne($headers);

      $datos = [];
      foreach ($r['questions'] as $question){
        $entry = [];
        $question_text = html_entity_decode($question->question);
        foreach ($question->options as $option){
          $option_text = html_entity_decode($option->description);
          $option_num  = $option->answer_num;
          $datos[] = [$question_text, $option_text, $option_num];
        }
      }

      $writer->insertAll($datos);
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=file.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $writer;
    }
    

    /*
    ***
    ***
    */
    elseif($format == 'fullcsv'){
      // get the base data
      $questions  = $this->question_model->get($blueprint_id, false, true);
      $options    = $this->question_options_model->get($blueprint_id);
      $applicants = array_column($this->answers_model->get_applicant_list($blueprint_id), 'form_key');
      $first_row = [];

      $answers = $this->answers_model->get($applicants[0], true);
      $answers_id = array_column($answers, 'question_id');
    
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
        $first_row[] = $res;
      }
      var_dump($first_row);
      die();

      $writer = Writer::createFromFileObject(new SplTempFileObject()); //the CSV file will be created into a temporary File
      $writer->setDelimiter(","); //the delimiter will be the tab character
      $writer->setNewline("\r\n"); //use windows line endings for compatibility with some csv libraries
      $writer->setEncodingFrom("utf-8");
      $headers = $this->get_csv_header($questions);
      $writer->insertOne($headers);

      $datos = [];
      foreach ($r['questions'] as $question){
        $entry = [];
        $question_text = html_entity_decode($question->question);
        foreach ($question->options as $option){
          $option_text = html_entity_decode($option->description);
          $option_num  = $option->answer_num;
          $datos[] = [$question_text, $option_text, $option_num];
        }
      }

      $writer->insertAll($datos);
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=file.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $writer;
    }
    /*
    ***
    ***
    */
	 	
    elseif($format == 'archivo'){
	 	  $filename = "data.json";
	 	  header("Content-type: application/octet-stream");
	 	  header("Content-disposition: attachment;filename=$filename");
	 	  echo json_encode($r);
	 	}
	 	else{
	 	  
	 	
	 	$data['response'] 				= $r;
  
	 	  
	 	$data['title'] 			 = 'Resultados de las encuestas Tú Evalúas';
		$data['description'] 	= 'Resultados de las encuestas Tú Evalúas';
		$data['body_class'] 	= 'data';
		$this->load->view('/templates/header_view', $data);
		$this->load->view('/data/singledata_viz_view');
		$this->load->view('/templates/footer_view');
	 	}
	 	
	 	
	 }	
	 	
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

  //
  // [ CSV ROW CONTRUCTOR ]
  //
  //
  private function get_csv_rows($blueprint_id, $questions, $options){
    $answers = $this->answers_model->get_by_blueprint($blueprint_id);

    foreach ($answer as $key => $value) {
      # code...
    }
  }



}