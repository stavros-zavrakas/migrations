<?php

	class stlMongo {
		protected $connection;
		private $mongoDb;
		private $mongoStr;

		public function __construct($mongoArray) {
	  	$mongoStr = $mongoArray['host'] . ':' . $mongoArray['port'] . '/' . $mongoArray['database'];
	  	$this->mongoStr = $mongoStr;
	  	$this->mongoDb = $mongoArray['database'];
		}

		public function getMongoConnection() {
	    try {
	      $this->connection = new MongoClient($this->mongoStr);
	      $dbName = $this->mongoDb;
	      return $this->connection->$dbName;
	    } catch (Exception $e) {
	      echo 'Mongo Connection failed: ',  $e->getMessage(), "\n";
	      return false;
	    }
		}
	}