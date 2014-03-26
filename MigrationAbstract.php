<?php

require_once __DIR__ . '/MigrationInterface.php';

abstract class MigrationAbstract implements MigrationInterface {
  protected $db = null;
  protected $mongo = null;

  public function __construct($connections) {
  	if(!empty($connections['migrations'])) {
    	$this->db = $connections['migrations'];
    }

  	if(!empty($connections['mongo'])) {
    	$this->mongo = $connections['mongo'];
    }
  }
}