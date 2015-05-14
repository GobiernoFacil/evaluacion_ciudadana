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
  static $csv_path;

  //
  // [ THE CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    $this->user = $this->session->userdata('user');
    $this->login_library->can_access(self::MIN_LEVEL);
    $this->load->helper('file');
    $this->csv_path = __DIR__ . '/../../../html/csv/';
    date_default_timezone_set('America/Mexico_City');
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

    $bp  = $this->session->userdata('blueprint');
    $csv = $bp->csv_file ? get_file_info($this->csv_path . $bp->csv_file) : false;
    $data = [];
    $data['title']       = 'Editar encuesta Tú Evalúas';
    $data['description'] = '';
    $data['body_class']  = 'surveys';
    $data['user']        = $this->user;

    $data['blueprint'] = $this->session->userdata('blueprint');
    $data['questions'] = $this->question_model->get($data['blueprint']->id);
    $data['rules']     = $this->rules_model->get($data['blueprint']->id);
    $data['options']   = $this->question_options_model->get($data['blueprint']->id);
    $data['csv_file']  = $csv;
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/surveys_app_view', $data);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  //
  // [ DELETE SURVEY ]
  //
  //
  public function delete($id){
    $is_admin = $this->user->level >= self::ADMIN_LEVEL;
    $user = $is_admin ? false : $this->user->id;
    $blueprint = $this->blueprint_model->get((int)$id, $user);
    if(! empty($blueprint)){
      $this->blueprint_model->soft_delete((int)$id);
    }
    redirect('bienvenido/encuestas', 'refresh');
  }

  //
  // [ UDPATE TITLE ]
  //
  //
  public function update_blueprint(){
    $blueprint = json_decode(file_get_contents('php://input'), true);
    $title     = $this->sanitize_string($blueprint['title']);
    $bp        = $this->session->userdata('blueprint');
    $is_closed = (int)$blueprint['is_closed'];
    $is_public = (int)$blueprint['is_public'];
    $bp_obj    = [
      'title'     => $title, 
      'is_closed' => $is_closed, 
      'is_public' => $is_public
    ];
    $blueprint['success'] = $this->blueprint_model->update($bp->id, $bp_obj);

    header('Content-type: application/json');
    echo json_encode($bp_obj);
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
  * THE CSV
  * -----------------------------------------------------
  *
  */

  //
  //
  //
  //
  public function make_csv(){
    $bp = $this->session->userdata('blueprint');
    $filename = false;

    if($bp){
      $id       = $bp->id;
      $filename = uniqid("cuestionario-{$id}-v-") . '.csv';
      $path     = __DIR__ . '/../../../html/';
      $index    = $path . 'index.php';
      $new_file = $path . 'csv/' . $filename;

      exec("php {$index} wackyland/make_csv index {$id} > {$new_file} &");

      $this->blueprint_model->update($id, ['csv_file' => $filename]);
    }

    header('Content-type: application/json');
    echo json_encode(['file' => $filename, 'full_path' => "/csv/{$filename}"]);
  }

  //
  //
  //
  //
  public function csv_is_avaliable($file = false){
    $filename = $this->sanitize_string($file);
    $file     = __DIR__ . '/../../../html/csv/' . $filename;

    $response = get_file_info($file);

    header('Content-type: application/json');
    echo json_encode(['file' => $response]);
  }

  //
  //
  //
  //
  public function upload_my_results(){
    $success = false;
    $name    = null;
    $upload  = reset($_FILES);
    if(! empty($upload) || ! $upload['error']){
      $bp        = $this->session->userdata('blueprint');
      $extension = pathinfo($upload['name'], PATHINFO_EXTENSION);
      $name = uniqid('resultados') . (empty($extension) ? '' : '.' . $extension);
      // $this->csv_path
      if(move_uploaded_file($upload['tmp_name'], $this->csv_path . $name)){
        $success = $this->blueprint_model->update($bp->id, ['csv_file' => $name]);
      }
    }

    header('Content-type: application/json');
    echo json_encode(['success' => $success, 'name' => $name]);
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