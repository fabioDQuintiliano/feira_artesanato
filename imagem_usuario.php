<?php
require_once("front_includes.php");
$id = addslashes($_GET['user']);
$aux = DB_Class::make("system_admin")->_id($id)->_loadAll();
if($aux->foto != '' && is_file("images/upload/".$aux->foto)){
	myHeader("Location:".ROOT."images/upload/".$aux->foto);
}else{
	myHeader("Location:".ROOT."images/no-user.png");

}
?>