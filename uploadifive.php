<?php
require('front_includes.php');
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Set the uplaod directory
$uploadDir = '/images/upload/';

// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$uploadDir  = __dir__ . $uploadDir;
	
	$fileParts = pathinfo($_FILES['Filedata']['name']);


	$nomeImagem = md5(rand(0,9999).time()).'.'.$fileParts['extension'];

	$largItem =  $_POST['larguraMAX'];

	$altuItem =  $_POST['alturaMAX'];

	$viewItem =  $_POST['viewImage'];

	// Validate the filetype
	
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {



		$arquivoOrigem = $tempFile;

		resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);

		resizeImage($arquivoOrigem ,$viewItem,'','images/upload/thumb_'.$nomeImagem);

		echo json_encode(['url'=>$nomeImagem, 'view'=>'thumb_'.$nomeImagem]);

	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';

	}
}
?>