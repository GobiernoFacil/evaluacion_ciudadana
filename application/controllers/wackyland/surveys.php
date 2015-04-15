<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surveys extends CI_Controller {

  //
  // [ THE SETTINGS ]
  //
  //
  const MIN_LEVEL     = 3;
  const CREATE_LEVEL  = 3;
  const ADMIN_LEVEL   = 5;

  //
  // [ THE CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    $this->user = $this->session->userdata('user');
    if(! $this->user || self::MIN_LEVEL > $this->user->level){
      redirect('wackyland/login', 'refresh');
    }   
  }

  //
  // [ THE SURVEY LIST ]
  //
  //
  public function index(){
	 $data['title'] 			= 'Encuestas Tú Evalúas';
	 $data['description'] 		= '';
	  
    $surveys = $this->blueprint_model->all();
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/surveys_view', ['surveys' => $surveys]);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  //
  // [ THE SURVEY CREATOR ]
  //
  //
  public function create(){
    // [1] revisa que tenga el nivel de usuario necesario para
    //     crear un cuestionario.
    if(self::CREATE_LEVEL > $this->session->userdata('user')->level){
      redirect('wackyland/surveys', 'refresh');
    }

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_ENCODE_HIGH);
    if($title){
      $blueprint = ['title' => $title, 'creator' => $this->user->id];
      $new_id    = $this->blueprint_model->add($blueprint);
      if($new_id){
        redirect('wackyland/surveys/update/' . $new_id);
      }
      else{
        die('something wrong x_____x');
      }
    }else{
      redirect('wackyland/surveys', 'refresh');
    }
  }

  //
  // [ THE SURVEY EDITOR ]
  //
  //
  public function update($id){
    // [ GET THE BLUEPRINT ]
    // [1] limpia los datos
    $id = (int)$id;
    $creator = $this->user->level < self::ADMIN_LEVEL ? $this->user->id : false;
    // [2] busca el blueprint. Si es admin, puede ver cualquier cuestionario.
    //     Si no, solo los que le pertenecen.
    $blueprint = $this->blueprint_model->get($id, $creator);
    // [3] Si no encuentra el bluprint, lo redirecciona a la sección de cuestionarios
    if(! $blueprint){
      redirect('wackyland/surveys');
      die();
    }

    // [ PREPARE THE DATA ]
    $this->session->set_userdata('blueprint', $blueprint);
    $data = [];
    $data['title'] 				= 'Editar encuesta Tú Evalúas';
	 $data['description'] 		= '';
    $data['blueprint'] = $blueprint;
    $data['sections']  = $this->section_model->get($data['blueprint']->id);
    $data['questions'] = $this->question_model->get($data['blueprint']->id);
    // $data['rules']     = $this->rules_model->get($data['blueprint']->id);
    $data['options']   = $this->question_options_model->get($data['blueprint']->id);
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/surveys_app_view', $data);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  public function delete($id){

  }

  public function update_title(){
    $bp        = $this->session->userdata('blueprint');
    $blueprint = json_decode(file_get_contents('php://input'), true);
    $title     = $this->sanitize_string($blueprint['title']);
    $success   = $this->blueprint_model->update($bp->id, ['title' => $title]);
    $blueprint['success'] = $success;
    $blueprint['title']   = $title;
    header('Content-type: application/json');
    echo json_encode($blueprint);
  }

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

    $new_id    = $this->question_model->add($question_obj);
    $question_obj['id'] = (string)$new_id;
    $question_obj['options'] = $this->add_options($response['options'], $question_obj);

    header('Content-type: application/json');
    echo json_encode($question_obj);
  }

  public function update_question($id = 0){
    $bp     = $this->session->userdata('blueprint');
    $action = $_SERVER['REQUEST_METHOD'];
    
    if($action == "PUT"){
      // update question
      // update options
    }
    else if($action == "DELETE"){
      // delete options
      $options = $this->question_options_model->delete_group($bp->id, $id);
      // delete question
      $question = $this->question_model->delete($bp->id, $id);
      // json response
      header('Content-type: application/json');
      echo json_encode(['options' => $options, 'question' => $question]);
    }
    else{
      // return 404
      show_404();
    }
  }

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

  private function sanitize_string($string){
    return filter_var($string,FILTER_SANITIZE_STRING/*,FILTER_FLAG_STRIP_LOW|FILTER_FLAG_ENCODE_HIGH*/);
  }
}