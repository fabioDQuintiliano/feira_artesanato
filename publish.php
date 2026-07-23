<?php
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);
require_once('front_includes.php');

//conexão com o servidor ftp
define('HOST_FTP',"ftp.ocati.com.br");
define("USER_FTP","blogd180");
define("PASS_FTP","otL2Gs2e83");
//configurações de caminhos do projeto
define("LINK_PROJETO","http://www.ocati.com.br/projetos/lovemodel/");
define("RAIZ_SISTEMA","/public_html/www.ocati.com.br/");
define("PASTA_PROJETO","projetos/lovemodel/");



define( 'DS', DIRECTORY_SEPARATOR ); 
global $ARQUIVOS;


function enviaArquivo($arquivo,$novoNomeArquivo=null){
	$caminho =  dirname(__FILE__);
	if($novoNomeArquivo == ''){
		$novoNomeArquivo = $arquivo;
	}
	// Conectar com o servidor FTP 
	$conecta = ftp_connect(HOST_FTP);
	
	if(!$conecta) die('Erro ao conectar com o servidor');
	$login = ftp_login($conecta, USER_FTP, PASS_FTP);
	//var_dump($login);
	if(!$login) die('Erro ao autenticar');
	// Liga modo passivo
	ftp_pasv($conecta, true);
	// Envia o arquivo
	$envia = ftp_put($conecta, RAIZ_SISTEMA.PASTA_PROJETO.$novoNomeArquivo, $caminho.DS.$arquivo, FTP_BINARY);
	// Desconecta do servidor
	ftp_close($conecta);
	if(!$envia){
		return false;
	}else{
		return true;
	}
	
}

function baixaArquivo($arquivo,$novoNomeArquivo=null){
	$caminho =  dirname(__FILE__);
	if($novoNomeArquivo == ''){
		$novoNomeArquivo = $arquivo;
	}
	// Conectar com o servidor FTP 
	$conecta = ftp_connect(HOST_FTP);
	//var_dump($conecta);
	if(!$conecta) die('Erro ao conectar com o servidor');
	$login = ftp_login($conecta, USER_FTP, PASS_FTP);

	if(!$login) { echo 'Problemas para logar.';exit;};
	// Liga modo passivo
	ftp_pasv($conecta, true);
	// Envia o arquivo
	$caminhoAq = (RAIZ_SISTEMA.PASTA_PROJETO.$arquivo);
	$baixa = ftp_get($conecta, $caminho.DS.$novoNomeArquivo, $caminhoAq, FTP_BINARY);
	
	// Desconecta do servidor
	ftp_close($conecta);

	if(!$baixa){
		return false;
	}
	else{
		return true;
	}
	
}

function deletaArquivo($arquivo){
	$caminho =  dirname(__FILE__);
	// Conectar com o servidor FTP 
	$conecta = ftp_connect(HOST_FTP);
	if(!$conecta) die('Erro ao conectar com o servidor');
	$login = ftp_login($conecta, USER_FTP, PASS_FTP);
	if(!$login) die('Erro ao autenticar');
	// Liga modo passivo
	ftp_pasv($conecta, true);
	// Envia o arquivo
	$envia = ftp_delete($conecta, RAIZ_SISTEMA.PASTA_PROJETO.$arquivo);
	// Desconecta do servidor
	ftp_close($conecta);
	if(!$envia){
		return false;
	}
	else{
		return true;
	}
	
}
/*
function listaDiretorio($zip,$path,$prefix = ''){
	$DiretoriosNaoListados = array('./arquivos','./_public','./images/upload','./pages/pages','./admin/pages','./system','./_publish');
	$ArquivosIgnorados = array('./publish.php','./config.php');
	$diretorio = dir($path); 
	while($arquivo = $diretorio -> read()){
		 if(is_dir($path.$arquivo)){
			 if($arquivo != '.' && $arquivo != '..' && !in_array($path.$arquivo,$DiretoriosNaoListados))
			 listaDiretorio($zip, $path.$arquivo.'/');
		 }else{
			if(!in_array($path.$arquivo,$ArquivosIgnorados)) {
		 	//echo $path.$arquivo.'<br>'; 
			$zip->addFile($path.$arquivo,$path.$arquivo);
			}
		 }
	} 
	$diretorio -> close(); 
}
*/
function listaArquivosDiferentes($path,$prefix = ''){
	global $ARQUIVOS;
	$DiretoriosNaoListados = array('./arquivos','./_public','./images/upload','./pages/pages','./admin/pages','./system','./_publish');
	$ArquivosIgnorados = array('./publish.php','./config.php','./publish_server.php','./publish_server_bd.php');
	$diretorio = dir($path); 
	
	while($arquivo = $diretorio -> read()){
		 if(is_dir($path.$arquivo)){
			 if($arquivo != '.' && $arquivo != '..' && !in_array($path.$arquivo,$DiretoriosNaoListados))
			 listaArquivosDiferentes($path.$arquivo.'/','-'.$prefix);
		 }else{
			if(!in_array($path.$arquivo,$ArquivosIgnorados)) {
				$ARQ = $path.$arquivo;
				@$arquivoLocal     = md5(file_get_contents($ARQ));
				@$arquivoPublicado = md5(file_get_contents('_publish/system/'.str_replace('./','',$ARQ)));
				
				if($arquivoLocal != $arquivoPublicado){
					$ARQUIVOS['item'][] = str_replace('./','',$ARQ);
					$ARQUIVOS['prefix'][] = $prefix; 
					$ARQUIVOS['status'][] = (file_exists('_publish/system/'.str_replace('./','',$ARQ))?'<span style="color:#f1c40f">(E)</span>':'<span style="color:#3498db">(N)</span>'); 
					//$zip->addFile(str_replace('./','',$ARQ));
				}
			}
		 }
	} 
	$diretorio -> close(); 
}
function zipArquivosDiferentes($array){

	$ret = true;
	@unlink('_publish/deploy.zip');
	$zip = new ZipArchive();
	
	//print_r($array);
	if($zip->open('_publish/deploy.zip', ZIPARCHIVE::CREATE) == TRUE){
			
		if(!is_file('_publish/system/images/upload/index.html')){
			$zip->addFile('images/upload/index.html');
		}
		if(!is_file('_publish/system/arquivos/index.html')){
			$zip->addFile('arquivos/index.html');
		}
		
		for($i=0;$i<count($array);$i++){
			$zip->addFile($array[$i]);	
		}
		//listaArquivosDiferentes($zip,'./');
	}else{
		$ret = false;
	}
	$zip->close();
	return $ret;
	
}
function executaZipServer($acao=null){
	if($acao == true){
		$param = '?descompacta=doAction';	
	}else{
		$param = '';	
	}
	$ret = file_get_contents(LINK_PROJETO.'publish_server.php'.$param);
	if($ret == 'concluido'){
		return true;	
	}else{
		return false;	
	}
}
function exibeLista(){
	global $ARQUIVOS;
	if(count($ARQUIVOS)>0){
		echo '<div class="boxItens">';
		echo '<form method="post" action="publish.php?etapa=2" id="itensEnvia">';
		for($i=0;$i<count($ARQUIVOS['item']);$i++){
			echo '<input type="checkbox" name="arq[]" value="'.$ARQUIVOS['item'][$i].'" checked="checked" />';
			echo $ARQUIVOS['prefix'][$i].$ARQUIVOS['status'][$i].$ARQUIVOS['item'][$i];
			echo '<br />';
				
		}
		echo '<input type="button" value="Enviar" class="btEnviar">';
		echo '</form>';
		echo '</div>';
	}else{
		echo '<div class="titulo">Não há arquivos para serem enviados.</div>';	
	}
}

function listaDadosDb(){
	$dados = file_get_contents('dblog.txt');
	$dados = explode("\n",$dados);
	if(count($dados)>0){
		echo '<div class="boxItens">';
		echo '<form method="post" action="publish.php?etapa=gera_arquivo_db" id="itensEnvia">';
		echo '<span class="h1">Verifique os comandos SQL antes de executar a atualização. </span><br /><br />';
		for($i = 0;$i<=count($dados);$i++){
			$sql = trim($dados[$i]);
			if($sql != ''){
				echo '<input type="checkbox" name="sql[]" value="'.$sql.'" checked="checked">';
				echo $sql;
				echo '<br />';
			}
		}
		echo '<input type="button" value="Enviar" class="btEnviar">';
		echo '</form>';
		echo '</div>';
		
	}else{
		echo '<div class="titulo">Não comandos sql aqui. Verifique o arquivo dblog.txt.</div>';	
	}
	
	
}
function postExeDb($ps){
	
	$param = base64_encode(serialize($ps));
	
	//set POST variables
	$url = LINK_PROJETO.'publish_server_db.php';
	$fields = array(
					'code' => urlencode('makePageSqlParamState'),
					'param' => urlencode($param)
				);
	
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	//executa post
	$result = curl_exec($ch);
	curl_close($ch);	
}

//$a = postExeDb(array('teste','teste 2'));
//print_r(unserialize(base64_decode($a)));

$ETAPA = $_GET['etapa'];
if($ETAPA == 1):
	$ARQUIVOS = array();
	mkdir('_publish');
	enviaArquivo('publish_server.php');
	$ret = executaZipServer();
	
	//sleep(5);
	deletaArquivo('publish_server.php');
	if($ret == true){
	    baixaArquivo('_publish/publish.zip');
		
		if(is_file('_publish/publish.zip')){
			$zipD = new ZipArchive();
			$zipD->open(getcwd()."/_publish/publish.zip");
			$zipD->extractTo("_publish/system");
			$zipD->close();
		
		}
		
	}
	@deletaArquivo('_publish/publish.zip');
	listaArquivosDiferentes('./');
	exibeLista();
	
elseif($ETAPA == 2):
	if(count($_POST['arq'])>0):
		zipArquivosDiferentes($_POST['arq']);
		
		@deletaArquivo('deploy.zip');
		enviaArquivo('publish_server.php');
		enviaArquivo('_publish/deploy.zip','deploy.zip');
		$ret = executaZipServer(true);
		deletaArquivo('deploy.zip');
		deletaArquivo('publish_server.php');
	
		location('publish.php?etapa=3');
	endif;
	
elseif($ETAPA == 3):
	echo '<div class="titulo">Sistema atualizado com sucesso.</a>';

elseif($ETAPA == 'gera_arquivo_db'):
	if(count($_POST['sql'])>0):
	
		/*foreach($_POST['sql'] as $comando):
			echo $comando.'<br>';
		endforeach;
		*/
	
		enviaArquivo('publish_server_db.php');
		postExeDb($_POST['sql']);


	endif;
	
elseif($ETAPA == 'atualiza_db'):
	listaDadosDb();
	
	
else:

?>
<div class="containerIcones">

	<div class="titulo">Atualização do sistema</div>
	<div class="boxIcones" id="atualizaSistemaDB">
    	<img src="system/img/ico_sql.png" />
        <div class="boxTxt">
        	Banco de Dados
        </div>
    </div><!-- <strong>boxIcones</strong> -->

	<div class="boxIcones" id="atualizaSistema">
    	<img src="system/img/ico_atualizar.png" />
        <div class="boxTxt">
        	Atualizar Versão
        </div>
    </div><!-- <strong>boxIcones</strong> -->

</div>
<div class="iniciando">
    <div class="titulo"></div>
    <img src="system/img/loader.GIF" width="80" />
</div>


<?php	
endif;
/**/
?>
<script src="<?php echo ROOT?>script/jquery-1.9.0.js"></script>
<script>
$(function(){
	
	$('.btEnviar').click(function(){
		$('.boxItens').hide();
		$('body').append('<div class="titulo">Aguarde, atualizando. <br /><img src="<?php echo ROOT?>system/img/loader.GIF" width="80" /></div>');
		setTimeout(function(){
			$('#itensEnvia').submit();
		},500)
		
	})
	
	$('#atualizaSistemaDB').click(function(){
		$('.containerIcones').hide();
		$('.iniciando .titulo').html('Buscando itens do banco');

		$('.iniciando').show();
		setTimeout(function(){
			location.href='publish.php?etapa=atualiza_db';
			},3000)
		
		
	});
	$('#atualizaSistema').click(function(){
		$('.containerIcones').hide();
		$('.iniciando .titulo').html('Verificando diferença entre arquivos');
		$('.iniciando').show();
		location.href='publish.php?etapa=1';
		
	});
})
</script>
<style>
@font-face {
    font-family: 'opensans';
    src: url('fonts/opensans.woff') format('woff');
    font-weight: normal;
    font-style: normal;

}
@font-face {
    font-family: 'GOTHIC';
    src: url('fonts/GOTHIC.eot');
    src: url('fonts/GOTHIC.eot?#iefix') format('embedded-opentype'),
         url('fonts/GOTHIC.woff') format('woff'),
         url('fonts/GOTHIC.ttf') format('truetype'),
         url('fonts/GOTHIC.svg#GOTHIC') format('svg');
    font-weight: normal;
    font-style: normal;

}


body{ background:#ECF0F1; padding:0px; margin:0px; font-family:'GOTHIC', Helvetica, Arial, sans-serif; color:#808B8D;}
.titulo{ font-size:38px; width:100%; position:relative; text-align:center; top:40%;}
.h1{ font-size:38px;}
.boxItens{ margin:20px 20px 20px 20px;}
.btEnviar{ padding:5px 10px 5px 10px; background:#1BBC9D; color:#fff; border:none; font-family:'GOTHIC', Helvetica, Arial, sans-serif; cursor:pointer; font-size:18px; margin:15px 0px 0px 0px ;}
.containerIcones{ width:800px; float:left; clear:both; position:relative; top:50%; margin-top:-137px; left:50%; margin-left:-400px;}
.boxTxt{font-size:32px; float:left; clear:both; width:100%; text-align:center; margin-top:15px;}
.boxIcones{ float:left; width:400px; position:relative; text-align:center; cursor:pointer;}
.containerIcones .titulo, .iniciando .titulo{ top:0px; margin-bottom:50px;} 

.iniciando{ position:relative; top:40%; text-align:center; display:none;}

</style>