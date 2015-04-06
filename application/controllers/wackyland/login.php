<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('wackyland/login_view');
  }

  private function validate(){
    $response = [];
    // valida el correo
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $pass  = filter_input(INPUT_POST, 'pass');

    $user  = $this->admins_model->get_by_email($email);
    $valid = password_verify($pass, $user->password);

  }


}

/* End of file test.php */