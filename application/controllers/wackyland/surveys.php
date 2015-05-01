<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* SURVEYS
* -------------------------------------------------------
* Este controller es el CRUD para los formularios.
*
*/

// use Ezyang\HTMLPurifier;
require_once __DIR__ . '/../../../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';

class Surveys extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  *
  */

  //
  // [ THE SETTINGS ]
  //
  //
  const MIN_LEVEL     = 1;
  const CREATE_LEVEL  = 3;
  const ADMIN_LEVEL   = 5;

  //
  // [ THE CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    $this->user = $this->session->userdata('user');
    $this->login_library->can_access(self::MIN_LEVEL);  
  }

  /**
  * THE SURVEY
  * -----------------------------------------------------
  *
  */

  //
  // [ THE SURVEY LIST ] [ VIEW ]
  //
  //
  public function index(){
    $data['title']       = 'Encuestas Tú Evalúas';
    $data['description'] = '';
    $data['body_class']  = 'surveys';

    $data['surveys']     = $this->user->level >= 5 ? $this->blueprint_model->all() : $this->blueprint_model->all_from($this->user->id);
    $data['user']        = $this->user;

    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/surveys_view', $data);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  //
  // [ THE SURVEY CREATOR ] [ POST ]
  //
  //
  public function create(){
    // [1] revisa que tenga el nivel de usuario necesario para
    //     crear un cuestionario.
    $this->login_library->can_access(self::CREATE_LEVEL, 'wackyland/surveys');  

    // [2] si la información POST existe, se crea un nuevo formulario,
    //     y redirecciona a editarlo
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    if($title){
      $blueprint = [
        'title'     => $title, 
        'creator'   => $this->user->id, 
        'is_public' => 0, 
        'is_closed' => 0
      ];

      $new_id    = $this->blueprint_model->add($blueprint);
      if($new_id){
        redirect('wackyland/surveys/update/' . $new_id);
      }
      else{
        die('something wrong x_____x');
      }
    // [3] si no existe la información POST, envía a la lisa de encuestas
    }else{
      redirect('wackyland/surveys', 'refresh');
    }
  }

  //
  // [ THE SURVEY EDITOR ] [ VIEW ]
  //
  //
  public function update($id){
    // [ GET THE BLUEPRINT ]
    // guarda en la sesión el fomrulario a editar, para evitar que se modifiquen 
    // otros fomularios
    $is_admin = $this->user->level >= self::ADMIN_LEVEL;
    $this->login_library->set_blueprint((int)$id, $is_admin);

    $data = [];
    $data['title']       = 'Editar encuesta Tú Evalúas';
    $data['description'] = '';
    $data['body_class']  = 'surveys';
    $data['user']        = $this->user;

    $data['blueprint'] = $this->session->userdata('blueprint');
    $data['questions'] = $this->question_model->get($data['blueprint']->id);
    $data['rules']     = $this->rules_model->get($data['blueprint']->id);
    $data['options']   = $this->question_options_model->get($data['blueprint']->id);
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/surveys_app_view', $data);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  //
  // [ DELETE SURVEY ]
  //
  //
  public function delete($id){

  }

  //
  // [ UDPATE TITLE ]
  //
  //
  public function update_title(){
    $blueprint = json_decode(file_get_contents('php://input'), true);
    $title     = $this->sanitize_string($blueprint['title']);
    
    $blueprint['success'] = $this->update_blueprint->update(['title' => $title]);
    $blueprint['title']   = $title;

    header('Content-type: application/json');
    echo json_encode($blueprint);
  }

  public function set_public(){
    // is_public
  }

  public function set_tatus(){
    // is_closed
  }

  //
  //
  //
  //
  private function update_blueprint($blueprint){
    $bp = $this->session->userdata('blueprint');
    return $this->blueprint_model->update($bp->id, $blueprint);
  }

  /**
  * THE QUESTIONS
  * -----------------------------------------------------
  *
  */

  //
  //
  //
  //
  public function add_question(){
    $bp        = $this->session->userdata('blueprint');
    $response  = json_decode(file_get_contents('php://input'), true);

    $question_obj = [
      'blueprint_id'   =>  $bp->id,
      'section_id'     => (string)((int)$response['section_id']),
      'question'       => $this->sanitize_string($response['question']),
      'is_description' => (bool)$response['is_description'],
      'type'           => $response['type'] == 'number' ? 'number' : 'text',
      'is_location'    => (int)$response['is_location']
    ];

    // weird hack
    if($question_obj['is_description']){
      $config     = HTMLPurifier_Config::createDefault();
      $purifier   = new HTMLPurifier($config);
      $clean_html = $purifier->purify($response['question']);
      $question_obj['question'] = $clean_html;
    }

    $new_id    = $this->question_model->add($question_obj);
    $question_obj['id'] = (string)$new_id;
    $question_obj['options'] = $this->add_options($response['options'], $question_obj);

    header('Content-type: application/json');
    echo json_encode($question_obj);
  }

  //
  // [ UPDATE / DELETE QUESTION ]
  //
  //
  public function update_question($id = 0){
    $bp     = $this->session->userdata('blueprint');
    $action = $_SERVER['REQUEST_METHOD'];

    // [ UPDATE]
    if($action == "PUT"){
      $response = false;
      
      $bp        = $this->session->userdata('blueprint');
      $response  = json_decode(file_get_contents('php://input'), true);
      $question  = $this->question_model->validate((int)$id, $bp->id);

      $question_obj = [
        'blueprint_id'   =>  $bp->id,
        'section_id'     => (string)((int)$response['section_id']),
        'question'       => $this->sanitize_string($response['question']),
        'is_description' => (bool)$response['is_description'],
        'type'           => $response['type'] == 'number' ? 'number' : 'text',
        'is_location'    => (int)$response['is_location']
      ];

      // weird hack
      if($question_obj['is_description']){
        $config     = HTMLPurifier_Config::createDefault();
        $purifier   = new HTMLPurifier($config);
        $clean_html = $purifier->purify($response['question']);
        $question_obj['question'] = $clean_html;
      }

      if($question){
        // update question
        $success = $this->question_model->update($question->id, $question_obj);
        // update options
        $question_obj['id']      = $question->id;
        $options = $this->question_options_model->delete_group($bp->id, $id);
        $question_obj['options'] = $this->add_options($response['options'], $question_obj);
      }
      
      header('Content-type: application/json');
      echo json_encode($question_obj);
    }

    // [ DELETE ]
    else if($action == "DELETE"){
      // delete options
      $options = $this->question_options_model->delete_group($bp->id, (int)$id);
      // delete question
      $question = $this->question_model->delete($bp->id, (int)$id);
      // json response
      header('Content-type: application/json');
      echo json_encode(['options' => $options, 'question' => $question]);
    }
    else{
      // return 404
      show_404();
    }
  }

  /**
  * THE OPTIONS
  * -----------------------------------------------------
  *
  */

  //
  // [ ADD OPTIONS TO A QUESTION ]
  //
  //
  private function add_options($options, $question){
    $response = [];
    if(!empty($options)){
      $value = 1;
      foreach($options as $option){
        $option_obj = [
          'blueprint_id' =>  $question['blueprint_id'],
          'question_id'  =>  $question['id'],
          'description'  => $this->sanitize_string($option),
          'value'        => $value,
          'name'         => uniqid('opt')
        ];
        $new_id = $this->question_options_model->add($option_obj);
        $option_obj['id'] = $new_id;
        $response[] = $option_obj;
        $value++;
      }
    }

    return $response;
  }



  /**
  * THE RULES
  * -----------------------------------------------------
  *
  */

  //
  // [ ADD RULE ]
  //
  //
  public function add_rule(){
    $bp        = $this->session->userdata('blueprint');
    $response  = json_decode(file_get_contents('php://input'), true);

    $rule_obj = [
      'blueprint_id' =>  $bp->id,
      'section_id'   => (int)$response['section_id'],
      'question_id'  => (int)$response['question_id'],
      'value'        => (int)$response['value'],
    ];

    $new_id    = $this->rules_model->add($rule_obj);
    $rule_obj['id'] = (string)$new_id;
    $rule_obj['section_id'] = (string)$rule_obj['section_id'];

    header('Content-type: application/json');
    echo json_encode($rule_obj);
  }

  //
  // [ DELETE RULE ]
  //
  //
  public function delete_rule($id){
    $bp      = $this->session->userdata('blueprint');
    $success = $this->rules_model->delete($bp->id, (int)$id);
    header('Content-type: application/json');
    echo json_encode(['success' => $success]);
  }

  /**
  * THE PRIVATE FUNCTIONS
  * -----------------------------------------------------
  *
  */

  //
  // [ SANITAZE STRING ]
  //
  //
  private function sanitize_string($string){
    return filter_var($string,FILTER_SANITIZE_STRING);
  }
}