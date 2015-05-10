<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* APPLICANTS
* -------------------------------------------------------
* Este controller es es para llevar los registros de las
* encuestas aplicadas o por aplicar
*
*/

class Applicants extends CI_Controller {

  /**
  * CONSTRUCTOR
  * -----------------------------------------------------
  *
  */

  //
  // [ THE SETTINGS ]
  //
  //
  const MIN_LEVEL     = 1;
  const CREATE_LEVEL  = 3;
  const ADMIN_LEVEL   = 5;

  static $user;
  static $max_applicants;

  //
  // [ THE CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    $this->user = $this->session->userdata('user');
    $this->max_applicants = $this->config->item('max_applicants');
    $this->login_library->can_access(self::MIN_LEVEL);
    date_default_timezone_set('America/Mexico_City');
  }

  function index(){
    $blueprints = $this->blueprint_model->all_from($this->user->id, true);
    $this->load->view('wackyland/applicants_view', ['blueprints' => $blueprints]);
  }

  function mailto($bluerint_id){

  }

  function newnum($blueprint_id){

  }

  function newfile($blueprint_id){

  }

}