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
    if($this->user->level >= self::ADMIN_LEVEL){
      $blueprints = $this->blueprint_model->all(true);
    }
    else{
      $blueprints = $this->blueprint_model->all_from($this->user->id, true);
    }
    if(!empty($blueprints)){
      foreach ($blueprints as $blueprint){
        $blueprint->applicants = $this->applicants_model->count_all($blueprint->id);
      }
    }
    $data['title']       = 'Encuestas por aplicar Tú Evalúas';
    $data['description'] = '';
    $data['body_class']  = 'cuestionarios';
    $data['user']        = $this->user;

    
    $this->load->view('wackyland/templates/header_view', $data);
    $this->load->view('wackyland/applicants_view', ['blueprints' => $blueprints, 'max_app' => $this->max_applicants]);
  }

  function mailto($blueprint_id){
    $creator   = $this->user->level >= self::ADMIN_LEVEL ? false : $this->user->id;
    $blueprint = $this->blueprint_model->get((int)$blueprint_id, $creator);
    $email     = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if(empty($blueprint) || !$email){
      die('ora qué!');
    }

    $form_key = md5('blueprint' . $blueprint_id . $email);
    $this->add_applicant((int)$blueprint_id, $form_key, $email);
    $this->mailgun_library->survey_invitation($email, $form_key);
    redirect('bienvenido/cuestionarios');
  }

  function newnum($blueprint_id){

  }

  function newfile($blueprint_id){

  }

  function getall($blueprint_id){

  }

  function getemails($blueprint_id){

  }

  function delete($blueprint_id){

  }

  private function add_applicant($blueprint_id, $form_key, $email = null){
    $this->applicants_model->save([
      'blueprint_id' => $blueprint_id,
      'user_email'   => $email,
      'form_key'     => $form_key,
      'is_over'      => 0
    ]);
  }

  private function makeRange($length) {
    for ($i = 0; $i < $length; $i++) {
        yield $i;
    }
  }
/*
foreach (makeRange(1000000) as $i) {
    echo $i, PHP_EOL;
}
*/

  private function getRows($file) {
    $handle = fopen($file, 'rb');
    if ($handle === false) {
        throw new Exception();
    }
    while (feof($handle) === false) {
        yield fgetcsv($handle);
    }
    fclose($handle);
  }

/*
foreach (getRows('data.csv') as $row) {
    print_r($row);
}
*/
}