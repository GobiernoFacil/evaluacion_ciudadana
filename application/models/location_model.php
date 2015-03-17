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

  function get_city($state, $city){
    $key = str_pad($city, 3, "0", STR_PAD_LEFT); 
    $q = $this->db->get_where(self::CITIES, ['estado_id' => $state, 'clave' => $key]);
    return $q->row();
  }

  function get_localities($state, $city){
    $city = $this->get_city($state, $city);
    $q = $this->db->get_where(self::LOCALITIES, ['municipio_id' => $city->id]);
    return $q->result();
  }
}