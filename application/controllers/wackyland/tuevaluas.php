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
		if(! $this->user || self::MIN_LEVEL > $this->user->level){
		redirect('bienvenido', 'refresh');
    }
  }

  //
  // [ DASHBOARD ]
  //
  //
  public function index(){
	  $data['title'] 			 = 'Dashboard Tú Evalúas';
	  $data['description'] = '';
	  $data['body_class']  = 'dash';
	  
	  $admins = $this->admins_model->all();
    $surveys = $this->blueprint_model->all();
	  
	  $this->load->view('wackyland/templates/header_view', $data);	  
	  $this->load->view('wackyland/tuevaluas_view', ['admins' => $admins, 'surveys' => $surveys]);
	  $this->load->view('wackyland/templates/footer_view');	  
  }
}