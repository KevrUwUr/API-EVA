<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class HomeModel
{
  private $db;

  function __construct()
  {
    $this->db = new Base;
  }

  // MÃ©todo para verificar la conexiÃ³n
  public function checkConnection()
  {
    return $this->db->getError();
  }
}
