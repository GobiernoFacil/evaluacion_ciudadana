<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admins extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  public function index(){
    $admins = $this->admins_model->all();
  }

  public function create(){
   
  }

  public function update($id){

  }

  public function delete($id){

  }
}