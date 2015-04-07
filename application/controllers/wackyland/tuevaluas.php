<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tuevaluas extends CI_Controller {

  const MIN_LEVEL = 1;

  function __construct(){
    parent::__construct();
    if(self::MIN_LEVEL > $this->session->userdata('user')->level){
      redirect('wackyland/login', 'refresh');
    }
  }

  public function index(){
    echo "hola!";
  }
}