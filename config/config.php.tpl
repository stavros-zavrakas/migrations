<?php

$config = array();

$config['env'] =  'development'; // live=live ; test=test; dev=development

$config['db'] = array();
$config['db']['mongodb'] 								= array();
$config['db']['mongodb']['host'] 				= 'mongodb://' . (($config['env'] == 'development') ? '127.0.0.1' : (($config['env'] == 'test') ? '127.0.0.1' : '127.0.0.1'));
$config['db']['mongodb']['port'] 				= (($config['env'] == 'development') ? '27017' : (($config['env'] == 'test') ? '27017' : '27017'));
$config['db']['mongodb']['database'] 		= 'dbName';

$config['db']['migrations'] 						= array();
$config['db']['migrations']['type'] 		= 'postgres'; // Can support right now: postgres and sqlite
$config['db']['migrations']['database'] = 'dbName';

if($config['db']['migrations']['type'] == 'postgres') {
	$config['db']['migrations']['host'] 	= (($config['env'] == 'development') ? 'localhost' : (($config['env'] == 'test') ? 'localhost' : 'localhost'));
	$config['db']['migrations']['port'] 	= (($config['env'] == 'development') ? '5432' : (($config['env'] == 'test') ? '5432' : '5432'));
	$config['db']['migrations']['user'] 	= 'username';
	$config['db']['migrations']['pass'] 	= 'password';
} else if($config['db']['migrations']['type'] == 'sqlite') {
	$config['db']['migrations']['dbPath'] = '/tmp/sqlite/' . $config['db']['migrations']['database'];
}

$config['filesystem']['scriptsRelativePath'] = '/../../../migrations/';