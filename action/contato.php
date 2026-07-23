<?php

if(!empty($_POST)){

	
	$aux = DB_Class::make("contato");
	$aux->nome = addslashes($_POST['nome']);
	$aux->email = addslashes($_POST['email']);
	$aux->mensagem = addslashes($_POST['mensagem']);
	$aux->assunto = addslashes($_POST['assunto']);
	$aux->telefone = addslashes($_POST['telefone']);
	$aux->created_on = date("Y-m-d H:i:s");
	$aux->save();

	$_SESSION['sucesso'] = "Obrigado por entrar em contato. Enviaremos uma resposta o quanto antes.";

}



	

echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=".$_POST['onSucesso']."'>";

//header("location:http://".$_POST['onSucesso']);

exit;

?>