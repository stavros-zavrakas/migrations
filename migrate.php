<?php
	 function __autoload($class_name) {
     $class_name = $class_name . ".php";
	   include __DIR__ . '/scripts/' . $class_name;
	 }

	define('APP_ROOT', __DIR__);
	require_once APP_ROOT . '/config/config.php';

	$scriptsDir = APP_ROOT . $config['filesystem']['scripts'];

  // Retrieve the file list and sort them.
	$files = array();
  $classNameToFile = array();
  $isFileSystemOk = false;
	if (is_dir($scriptsDir)) {
    if ($dh = opendir($scriptsDir)) {
      $isFileSystemOk = true;
      while (($file = readdir($dh)) !== false) {
        if (strpos($file,'.php') !== false) {
          $pureFileName = str_replace(".php", "", $file);
          $fileNameParts = explode("_", $pureFileName);
          $files[$fileNameParts[1]]['name'] = $file;
          $files[$fileNameParts[1]]['className'] = $pureFileName;

          // $classNameToFile[$fileNameParts[1]] = $file;
        }
      }
      closedir($dh);
    }
	}

  if(!$isFileSystemOk) {
    echo "Something went wrong with the directory that stores the migration scripts";
    die;
  }
  
	ksort($files, SORT_STRING);
	// $GLOBALS['files'] = $classNameToFile;
  
	$dsn  = "pgsql:";
	$dsn .= ";host=" . $config['db']['postgres']['host'];
	$dsn .= ";port=" . $config['db']['postgres']['port'];
	$dsn .= ";dbname=" . $config['db']['postgres']['database'];
	$dsn .= ";user=" . $config['db']['postgres']['user'];
	$dsn .= ";password=" . $config['db']['postgres']['pass'];

  // Uncomment the lines in php.ini to be able to work PDO and Postgres connection
  // extension=php_pdo_pgsql.dll
  // extension=php_pgsql.dll
	try {
    $db = new PDO($dsn, $config['db']['postgres']['user'], $config['db']['postgres']['pass']);
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die;
  }
  
  // Check if migrations table exists.
  $sql = "CREATE TABLE IF NOT EXISTS migrations (
            script character(20) NOT NULL,
            CONSTRAINT firstkey PRIMARY KEY (script )
          );";
  try {
    $sq = $db->query($sql);
  } catch (PDOException $e) {
    echo "Something went wrong with migrations table creation";
    die;
  }
  
  // Apply migration if is not already applied.
	foreach ($files as $fileTime => $fileName) {
    $stmt = $db->prepare("SELECT * FROM migrations WHERE script=?");
    $stmt->execute(array($fileTime));
    $results = $stmt->fetch();
    
    if (!$results) {
      try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();

        // include the script that we have to run.
        // require_once $scriptsDir . "/" . $fileName;
        $obj  = new $fileName['className']($db);
        $obj->up();
         
        $db->commit(); 
      }
      catch (PDOException $e) {
        echo print_r($e, true);
        $db->rollback(); 
        echo "Migration script: $fileName failed. Dying..\n";
        die;
      }
      
      // Success migration, write on the DB the script date.
      $stmt = $db->prepare("INSERT INTO migrations (script) VALUES (:script)");
      $stmt->bindParam(':script', $fileTime);
      $stmt->execute();
      
      echo "Migration script: $fileName succesfully applied\n";
		} else {
      echo "Migration script: $fileName already applied\n";
    }
	}