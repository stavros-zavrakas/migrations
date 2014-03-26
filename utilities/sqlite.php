<?php

	class stlSqlite {
		protected $connection;
		private $dsn;

		public function __construct($sqliteArray) {
	  	$dsn  = "sqlite:";
	  	$dsn .= $sqliteArray['dbPath'];
	  	$this->dsn = $dsn;
		}

		public function getConnection() {
	  	try {
	      $this->connection = new PDO($this->dsn);
	      return $this->connection;
	    } catch (PDOException $e) {
	      echo 'Sqlite Connection failed: ' . $e->getMessage();
	      return false;
	    }
		}
	}