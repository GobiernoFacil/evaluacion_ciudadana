<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* SURVEY HANDLER
* -------------------------------------------------------
* Este controller se encarga de guardar las respuestas de cada encuesta conforme
* se van aplicando, en "tiempo real"
*
*/
class Survey_handler extends CI_Controller {

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
  * Obtiene mediante JSON una respuesta, el id de la pregunta y el id 
  * de la encuesta. 
  *
  */
  public function index(){
    $data   = $this->input->post(NULL, TRUE);
    $survey = $this->get_survey_items($data);

    if($survey){
      $answer = [
        'blueprint_id' => $survey['applicant']->blueprint_id,
        'form_key'     => $survey['applicant']->form_key,
        'question_id'  => $survey['question']->id,
        'text_value'   => $survey['question']->type == 'text'   ? $data['question_value'] : NULL,
        'num_value'    => $survey['question']->type == 'number' ? (int)$data['question_value'] : NULL,
      ];

      $question = $this->answers_model->save($answer);

      $this->output->set_content_type('application/json');
      $this->output->set_output(json_encode($data['question_value']));
    }
    else{
      $this->handle_error();
    }
  }

  /**
  * HANDLE ERROR
  * -----------------------------------------------------
  * Genera una respuesta de error
  *
  */
  private function handle_error(){
    $this->output->set_status_header('400');
    $this->output->set_content_type('application/json');
    $this->output->set_output(json_encode(FALSE));
  }


  /**
  * GET SURVEY ITEMS
  * -----------------------------------------------------
  * Valida la información que se recibe y regresa el formulario y
  * la pregunta de la DB
  *
  */
  private function get_survey_items($data){
    // revisa que la información esté completa
    if(empty($data['question_value']) || empty($data['form_key']) || empty($data['question_id'])){
      return FALSE;
    }
    // revisa que el formulario exista y esté vigente
    $applicant = $this->applicants_model->get($data['form_key']);
    if(empty($applicant) || (int)$applicant->is_over){
      return FALSE;
    }

    // revisa que la pregunta exista
    $question = $this->question_model->validate($data['question_id'], $applicant->blueprint_id);
    if(empty($question)){
      return FALSE;
    }

    // regresa el formulario y la pregunta
    return ['applicant' => $applicant, 'question' => $question];
  }
}