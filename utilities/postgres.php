<?php

	class stlPostgres {
		protected $connection;
		private $dsn;
		private $pgUser;
		private $pgPass;

		public function __construct($postgresArray) {
	  	$dsn  = "pgsql:";
	  	$dsn .= ";host=" . $postgresArray['host'];
	  	$dsn .= ";port=" . $postgresArray['port'];
	  	$dsn .= ";dbname=" . $postgresArray['database'];
	  	$dsn .= ";user=" . $postgresArray['user'];
	  	$dsn .= ";password=" . $postgresArray['pass'];
	  	$this->dsn = $dsn;

	  	$this->pgUser = $postgresArray['user'];
	  	$this->pgPass = $postgresArray['pass'];
		}

		public function getConnection() {
	  	try {
	      $this->connection = new PDO($this->dsn, $this->pgUser, $this->pgPass);
	      return $this->connection;
	    } catch (PDOException $e) {
	      echo 'Postgres Connection failed: ' . $e->getMessage();
	      return false;
	    }
		}
	}