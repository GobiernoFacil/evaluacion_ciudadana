<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

  function __construct(){
    parent::__construct();
  }

	public function index()
	{
    $data = [];
    $data['form']      = $this->blueprint_model->get(1);
    $data['questions'] = $this->question_model->get(1);

		$this->load->view('test_view', $data);
	}
}

/* End of file test.php */