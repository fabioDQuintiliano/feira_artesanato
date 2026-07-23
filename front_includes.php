<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
include 'config.php';
//require_once('classes/asido/class.asido.php');
include 'classes/image_resize/ImageResize.php';
include 'classes/phasher.class.php';
//include 'classes/Instagram.php';
require_once('autoload.php');
include_once('classes/simple_html_dom.php');
require __DIR__.'/vendor/autoload.php';





$functions_permidas = array("mainFunction","queryFunction","systemFunctions","systemFunctions_formsNew","functions","getIp","__list_functions");

foreach($functions_permidas as $include_functions):

	// verifica se o arquivo incluido existe antes de inclui-lo

	if(is_file('functions/'.$include_functions.'.php'))

	include 'functions/'.$include_functions.'.php';

endforeach;


//require_once("mpdf60/mpdf.php");


//require_once('classes/pagseguro/source/PagSeguroLibrary/PagSeguroLibrary.php');





?>