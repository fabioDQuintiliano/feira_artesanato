<?php
$caminho =  dirname(__FILE__);
define( 'DS', DIRECTORY_SEPARATOR ); 
// Dados para Conexão via FTP
$host_ftp = "ftp.ocahotelaria.com.br";
$user_ftp = "blogd180";
$pass_ftp = "otL2Gs2e83";

$pasta_raiz = "/public_html/ocahotelaria.com.br/";
$pasta_projeto = 'teste/';


$ftp_con = ftp_connect($host_ftp);
$ftp_log = ftp_login($ftp_con,$user_ftp,$pass_ftp);


ftp_chdir($ftp_con, $pasta_raiz);
$root = ftp_pwd($ftp_con);

/* verifica o direitorio base do projeto e cria a pasta se necessário.*/
if (@ftp_chdir($ftp_con, $pasta_projeto) === false) {
	ftp_mkdir($ftp_con, $pasta_projeto); //se o diretorio do projeto não existir, ele é criado
	ftp_chdir($ftp_con, $pasta_projeto); //define o direitorio do projeto como pasta base.
	$root = ftp_pwd($ftp_con);
}else{
	ftp_chdir($ftp_con, $pasta_raiz.$pasta_projeto);//define o direitorio do projeto como pasta base.	
	$root = ftp_pwd($ftp_con);
}


/*functions para envio ----------*/
function checkForAndMakeDirs($connection, $file) {
        $origin = ftp_pwd($connection);
        $parts = explode("/", dirname($file));

        foreach ($parts as $curDir) {
            // Attempt to change directory, suppress errors
            if (@ftp_chdir($connection, $curDir) === false) {
                ftp_mkdir($connection, $curDir); //directory doesn't exist - so make it
                ftp_chdir($connection, $curDir); //go into the new directory
            }
        }

        //go back to the origin directory
        ftp_chdir($connection, $origin);
}
function listaDiretorio($path){
		
	$dir = $path;
	$d = opendir($dir);
		 
	$nome = readdir($d);
	$arquivos = array();
	while( $nome !== false ){
		if( !is_dir($nome)){
			$arquivos[] =  "{$path}".$nome;
		}elseif($nome != '.' && $nome != '..'){
			listaDiretorio($path.$nome.'/');
		}
		$nome = readdir($d);
	}
	sort($arquivos);
	return $arquivos;
}

/*-------------------------------*/


$arquivosProjeto = listaDiretorio('action'.DS);


foreach($arquivosProjeto as $arq){
	
	echo '<br/>';
	$arquivoEnviado = $caminho.DS.$arq;
	checkForAndMakeDirs($ftp_con, $arq);
	
	$serv = $root.'/'.str_replace('\\','/',$arq);
	ftp_put($ftp_con, $serv, $arquivoEnviado, FTP_BINARY);
}


/*essa parte envia o arquivo propriamente dito*/
//seleciona o arquivo
$arquivo_nome = $caminho."adm.php";

// Verificamos se a nossa variável não está em branco ou é nula
if($arquivo_nome != "" and $arquivo_nome != "none"){
// Utilizamos o comando ftp_put para enviar o arquivo.

//ftp_put($ftp_con, $pasta_raiz."adm.php", $arquivo_nome, FTP_BINARY);
}






// Encerramos a conexão de FTP previamente estabelecida
ftp_close($ftp_con);
?>