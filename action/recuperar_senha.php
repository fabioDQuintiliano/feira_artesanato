<?php
$ERRO = true;
if(!empty($_POST)){
	$code = $_SESSION['codigo_rec'];
	$dao = DAO::recuperar_senha()->_where("validade >  NOW()")->_codigo($code)->_loadAll();

	
	if($dao->size()){


		$senha = $_POST[':senha'];
		$senha2 = $_POST[':senha2'];
		if($senha == $senha2 && strlen($senha)>=6){

			$n_senha = encriptPassSystem($senha);


			$daoP = DAO::System_admin()->_id($dao->pessoa)->_loadAll();
			if($daoP->size()>0){

				$daoP->senha = $n_senha;
				$daoP->update();
				$dao->delete();
				$_SESSION['sucesso'] = "Sua senha foi atualizada com sucesso.";
				$ERRO = false;
			}
		}


	}



}
if($ERRO){

	$_SESSION['erro'] = true;
}

	

echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=".$_POST['onSucesso']."'>";

//header("location:http://".$_POST['onSucesso']);

exit;

?>