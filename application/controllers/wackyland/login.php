<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('wackyland/login_view');
  }
}

/* End of file test.php */