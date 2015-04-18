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
  public function index()
  {
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


}

/* End of file login.php */