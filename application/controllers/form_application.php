<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* FORM APPLICATION
* -------------------------------------------------------
* Esta librería se encarga de ejecturar el cuestionario.
* Si el cuestionario no existe, debería redireccionar al
* homepage o a los datos recabados.
*
*/
class Form_application extends CI_Controller {

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
  * Construye el cuestionario. Debe recibir una form key.
  * En los casos siguientes:
  * - no recibir form key
  * - recibir form key inexistente
  * - que el formulario esté finalizado
  * se redireccionará al home o a consultar los datos.
  *
  */
  public function index($form = FALSE){
    // evalúa el form key
    if($form){
      // busca en la DB que exista la form key
      $applicant = $this->applicants_model->get($form);
      $blueprint = empty($applicant) ? false : $this->blueprint_model->get($applicant->blueprint_id);
      // si el form key no es válida, no es pública o si ya fue cerrada, pelas.
      if( empty($blueprint) || ! (bool)$blueprint->is_public || (bool)$blueprint->is_closed){
        $this->handle_error($blueprint);
      }
      // entonces sí se puede
      else{
        $data = [];
        $data['applicant'] = $applicant;
        $data['blueprint'] = $blueprint;
        $data['questions'] = $this->question_model->get($blueprint->id);
        $data['options']   = $this->question_options_model->get($blueprint->id);
        $data['answers']   = $this->answers_model->get($applicant->form_key);
        $data['rules']     = $this->rules_model->get($blueprint->id);

        $this->load->view('form_application_view', $data);
      }
    }
    // entonces no se puede
    else{
      $this->handle_error(false);
    }
  }

  /**
  * HANDLE ERROR
  * -----------------------------------------------------
  * Hay tres posibles errores: 
  * 1. no existe la encuesta (para el form_key)
  * 2. la encuesta no es pública
  * 3. la encuesta ya se cerró
  * 4. sepa la bola*
  *
  */
  public function handle_error($blueprint){
    if(empty($blueprint)){
      die('aquí no hay ningún formulario :/');
    }
    elseif($blueprint->is_public == '0'){
      die('la encuesta no está disponible O____O');
    }
    elseif($blueprint->is_closed == '1'){
      redirect('resultados/' . $blueprint->id);
      // die('la encuesta ya se cerró :P');
    }
    else{
      die('x_______x');
    }
  }
}

/* End of form_application.php */