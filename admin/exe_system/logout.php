<!--[CONTAINER-container-admin]-->
<!--[PAGETITLE-Administracao]-->
<!--[PAGEDESCRIPTION-Descrição da página]-->
<!--[PAGEKEYWORDS-]-->
<?php
	unset($_SESSION['user_id']);
	unset($_SESSION['system_pass']);
	unset($_SESSION['system_admin']);
	session_destroy();
	
	header("location:".ROOT."admin");
?>