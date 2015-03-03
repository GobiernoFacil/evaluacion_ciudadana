<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_application extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  public function index($form_key)
  {
    $this->load->view('test_view', $data);
  }
}

/* End of file test.php */