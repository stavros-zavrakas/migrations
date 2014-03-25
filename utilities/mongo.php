<?php

	class stlMongo {
		protected $connection;
		private $mongoStr;

		public function __construct($mongoArray) {
	  	$mongoStr = $mongoArray['host'] . ':' . $mongoArray['port'] . '/' . $mongoArray['database'];
	  	$this->mongoStr = $mongoStr;
		}

		public function getMongoConnection() {
	    try {
	      $this->connection = new MongoClient($this->mongoStr);
	      return $this->connection;
	    } catch (Exception $e) {
	      echo 'Mongo Connection failed: ',  $e->getMessage(), "\n";
	      return false;
	    }
		}
	}