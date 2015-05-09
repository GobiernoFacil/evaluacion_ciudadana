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
  
  static $csv_path;

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
    $this->load->helper('file');
    $this->csv_path = __DIR__ . '/../../html/csv/';
    date_default_timezone_set('America/Mexico_City');
  }

  /**
  * INDEX
  * -----------------------------------------------------
  * si no se uncluye un ID, debería mostrar una lista de
  * elementos disponibles. Si incluye un ID, el default es
  * la visualización; se elimina la opción de distintos formatos.
  * instrucciones gobies, que poruqe a la verga vergas.
  *
  */
  public function index($form_id = false){
    // [A] se muestra una sola encuesta
    if($form_id){
      // [1] busca el blueprint, y si no lo encuentra, redirecciona a mostrar
      //     todas las encuestas
		  $blueprint_id = (int)$form_id;
      $response     = $this->open_data_model->get($blueprint_id);
      if(empty($response)) redirect('resultados', 'refresh');

      // [2] prepara el resultado agregando las respuestas por pregunta
      foreach($response['questions'] as $question){
        // [2.1] le asigna a cada pregunta sus opciones
        $question->options = array_filter($response['options'], function($option) use($question){
          return $option->question_id == $question->id;
        });
        // [2.2] si la pregunta no tiene opciones, los valores de las
        //       respuestas se pasan directamente
        if(empty($question->options)){
          $question->answers = array_filter($response['answers'], function($answer) use($question){
            return $answer->question_id == $question->id;
          });
        }
        // [2.3] si la pregunta tiene opciones, se cuentan las coincidencias por opción
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

      // [3] revisa si está dispoinble el CSV
      $csv  = $response['blueprint']->csv_file;
      $path = $csv ? $this->csv_path . $csv : false;
      $file = $path ? get_file_info($path) : false;
      $url  = $file && $file['size'] ? "/csv/{$csv}" : false;

      // [4] se agrupan los resultados para el view
      $r = [
        'survey'     => $response['blueprint'],
        'applicants' => $response['applicants'],
        'questions'  => $response['questions']
      ];

      $data['response']    = $r;
      $data['file']        = $url;
      $data['title']       = 'Resultados de las encuestas Tú Evalúas';
      $data['description'] = 'Resultados de las encuestas Tú Evalúas';
      $data['body_class']  = 'data';
      $this->load->view('/templates/header_view', $data);
      $this->load->view('/data/singledata_viz_view', $data);
      $this->load->view('/templates/footer_view');
	  }
    // [B] Se muestran todas las encuestas
    else{
      $blueprints          =  $this->open_data_model->get_blueprints();
      $data['title']       = 'Resultados de las encuestas Tú Evalúas';
      $data['description'] = 'Resultados de las encuestas Tú Evalúas';
      $data['body_class']  = 'data';
      $data['response']    = $blueprints;
    
      $this->load->view('/templates/header_view', $data);
      $this->load->view('/data/data_view');
      $this->load->view('/templates/footer_view');
    }
  }
}