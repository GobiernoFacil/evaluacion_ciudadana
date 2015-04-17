<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tuevaluas extends CI_Controller {

  const MIN_LEVEL = 1;
  static $user;

  function __construct(){
    parent::__construct();
    $this->user = $this->session->userdata('user');
    if(! $this->user || self::MIN_LEVEL > $this->user->level){
      redirect('wackyland/login', 'refresh');
    }
  }

  public function index(){
	  $data['title'] 			= 'Dashboard Tú Evalúas';
	  $data['description'] 		= '';
	  
    $this->load->view('wackyland/templates/header_view', $data);	  
    $this->load->view('wackyland/tuevaluas_view');
    $this->load->view('wackyland/templates/footer_view');	  
  }
}