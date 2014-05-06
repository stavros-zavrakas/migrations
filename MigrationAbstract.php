<?php

require_once __DIR__ . '/MigrationInterface.php';

abstract class MigrationAbstract implements MigrationInterface {
  protected $db = null;
  protected $mongo = null;

  public function __construct($params) {
  	if(!empty($params['migrations'])) {
    	$this->db = $params['migrations'];
    }

    if(!empty($params['mongo'])) {
      $this->mongo = $params['mongo'];
    }
    
    if(!empty($params['configuration'])) {
      $this->configuration = $params['configuration'];
    }
  }
}