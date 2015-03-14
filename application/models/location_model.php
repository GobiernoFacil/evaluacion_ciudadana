<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Location model
*
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class Location_model extends CI_Model{

  const CITIES     = 'municipios';
  const LOCALITIES = 'localidades';
  
  function __construct(){
    parent::__construct();
  }

  function get_cities($id){
    $q = $this->db->get_where(self::CITIES, ['estado_id' => $id]);
    return $q->result();
  }

  function get_localities($id){
    $q = $this->db->get_where(self::LOCALITIES, ['municipio_id' => $id]);
    return $q->result();
  }
}