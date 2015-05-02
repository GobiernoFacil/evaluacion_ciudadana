<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Mailgun_library
 * -----------------------------------------------------------
 * Esta librería envía correos, siempre y cuando exista una
 * api key válida. Porque #YODO
 *
 */
 use Mailgun\Mailgun;

class Mailgun_library{
  
  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  *
  */
  static $CI;
  static $key;
  static $domain;

  function __construct(){
    $this->CI     =& get_instance();
    $this->key    = $this->CI->config->item('mailgun_key');
    $this->domain = $this->CI->config->item('mailgun_domain');
  }

  //
  // [ RESTORE PASSWORD ]
  //
  //
  function can_access(){
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if(!$email || !$this->key){
      return false;
    }
    
    $user  = $this->CI->admins_model->get_by_email($email);
    if(empty($user)){
      return false;
    }

    $mailgun = new Mailgun ($this->key);
    $message = [
        'from'    => 'prospera@tuevaluas.com.mx',
        'to'      => $email,
        'subject' => 'Recupera tu contraseña de tuevaluas.com.mx',
        'html'    => $this->CI->load->view('emails/vas_bulk_view', [], true)
    ];

    return $mailgun->sendMessage($this->domain, $message);
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