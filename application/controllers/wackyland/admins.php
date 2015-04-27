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
  const MIN_LEVEL     = 3;
  const CREATE_LEVEL  = 5;
  const PASSWORD_MIN  = 8;
  const DEFAULT_LEVEL = 1;

  //
  // [ CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    $this->load->library('email');
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
    if($this->user->level < self::CREATE_LEVEL){
      redirect('bienvenido/usuarios/' . $this->user->id,  'refresh');
    }

    $report = false;
    if(!empty($_POST)){
      $report = $this->create();
    }
    $admins = $this->admins_model->all();
    
    $data['title']       = 'Admins Tú Evalúas';
    $data['description'] = '';
    $data['body_class']  = 'users';
    $data['user']        = $this->user;
    $data['report']      = $report;
    $data['admins']      = $admins;
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/admins_view', $data);
    $this->load->view('wackyland/templates/footer_view');	  
  }

  //
  // [ NEW USER ]
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
      $this->send_welcome_email($email);
      return ['success' => true, 'user' => ['id' => $user_id, 'email' => $email, 'level'=>$level]];
    }
  }

  //
  // [ THE WELCOME EMAIL ] <-- out of service -->
  //
  //
  private function send_welcome_email($email){
    $this->email->from('welcome.robot@tuevaluas.com.mx', 'un robot de tú evalúas');
    $this->email->to($email); 

    $this->email->subject('Bienvenido a tú evalúas!');
    $this->email->message('Hola, bienvenido a tú evaluas, puedes acceder al 
      admin desde este link: http://tuevaluas.com.mx/bienvenido');  

    $this->email->send();
  }

  //
  // [ UPDATE USER ]
  //
  //
  public function update($id = false){
    $id = $id ? (int)$id : $this->user->id;

    if($id != $this->user->id && self::CREATE_LEVEL > $this->session->userdata('user')->level){
      $id = $this->user->id;
    }

    $report = false;

    if(!empty($_POST)){
      $report = $this->update_user($id);
    }
    $data = [];
    $data['user'] = $this->admins_model->get($id);
    $data['report'] = $report;

    $this->load->view('wackyland/update_admin_view', $data);
  }

  //
  // UPDATE USER
  //
  //
  private function update_user($id){
    $success = false;
    // [1] limpia el nivel y revisa que no llegue con algún valor raro
    $level    = isset($_POST['level']) ? (int)$_POST['level'] : $this->user->level;
    $level    = $level < 6 ? $level : self::DEFAULT_LEVEL;

    // [2] limpia  el password y revisa que tenga por lo menos ocho caracteres
    $password = filter_input(INPUT_POST, 'password');
    $pass_len = mb_strlen($password) >= self::PASSWORD_MIN;

    // [3] limpia la clave para mailgun
    $mailgun  = filter_input(INPUT_POST, 'mailgun');

    // [4] valida el password. Es en el único lugar donde puede haber errores
    if( ! empty($password) && !$pass_len){
      return ['success'  => $success, 'password' => $password, 'pass_len' => $pass_len];
    }
    // [5] si pasa la validación, prepara los datos para el update
    else{
      // [5.1] inicia la variable del usuario
      $user = [];
      // [5.2] si es admin, puede cambiar el tipo de usuario
      if($this->user->level >= self::CREATE_LEVEL){
        $user['level'] = $level;
      }
      // [5.3] si el campo del password no está vacío, agrega el nuevo pass
      if(! empty($password)){
        $user['password'] = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
      }
      // [5.4] si no está vacío el campo de mailgun, también lo agrega
      if(! empty($mailgun)){
        $user['mailgun'] = $mailgun;
      }

      // [5.5] si hay algo que actualizar, pues lo actualiza
      if(!empty($user)){
        $success = $this->admins_model->update($id, $user);
      }

      // [5.6] quién sabe por qué regresa esto, pero por si las flys
      return ['success' => $success];
    }
  }

  //
  // [ DELETE USER ]
  //
  //
  public function delete($id){
    // [1] revisa que tenga el nivel de usuario necesario para
    //     eliminar otro administrador. Nomás por si las flys
    if(self::CREATE_LEVEL > $this->session->userdata('user')->level){
      redirect('administradores', 'refresh');
    }

    // revisa que exista y que no se elimine a sí mismo
    $user = $this->admins_model->get((int)$id);
    if( !empty($user) && (int)$user->id != (int)$this->user->id ){
      $this->admins_model->delete($user->id);
      redirect('administradores', 'refresh');
    }
    else{
      redirect('administradores', 'refresh');
    }
  }
}