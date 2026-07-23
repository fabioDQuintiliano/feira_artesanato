<?php
spl_autoload_register(function($className) {

	$d = DIRECTORY_SEPARATOR;

	//var_dump($className);


	$info = explode('\\',$className);
	$file = array_pop($info);
	$path = implode("\\", $info);
	$path = __DIR__."\\classes\\".$path.'\\'.$file.'.php';
	$path = str_replace("\\", $d, $path);


	if(is_file($path)){
		require_once($path);
	}

});