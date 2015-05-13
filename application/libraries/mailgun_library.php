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
  // [ THE WELCOME EMAIL ]
  //
  //
  function send_welcome_email($email){
    $mailgun = new Mailgun ($this->key);
    $message = [
        'from'    => 'welcome.robot@tuevaluas.com.mx',
        'to'      => $email,
        'subject' => 'Bienvenido a tú evalúas!',
        'html'    => $this->CI->load->view('emails/mail_welcome_view', null, true)
    ];
    return $mailgun->sendMessage($this->domain, $message);
  }

  //
  // [ THE APPLICANT INVITATION ]
  //
  //
  function survey_invitation($email, $form_key){
    $mailgun = new Mailgun ($this->key);
    $url  = base_url() . "index.php/cuestionario/" . $form_key;
    $message = [
      'from'    => 'robot.invitacion@tuevaluas.com.mx',
      'to'      => $email,
      'subject' => 'Te invitamos a participar en evalúas!',
      'html'    => $this->CI->load->view('emails/mail_applicant_invitation_view', ['url' => $url], true)
    ];
    return $mailgun->sendMessage($this->domain, $message);
  }

  //
  // [ RESTORE PASSWORD ]
  //
  //
  function can_access(){
    // [1] revisa que el correo sea válido y que esté disponible una 
    //     api key de gunmail
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if(!$email || !$this->key){
      return false;
    }
    // [2] revisa que el usuario exista
    $user  = $this->CI->admins_model->get_by_email($email);
    if(empty($user)){
      return false;
    }
    // [3] genera la clave para cambiar password y la fecha de vencimiento
    //     de la clave, y las guarda en la DB
    $pass_key = sha1(uniqid($user->id));
    $date     = date_create(null);
    date_add($date, date_interval_create_from_date_string('4 days'));
    $expire_date = date_format($date, 'Y-m-d');
    $update      = ['pass_key' => $pass_key, 'expire_date' => $expire_date];
    $success     = $this->CI->admins_model->update($user->id, $update);
    // [4] envía el mensaje
    $mailgun = new Mailgun ($this->key);
    $message = [
        'from'    => 'robot.recuperador@tuevaluas.com.mx',
        'to'      => $email,
        'subject' => 'Recupera tu contraseña de tuevaluas.com.mx',
        'html'    => $this->CI->load->view('emails/mail_restore_password_view', ['pass_key' => $pass_key], true)
    ];

    return $mailgun->sendMessage($this->domain, $message);
  }
}
