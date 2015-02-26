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
    // $id     = $this->question_options_model->save($option);
    header('Content-Type: application/json');
    echo json_encode(['the_option' => $option, 'the_request' => $_SERVER]);
  }

  public function delete(){

  }
}

/* End of file test.php */