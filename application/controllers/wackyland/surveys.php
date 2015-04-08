<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surveys extends CI_Controller {

  const MIN_LEVEL     = 3;
  const CREATE_LEVEL  = 3;

  function __construct(){
    parent::__construct();
    $this->user = $this->session->userdata('user');
    if(! $this->user || self::MIN_LEVEL > $this->user->level){
      redirect('wackyland/login', 'refresh');
    }   
  }

  public function index(){
    $surveys = $this->blueprint_model->all();
    $this->load->view('wackyland/surveys_view', ['surveys' => $surveys]);
  }

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

  public function update($id){
    $this->load->view('wackyland/surveys_app_view');
  }

  public function delete($id){

  }
}