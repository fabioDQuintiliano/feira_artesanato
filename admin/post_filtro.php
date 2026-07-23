<?php
foreach($_POST as $k=>$v):
	
	$_SESSION[$k] = $v;
	unset($_POST[$k]);

endforeach;
?>