<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_dispatcher extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  public function index(){
    $q = $this->db->get('small_vas');
    $applicants = $q->result();
    $blueprint_id = 1;

    foreach($applicants as $email){
      $this->applicants_model->save([
        'blueprint_id' => $blueprint_id,
        'user_email'   => $email->email,
        'form_key'     => md5('blueprint' . $blueprint_id . $email->email),
        'is_over'      => 0
      ]);
    }

    /*
    $users = $this->applicants_model->all($blueprint_id);
    echo "<pre>";
    var_dump($users);
    echo "</pre>";
    */

  }
}