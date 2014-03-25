<?php

	function createDirectory($path) {
		$isCreated = true;
		if (!is_dir($path)) {
			$isCreated = mkdir($path, 0700, true);
		}

		return $isCreated;
	}

	function getMigrationScripts($dir) {
		$files = array();
		if (is_dir($dir)) {
	    if ($dh = opendir($dir)) {
	      while (($file = readdir($dh)) !== false) {
	        if (strpos($file,'.php') !== false) {
	          $pureFileName = str_replace(".php", "", $file);
	          $fileNameParts = explode("_", $pureFileName);
	          $files[$fileNameParts[1]]['name'] = $file;
	          $files[$fileNameParts[1]]['className'] = $pureFileName;
	        }
	      }
	      closedir($dh);
	    } else {
	    	$files = false;
	    }
		}

		return $files;
	}