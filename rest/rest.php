<?php
require 'rest/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

function json_result($no){
    echo json_encode(($no));
};
/*
function json_result_with_cache($no, $p1,$p2,$hash,$time){

	$data = json_encode(utf8_encode_rec($no));
	meta_cache_push($p1,$p2, $hash,$data, $time);
    echo $data;
};

function json_result_with_cache_start( $p1,$p2,$hash,$time){

	$return = meta_cache_pop($p1,$p2,$hash,  60*60*24);
	if($return !==null){ echo $return; return true; }
	return false;

}*/

function app_body(){

	$app = \Slim\Slim::getInstance();
	$request = $app->request();
	//var_dump($request);
	$body = $request->getBody();
	//return $body;
	
	$event = json_decode($body);
	return $event;

};


function app_get_token(){

	$app = \Slim\Slim::getInstance();
	$request = $app->request();
	return $request->get('token');

};


function check_token($param=''){
	if(DEVELOPMENT_MODE===true)return true;
	$received = explode('-',app_get_token());

	if( time()-$received[0]*1 > 1000*60*60*7 ) return false; // 7 dias

	$need = md5('RESTFULL_'.$param.'_'.$received[0]);
	if($need ==	$received[1]) return true;

	$app = \Slim\Slim::getInstance();
	$app->response->setStatus(401);
	return false;
}

require 'rest/api.php';