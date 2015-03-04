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

      // si el form key no es válida o ya fue aplicada/cerrada
      if(empty($applicant) || (int)$applicant->is_over){
        $this->handle_error();
      }

      // si el form key es válido
      else{
        $data = [];
        $data['applicant'] = $applicant;
        $data['blueprint'] = $this->blueprint_model->get($applicant->blueprint_id);
        $data['questions'] = $this->question_model->get($data['blueprint']->id);
        $data['options']   = $this->question_options_model->get($data['blueprint']->id);

        $this->load->view('form_application_view', $data);
      }
    }

    // no se recibió un form key
    else{
      $this->handle_error();
    }
  }

  public function handle_error(){
    die('aquí no hay ningún formulario :/');
  }
}

/* End of file test.php */