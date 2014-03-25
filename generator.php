<?php

define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/config/config.php';

$scriptsDir = APP_ROOT . $config['filesystem']['scriptsRelativePath'];

if(!isset($argv[1])) {
	echo "Please provide a class name as parameter";
	die;
} else {
	$argv[1] = preg_replace("/[^a-zA-Z0-9]+/", "", $argv[1]);
}


if (!is_dir($scriptsDir)) {
  $isCreated = mkdir($scriptsDir, 0700, true);
  if(!$isCreated) {
    die;
  }
}

$today 			= getdate();
$year 			= $today['year'];
$month 			= ($today['mon'] < 9) ? '0' . $today['mon'] : $today['mon'];
$day 				= ($today['mday'] < 9) ? '0' . $today['mday'] : $today['mday'];
$hours 			= ($today['hours'] < 9) ? '0' . $today['hours'] : $today['hours'];
$minutes 		= ($today['minutes'] < 9) ? '0' . $today['minutes'] : $today['minutes'];
$seconds 		= ($today['seconds'] < 9) ? '0' . $today['seconds'] : $today['seconds'];
$className 	= $argv[1];
$filename 	= "_" . $year . $month . $day . $hours . $minutes . $seconds . "_" . $className;

$string  = "<?php" . PHP_EOL . PHP_EOL;
$string .= "require_once __DIR__ . '/../vendor/stavros-zavrakas/migrations/MigrationAbstract.php'" . PHP_EOL . PHP_EOL;
$string .= "class " . $filename . " extends MigrationAbstract {" . PHP_EOL . PHP_EOL;
$string .= "	public function up() {" . PHP_EOL;
$string .= 		PHP_EOL;
$string .= 		PHP_EOL;
$string .= "	}" . PHP_EOL . PHP_EOL;
$string .= "}";

$filename = $scriptsDir . "/" . $filename . ".php";

$fp = fopen($filename, "w");
if(!$fp) {
	echo "Something went wrong creating the script. Check scripts folder permissions";
}

fwrite($fp, $string);

fclose($fp);

echo $filename . " created successfully!";

?>
