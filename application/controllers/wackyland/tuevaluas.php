<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| --------------------------------------------------------------------------------
| THE DASHBOARD
| --------------------------------------------------------------------------------
*/
class Tuevaluas extends CI_Controller {

  //
  // [ SETTINGS ]
  //
  //
  const MIN_LEVEL = 1;
  static $user;

  //
  // [ CONSTRUCTOR ]
  //
  //
  function __construct(){
    parent::__construct();
    // [ THE SESSION CHECK ] 
    $this->user = $this->session->userdata('user');
		$this->login_library->can_access(self::MIN_LEVEL);
  }

  //
  // [ DASHBOARD ]
  //
  //
  public function index(){
	  $data['title'] 			 = 'Dashboard Tú Evalúas';
	  $data['description'] = '';
	  $data['body_class']  = 'dash';
    $data['user']        = $this->user;
	  
	  $admins  = $this->user->level >= 5 ? $this->admins_model->all() : [];
    $surveys = $this->user->level >= 5 ? $this->blueprint_model->all() : $this->blueprint_model->all_from($this->user->id);
	  
	  $this->load->view('wackyland/templates/header_view', $data);	  
	  $this->load->view('wackyland/tuevaluas_view', ['admins' => $admins, 'surveys' => $surveys, 'data' => $data]);
	  $this->load->view('wackyland/templates/footer_view');	  
  }
}