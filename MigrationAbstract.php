<?php

require_once __DIR__ . '/MigrationInterface.php';

abstract class MigrationAbstract implements MigrationInterface {

	abstract public function up();

}