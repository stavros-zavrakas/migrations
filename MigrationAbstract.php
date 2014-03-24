<?php

require_once __DIR__ . '/MigrationInterface.php';

abstract class MigrationAbstract implements MigrationInterface {
  protected $db;
  public function __construct($db) {
    $this->db = $db;
  }
}