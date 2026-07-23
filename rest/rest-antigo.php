<?php
require_once('../front_includes.php');
require_once'Slim/Slim.php';
\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim();
$app->post('/login', function ()  use ($app){
  
    $body = (object)$app->request()->post(); 
   	$ret = array(
   			'error'=>false,
   			'data'=>'fabio',
   			'user'=>$body->usuario
   		);

    echo json_encode($ret);
	
});



$app->run();
?>