<?php
global $MAP,$PERFIL_PERMISSOES;
$PERFIL_PERMISSOES = perfilUser();
$q = new Model;

$configTableList = getInfoItem($_GET[':item']);
require("tables/def_".$configTableList->arquivo_def.".php");
$dados = $TABLE_DEF;

if($dados['deletar'] != '1' && in_array(removeCaracteres($dados['nome']),$PERFIL_PERMISSOES['del'])):

	$idDeletado = $_GET[':reg'];
	
	$deleta = true;
	
	
	//chama a fumcao de pos delete.
	if(!empty($dados['predelete']) && function_exists($dados['predelete'])):
		
		$retDelete = $dados['predelete']($idDeletado,$dados['tabela']);
	
		if($retDelete === false){
			$deleta = false;	
		}
	endif;
	
	
	if($deleta == true){
	
		$q->delete($dados['tabela'],"id = '".$idDeletado."'");
		//mensagem de retorno
		$_SESSION['resposta_ok'] = 'Deletado com sucesso!';
	
	}
	
	
	//chama a função de pos delete.
	if(!empty($dados['posdelete']) && function_exists($dados['posdelete'])):
		
		$dados['posdelete']($idDeletado,$dados['tabela']);
	
	endif;
	

else:
	$_SESSION['resposta_no'] = 'Este item não pode ser deletado.';
endif;

$item = isset($_GET[':item']) ? (int) $_GET[':item'] : 0;
$destino = rtrim(ROOT, '/').'/adm-home'.($item > 0 ? '?item='.$item : '');
$referer = isset($_SERVER['HTTP_REFERER']) ? (string) $_SERVER['HTTP_REFERER'] : '';
if ($referer !== '') {
	$hostRoot = parse_url(ROOT, PHP_URL_HOST);
	$hostRef = parse_url($referer, PHP_URL_HOST);
	$schemeRef = parse_url($referer, PHP_URL_SCHEME);
	if ($hostRoot && $hostRef === $hostRoot && in_array($schemeRef, array('http', 'https'), true)) {
		$destino = $referer;
	}
}

myHeader('Location: '.$destino);
exit;
?>