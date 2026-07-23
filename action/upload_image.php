<?php
if($_SESSION['user_id']){

	$tmpF = $_FILES['files']['tmp_name'];
	$name = $_FILES['files']['name'];

	if($name != ''){

		$ext = end(explode('.',$name));

		$filename = $_SESSION['user_id'].time().rand(0,9999).'.'.$ext;
	//	var_dump($filename);



		if(move_uploaded_file($tmpF, 'images/upload/'.$filename)){

			echo $filename;
		}

	}

}
exit;
?>