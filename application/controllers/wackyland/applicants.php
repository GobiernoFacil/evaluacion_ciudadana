<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* APPLICANTS
* -------------------------------------------------------
* Este controller es es para llevar los registros de las
* encuestas aplicadas o por aplicar
*
*/
use League\Csv\Writer;
use League\Csv\Reader;

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

    // recomendación de la librería de CSV para mac OSX
    if (! ini_get("auto_detect_line_endings")){
      ini_set("auto_detect_line_endings", '1');
    }
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
    $num = $this->input->post('cuestionarios');
    if(!$num){
      redirect('bienvenido/cuestionarios');
    }

    $creator    = $this->user->level >= self::ADMIN_LEVEL ? false : $this->user->id;
    $blueprint  = $this->blueprint_model->get((int)$blueprint_id, $creator);
    $applicants = $this->applicants_model->count_all($blueprint->id);
    $available  = $this->max_applicants - $applicants;

    $total      = $available - (int)$num > 0 ? $num : $available;

    foreach($this->makeRange($total) as $i){
      $form_key = md5('blueprint' . $blueprint->id . uniqid($i));
      $this->add_applicant($blueprint->id, $form_key);
    }

    redirect('bienvenido/cuestionarios');
  }

  function newfile($blueprint_id){
    //$reader = Reader::createFromPath('/path/to/your/csv/file.csv');
    if(empty($_FILES) || $_FILES['csv']['error']) redirect('bienvenido/cuestionarios');
    
    $creator    = $this->user->level >= self::ADMIN_LEVEL ? false : $this->user->id;
    $blueprint  = $this->blueprint_model->get((int)$blueprint_id, $creator);
    $applicants = $this->applicants_model->count_all($blueprint->id);
    $available  = $this->max_applicants - $applicants;

    if($available <= 0 || empty($blueprint)) redirect('bienvenido/cuestionarios');


    $reader = Reader::createFromPath($_FILES['csv']['tmp_name']);
    $data = $reader->query();
    $counter = 0;
    foreach ($data as $lineIndex => $row){
      $email = filter_var($row[0], FILTER_VALIDATE_EMAIL);
      if($email){
        $form_key = md5('blueprint' . $blueprint->id . $email);
        $this->add_applicant($blueprint->id, $form_key, $email);
        $counter++;
      }
      if($counter > $available) break;
    }

    redirect('bienvenido/cuestionarios');
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