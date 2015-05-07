<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| --------------------------------------------------------------------------------
| THE LOGIN 
| --------------------------------------------------------------------------------
*/
class Login extends CI_Controller {

  //
  // [ CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
  }
  //
  // [ LOGIN ]
  //
  //
  public function index(){
    $errors = [];
    if(!empty($_POST)){
      $validation = $this->validate();
      if($validation['valid']){
        $this->log_user($validation['user']);
        redirect('bienvenido/tuevaluas', 'refresh');
        die();
      }
      else{
        $errors['email']    = $validation['email'];
        $errors['password'] = $validation['valid'];
      }
    }
    $this->load->view('wackyland/login_view', ['errors' => $errors]);
  }

  //
  // [ RESTORE PASSWORD ]
  //
  //
  public function restore(){
    $error   = false;
    $success = false;

    if(!empty($_POST)){
      $success = $this->mailgun_library->can_access();
      $error   = $success ? false : true;
    }

    $this->load->view('wackyland/login_restore_view', ['error' => $error, 'success' => $success]);
  }

  //
  // [ CHANGE PASSWORD ]
  //
  //
  public function change_password($pass_key = false){
    if(! $pass_key){
      show_error('algo falló :/', 404);
      die();
    }
    
    $pass_key = filter_var($pass_key,FILTER_SANITIZE_STRING);
    $user = $this->admins_model->can_reset_password($pass_key);
    if(empty($user)){
      show_error('el plazo para cambiar tu contraseña ha expirado O___O', 404);
      die();
    }

    $error   = false;
    $success = false;
    if($this->input->post('pass') && mb_strlen($this->input->post('pass'))>=8){
      $new_pass = filter_input(INPUT_POST, 'pass');
      $admin = [
        'password'    => password_hash($new_pass, PASSWORD_DEFAULT, ['cost' => 12]),
        'pass_key'    => null,
        'expire_date' => null
      ];
      $success = $this->admins_model->update($user->id, $admin);
      $error   = $success ? false : true;
    }

    $this->load->view('wackyland/login_password_change_view', ['error' => $error, 'success' => $success]);
  }

  //
  // [ VALIDATE USER ]
  //
  //
  private function validate(){
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $pass  = filter_input(INPUT_POST, 'pass');
    $user  = $this->admins_model->get_by_email($email);
    $valid = ! empty($user) ? password_verify($pass, $user->password) : false;

    return ['email' => $email, 'pass'  => $pass, 'user'  => $user, 'valid' => $valid];
  }

  //
  // [ LOG USER ] 
  //
  //
  private function log_user($user){
    $user->password = null;
    $this->session->set_userdata(['user' => $user]);
  }

  //
  // [ LOGOUT USER ] 
  //
  //
  public function logout(){
    $this->session->unset_userdata('user');
    redirect('bienvenido', 'refresh');
  }


}

/* End of file login.php */