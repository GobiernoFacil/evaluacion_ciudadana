<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* OPEN DATA
* -------------------------------------------------------
* Aquí se pueden descargar los datos de cualquier formulario,
* ya sea en archivo, en json o visualizarse como gráfica
*
*/
class Open_data extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  * la configuración básica del controller
  *
  */
  function __construct(){
    parent::__construct();
  }

  /**
  * INDEX
  * -----------------------------------------------------
  * si no se uncluye un ID, debería mostrar una lista de
  * elementos disponibles. Si incluye un ID, el default es
  * la visualización; si incluye el formato, este puede ser
  * archivo o json. 
  *
  */
  public function index($form_id = false, $format = false){
    
  }
}