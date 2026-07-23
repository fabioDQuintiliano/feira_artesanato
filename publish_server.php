<?php
set_time_limit(0);

function listaDiretorio($zip,$path,$prefix = ''){
	$DiretoriosNaoListados = array('./arquivos','./_public','./images/upload','./pages/pages','./admin/pages','./system','./_publish');
	$ArquivosIgnorados = array('./publish.php','./config.php','./publish_server.php','./publish_server_bd.php');
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

function ExcluiDir($Dir){
    
    if ($dd = opendir($Dir)) {
        while (false !== ($Arq = readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $Path = "$Dir/$Arq";
                if(is_dir($Path)){
                    ExcluiDir($Path);
                }elseif(is_file($Path)){
                    unlink($Path);
                }
            }
        }
        closedir($dd);
    }
    rmdir($Dir);
}
/**/
//
@ExcluiDir('./_publish');
//exit;
mkdir('_publish');


if($_GET['descompacta'] == 'doAction'){
	//DESCOMPACTA O ARQUIVO ZIP
	if(is_file('deploy.zip')){
		$zipD = new ZipArchive();
		$zipD->open(getcwd()."/deploy.zip");
		$zipD->extractTo("./");
		$zipD->close();
	
	}
	
	chmod ('images/upload' , 0777);
	chmod ('arquivos' , 0777);
		
}else{
	//GERA UM ARQUIVO ZIP COM TODOS OS ARQUIVOS NECESSÁRIO PARA O SISTEMA
	$zip = new ZipArchive();
	if($zip->open('_publish/publish.zip', ZIPARCHIVE::CREATE) == TRUE){	
		listaDiretorio($zip,'./');
	}else{
		echo 'O Arquivo não pode ser criado.';
		exit;
	}
	$zip->close();
	@chmod ('_publish/publish.zip', 0600);
}
echo 'concluido';
?>