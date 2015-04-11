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
    $surveys = $this->blueprint_model->all();
    $this->load->view('wackyland/surveys_view', ['surveys' => $surveys]);
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
    $this->session->userdata('blueprint', $blueprint);
    $data = [];
    $data['blueprint'] = $blueprint;
    $data['sections']  = $this->section_model->get($data['blueprint']->id);
    $data['questions'] = $this->question_model->get($data['blueprint']->id);
    $data['options']   = $this->question_options_model->get($data['blueprint']->id);

    $this->load->view('wackyland/surveys_app_view', $data);
  }

  public function delete($id){

  }
}