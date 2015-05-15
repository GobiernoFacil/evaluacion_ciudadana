<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* INITIALIZE USERS
* -------------------------------------------------------
* Crea el primer admin para Tú evalúas!
*
*/

class Initialize_users extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  * la configuración básica del controller
  *
  */
  function __construct(){
    parent::__construct();
    if(! $this->config->item('better_call_saul') ){
      die("está desactivada la opción de crear al admin");
    }
  }

  // [ BETTER CALL SAUL ]
  //  
  //  Si no existe un administrador con el correo de arturo.cordoba@gobiernofacil.com,
  //  lo crea y le asigna el password: 12345678. Es recomendable desactivar la variable
  //  "better_call_saul" en application/config/config.php después de crear el primer admin.
  //  También es recomendable crear un nuevo administrador, y eliminar al kid (arturo), o 
  //  cambiarle el password.
  public function make_saul(){
    $exist = $this->admins_model->get_by_email('arturo.cordoba@gobiernofacil.com');
    if(!empty($exist)) redirect('bienvenido');
    $email   = 'arturo.cordoba@gobiernofacil.com';
    $hash    = password_hash('12345678', PASSWORD_DEFAULT, ['cost' => 12]);
    $user_id = $this->admins_model->add(['email' => $email, 'password' => $hash, 'level'=> 99]);

    $user  = $this->admins_model->get_by_email($email);
    $user->password = null;
    $this->session->set_userdata(['user' => $user]);
    redirect('bienvenido/tuevaluas');
  }
}