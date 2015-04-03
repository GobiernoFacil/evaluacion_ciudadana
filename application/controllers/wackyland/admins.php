<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admins extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'correo', 'required|valid_email|is_unique[admins.email]');
    $this->form_validation->set_rules('password', 'clave', 'required|min_length[5]');
    $this->form_validation->set_rules('level', 'nivel', 'required|integer');
  }

  public function index(){
    $admins = $this->admins_model->all();
  }

  public function create(){
    if($this->form_validation->run() == FALSE){
      // $this->load->view('myform');
    }
    else{
      $admin = [
        'email'    =>
        'password' =>
        'level'    =>
      ];
    }
  }

  public function update($id){

  }

  public function delete($id){

  }
}