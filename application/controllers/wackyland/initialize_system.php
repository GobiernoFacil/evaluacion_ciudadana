<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* INITIALIZE SYSTEM
* -------------------------------------------------------
* Aquí están las instrucciones para iniciar una instalación de Tú evlúas!
*
*/

class Initialize_system extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  * la configuración básica del controller
  *
  */
  function __construct(){
    parent::__construct();
    $this->load->dbforge();
    // [1] revisa que esto se ejecute desde el CLI
    if(!$this->input->is_cli_request()){
      echo "x____x";
      die();
    }
  }

  /**
  * INDEX
  * -----------------------------------------------------
  * Debe imprimir el CSV en formato de texto. Se supone que esta
  * función debe llamarse desde el CLI, y escrbirla a un archivo.
  * En teoría.
  *
  */
  public function index(){
    exec("echo hola");
  }

  public function create_tables(){
    $this->table_admins();
  }

  private function table_admins(){
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(['email' => ['type' =>'VARCHAR', 'constraint' => '256']]);
    $this->dbforge->add_field(['password' => ['type' =>'VARCHAR', 'constraint' => '256']]);
    $this->dbforge->add_field(['level' => ['type' =>'INT', 'constraint' => 5, 'default' => 1]]);
    $this->dbforge->add_field(['pass_key' => ['type' =>'VARCHAR', 'constraint' => '256', 'null' => TRUE]]);
    $this->dbforge->add_field(['expire_date' => ['type' =>'DATE', 'null' => TRUE]]);

    if($this->dbforge->create_table('admins', TRUE)){
      echo "\033[32m se creó la tabla de admins \033[0m \n";
    }
    else{
      echo "\033[31m no se logró crear la tabla de admins \033[0m \n";
    }
  }

  public function hola(){
    echo "\033[31m hola!\033[0m \n";
  }
}