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
  const MIN_LEVEL     = 3; // debe ser funcionario para entrar aquí
  const CREATE_LEVEL  = 5; // para crear usuarios, debe ser admin
  const PASSWORD_MIN  = 8; // mínimo de caracteres para los passwords
  const DEFAULT_LEVEL = 1; // si el nivel no está definido, se va a la goma

  //
  // [ CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    // referencia a la información de login del usuario
    $this->user = $this->session->userdata('user');
    // revisa que el usuario esté logeado y tenga el 
    // nivel necesario para acceder a esta sección
    $this->login_library->can_access(self::MIN_LEVEL); 
  }

  //
  // [ USERS PANEL ]
  //
  //
  public function index(){
    // revisa que el usuario esté logeado y tenga el 
    // nivel necesario para acceder a esta sección
    $redirect = 'bienvenido/usuarios/' . $this->user->id;
    $this->login_library->can_access(self::CREATE_LEVEL, $redirect); 

    // la información de salida para el view. Si no se recibe información
    // del POST para crear un nuevo usuario, el reporte no contiene nada.
    $report = false;
    if(!empty($_POST)){
    // si se recibe infomración para crear un nuevo usuario, intenta crearlo,
    // y el resultado se asigna al reporte de salida
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
    // [4] si pasa la validación, crea un usuario y lo regresa. También envía
    //     una notificación por correo.
    else{
      $hash    = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
      $user_id = $this->admins_model->add(['email' => $email, 'password' => $hash, 'level'=>$level]);
      $notify  = $this->mailgun_library->send_welcome_email($email);
      return ['success' => true, 'user' => ['id' => $user_id, 'email' => $email, 'level'=>$level]];
    }
  }

  //
  // [ UPDATE USER ]
  //
  //
  public function update($id = false){
    // revisa que el id exista, que pertenzca al usuario, o que el usuario sea administrador
    $id = $id ? (int)$id : $this->user->id;
    if($id != $this->user->id && self::CREATE_LEVEL > $this->session->userdata('user')->level){
      $id = $this->user->id;
    }

    $report = false;

    if(!empty($_POST)){
      $report = $this->update_user($id);
    }
    $data = [];
    $data['user']   = $this->admins_model->get($id);
    $data['report'] = $report;
	
	$data['title']         = 'Editar Usuario';
    $data['description'] = '';
    $data['body_class']  = 'users';
    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/update_admin_view', $data);
    $this->load->view('wackyland/templates/footer_view');	  
	
	
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

  //
  // [ SEARCH USER ]
  //
  //
  public function search_user(){
    $res = $this->admins_model->search($this->input->get("query"));
    header('Content-type: application/json');
    echo json_encode($res);
  }
}