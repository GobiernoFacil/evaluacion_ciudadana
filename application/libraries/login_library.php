<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * login_library
 * -----------------------------------------------------------
 * Esta librería lleva el control de acceso al administrador
 *
 */
 
class Login_library{
  
  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  *
  */
  static $CI;

  function __construct(){
    $this->CI =& get_instance();
  }

  //
  // [ CAN ACCESS ]
  //
  //
  function can_access($min_level, $redirect = 'bienvenido'){
    if(! $this->CI->session->userdata('user') || $min_level > $this->CI->session->userdata('user')->level){
      redirect($redirect, 'refresh');
    } 
  }

  //
  // [ SET BLUEPRINT ]
  //
  //
  function set_blueprint($id, $is_admin = false){
    $user_id   = $this->CI->session->userdata('user')->id;
    $blueprint = $this->CI->blueprint_model->get($id, $is_admin ? false : $user_id);
    if(! $blueprint) redirect('bienvenido/encuestas');
    
    $this->CI->session->set_userdata('blueprint', $blueprint);
  }
}