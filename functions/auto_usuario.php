<?php
function do_auto_preinsert_admin(){
	
	$_POST['senha'] = encriptPassSystem($_POST['senha']);
}
function do_auto_preupdate_admin($id){
	$q = new Model;
	$dado = $q->read("system_admin","id = '".$id."'");
	$nova_senha = encriptPassSystem($_POST['senha']);
	if($dado[0]['senha']!=$_POST['senha']):
		$_POST['senha_antiga'] = $_POST['senha'];
		$_POST['senha'] = $nova_senha;
	else:
		unset($_POST['senha']);
	endif;	
	
}
function do_auto_posupdate_admin($id){
	

	
	if($id == $_SESSION['user_id']){
		loginSystem($_SESSION['system_admin'],$_POST['senha_antiga']);
	}
}

?>