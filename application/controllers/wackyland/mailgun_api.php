<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Mailgun\Mailgun;

class Mailgun_api extends CI_Controller {

  static $config;
  public function __construct(){
    parent::__construct();
    require __DIR__ . '/../../../.mailgun_config.php';
    $this->config = $mainlgun_config;
  }

  public function index(){
    $mailgun = new Mailgun ($this->config['api-key']);

    $blueprint_id = 1;

    $q = $this->db->query('SELECT * FROM small_vas LIMIT 500 OFFSET 1500');

    foreach($q->result() as $applicant){
      $applicant->key = $this->applicants_model->get_key($applicant->email, $blueprint_id);
      $applicant->url = 'http://tuevaluas.com.mx/index.php/cuestionario/' . $applicant->key->form_key;

      $email = [
        'from'    => 'prospera@tuevaluas.com.mx',
        'to'      => $applicant->email,
        'subject' => 'Tú Evalúas. Ayúdanos a mejorar PROSPERA',
        'html'    => $this->load->view('emails/vas_bulk_view', $applicant, true)
      ];

      $mailgun->sendMessage($this->config['domain'], $email);
    } 
  }
}