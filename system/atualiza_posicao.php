<?php

include '../config.php';
include '../functions/mainFunction.php';


$q = new Model;
//$da_confere = $q->read("system_inputs", "id = '".$_POST['bloco']."'");

$q->update("system_inputs",array('ordem' => $_POST['pos']),"id = '".$_POST['bloco']."'");
	
?>