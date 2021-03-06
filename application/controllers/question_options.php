<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_options extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

  /*
	public function index()
	{
    //
	}
  */

  public function index(){
    $option = json_decode(file_get_contents('php://input'));
    $option->name = uniqid();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $id = $this->question_options_model->save($option);
      header('Content-Type: application/json');
      echo json_encode(['id' => $id]);
    }
    else{
      header('Content-Type: application/json');
      echo json_encode($option);
    }
  }

  public function get_cities($state_id){
    $response = $this->location_model->get_cities((int)$state_id);
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function get_localities($state_id, $city_id){
    $response = $this->location_model->get_localities((int)$state_id, (int)$city_id);
    header('Content-Type: application/json');
    echo json_encode($response);
  }
}

/* End of file test.php */