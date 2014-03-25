<?php

$config = array();

$config['env'] =  'development'; // live=live ; test=test; dev=development

$config['db'] = array();
$config['db']['mongodb'] = array();
$config['db']['mongodb']['host'] 			= 'mongodb://' . (($config['env'] == 'development') ? "127.0.0.1" : (($config['env'] == 'test') ? "127.0.0.1" : "127.0.0.1"));
$config['db']['mongodb']['database'] 	= 'dbName';
$config['db']['mongodb']['port'] 			= '27017';

$config['db']['postgres'] = array();
$config['db']['postgres']['host'] 		= (($config['env'] == 'development') ? 'localhost' : (($config['env'] == 'test') ? 'localhost' : 'localhost'));
$config['db']['postgres']['database'] = 'dbName';
$config['db']['postgres']['port'] 		= (($config['env'] == 'development') ? '5432' : (($config['env'] == 'test') ? '5432' : '5432'));
$config['db']['postgres']['user'] 		= (($config['env'] == 'development') ? 'xyz' : (($config['env'] == 'test') ? 'xyz' : 'xyz'));
$config['db']['postgres']['pass'] 		= (($config['env'] == 'development') ? 'xyz' : (($config['env'] == 'test') ? 'xyz' : 'xyz'));

$config['filesystem']['scriptsRelativePath'] = '/../../../app/db/migration-scripts/';