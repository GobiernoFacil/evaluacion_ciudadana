<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  public function index()
  {
    $errors = [];
    if(!empty($_POST)){
      $validation = $this->validate();
      if($validation['valid']){
        $this->log_user($validation['user']);
        redirect('wackyland/tuevaluas', 'refresh');
        die();
      }
      else{
        $errors['email']    = $validation['email'];
        $errors['password'] = $validation['valid'];
      }
    }
    $this->load->view('wackyland/login_view');
  }

  private function validate(){
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $pass  = filter_input(INPUT_POST, 'pass');
    $user  = $this->admins_model->get_by_email($email);
    $valid = ! empty($user) ? password_verify($pass, $user->password) : false;

    return ['email' => $email, 'pass'  => $pass, 'user'  => $user, 'valid' => $valid];
  }

  private function log_user($user){
    $user->password = null;
    $this->session->set_userdata(['user' => $user]);
  }


}

/* End of file login.php */