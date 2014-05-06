<?php
	 function __autoload($class_name) {
     $class_name = $class_name . ".php";
	   include SCRIPTS_ROOT . $class_name;
	 }

	define('APP_ROOT', __DIR__);
  require_once APP_ROOT . '/config/config.php';
  require_once APP_ROOT . '/utilities/filesystem.php';
  require_once APP_ROOT . '/utilities/postgres.php';
	require_once APP_ROOT . '/utilities/sqlite.php';
	require_once APP_ROOT . '/utilities/mongo.php';

	$scriptsDir = APP_ROOT . $config['filesystem']['scriptsRelativePath'];
  define('SCRIPTS_ROOT', $scriptsDir);

  $isCreated = createDirectory($scriptsDir);

  // Retrieve the file list and sort them.
	$files = getMigrationScripts($scriptsDir);
  
  if($isCreated == false || $files === false) { // Strict comparison for false.
    echo 'Something went wrong with the directory that stores the migration scripts.\n';
    die;
  } else if($files == false) { // Check for empty array
    echo 'There are no scripts in the migrations folder.\n';
    die;
  }
  
	ksort($files, SORT_STRING);

  $connections = array();
  $connections['configuration'] = $config;
  if(!empty($config['db']['migrations'])) {
    $sqlType = ucwords($config['db']['migrations']['type']);
    $sqlType = "stl".$sqlType;
    $migrations = new $sqlType($config['db']['migrations']);
    $db = $migrations->getConnection();

    if($db == false) {
      die;
    }

    $connections['migrations'] = $db;
  } else {
    echo $config['db']['migrations']['type'] . ' connection is mandatory to work the migrations. Check your configuration.\n';
    die;
  }
  
  if(!empty($config['db']['mongodb'])) {
    $mongo = new stlMongo($config['db']['mongodb']);
    $mongoDb = $mongo->getMongoConnection();

    if($mongoDb == false) {
      die;
    }

    $connections['mongo'] = $mongoDb;
  }

  // Check if migrations table exists.
  $sql = "CREATE TABLE IF NOT EXISTS migrations (
            script character(20) NOT NULL,
            CONSTRAINT firstkey PRIMARY KEY (script )
          );";
  try {
    $sq = $connections['migrations']->query($sql);
  } catch (PDOException $e) {
    echo "Something went wrong with migrations table creation";
    die;
  }
  
  // Apply migration if is not already applied.
	foreach ($files as $fileTime => $fileName) {
    $stmt = $connections['migrations']->prepare("SELECT * FROM migrations WHERE script=?");
    $stmt->execute(array($fileTime));
    $results = $stmt->fetch();
    
    if (!$results) {
      try {
        $connections['migrations']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connections['migrations']->beginTransaction();

        $obj  = new $fileName['className']($connections);
        $obj->up();
         
        $connections['migrations']->commit(); 
      }
      catch (PDOException $e) {
        echo print_r($e, true);
        $connections['migrations']->rollback(); 
        echo "Migration script: " . $fileName['name'] . " failed. Dying..\n";
        die;
      }
      
      // Success migration, write on the DB the script date.
      $stmt = $connections['migrations']->prepare("INSERT INTO migrations (script) VALUES (:script)");
      $stmt->bindParam(':script', $fileTime);
      $stmt->execute();
      
      echo "Migration script: " . $fileName['name'] . " succesfully applied\n";
		} else {
      echo "Migration script: " . $fileName['name'] . " already applied\n";
    }
	}