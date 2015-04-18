<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| --------------------------------------------------------------------------------
| THE USERS PANEL
| --------------------------------------------------------------------------------
*/
class Admins extends CI_Controller {

  //
  // [ SETTINGS ]
  //
  //
  const MIN_LEVEL     = 5;
  const CREATE_LEVEL  = 5;
  const PASSWORD_MIN  = 8;
  const DEFAULT_LEVEL = 1;

  //
  // [ CONSTRUCTOR ]
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
  // [ USERS PANEL ]
  //
  //
  public function index(){
    $report = false;
    if(!empty($_POST)){
      $report = $this->create();
    }
    $admins = $this->admins_model->all();
    
    $data['title'] 			= 'Admins Tú Evalúas';
	$data['description'] 	= '';
	$data['body_class'] 	= 'users';
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/admins_view', ['admins' => $admins, 'report' => $report]);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  //
  // [ NEW ADMIN ]
  //
  //
  private function create(){
    // [1] revisa que tenga el nivel de usuario necesario para
    //     crear otro administrador. Nomás por si las flys
    
    if(self::CREATE_LEVEL > $this->session->userdata('user')->level){
      return false;
    }
    
    // [2] valida los datos
    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    $pass_len = mb_strlen($password) >= self::PASSWORD_MIN;
    $exist    = $email ? $this->admins_model->exist($email) : false;
    $level    = (int)$_POST['level'];
    $level    = $level < 6 ? $level : self::DEFAULT_LEVEL;
    // [3] si no pasa la validación, regresa un array con la validación
    if(!$email || !$password|| !$pass_len || $exist){
      return ['success'  => false, 
              'email'    => $email, 
              'password' => $password, 
              'exist'    => $exist, 
              'level'    => $level];
    }
    // [4] si pasa la validación, crea un usuario y lo regresa
    else{
      $hash    = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
      $user_id = $this->admins_model->add(['email' => $email, 'password' => $hash, 'level'=>$level]);
      return ['success' => true, 'user' => ['id' => $user_id, 'email' => $email, 'level'=>$level]];
    }
  }

  //
  // [ UPDATE ADMIN ]
  //
  //
  public function update($id = false){
    echo "n_____n";
  }

  //
  // [ DELETE ADMIN ]
  //
  //
  public function delete($id){

  }
}