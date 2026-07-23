<?php


/*
--- GERA A LISTAGEM DA PASTA DE FUNÇOES
*/
$diretorio = dir("functions/");
$list_functions = '<?php '.chr(13);
while($arquivo = $diretorio->read()){
  if(is_file("functions/".$arquivo) && ($arquivo != '__list_functions.php') && (substr($arquivo,0,5) == 'auto_'))
    $list_functions .= 'include "'.$arquivo.'";'.chr(13);

}
$list_functions .= ' ?>';

$diretorio->close();

$caminho_list_functions = __DIR__ . '/../functions/__list_functions.php';
$fp = fopen($caminho_list_functions, 'c');

if (!$fp) {
	throw new RuntimeException("Não foi possível abrir: ".$caminho_list_functions);
}

if (!flock($fp, LOCK_EX)) {
	fclose($fp);
	throw new RuntimeException("Não foi possível bloquear: ".$caminho_list_functions);
}

ftruncate($fp, 0);
rewind($fp);
$escreve = fwrite($fp, $list_functions);
fflush($fp);
flock($fp, LOCK_UN);
fclose($fp);







/*
--- ESCREVE OS ARQUIVOS NO FORMATO CORRETO PARA O SISTEMA - Administração.
*/

$main_diretorio = "admin/pages/"; 
if(is_dir($main_diretorio)):
	$diretorio = dir($main_diretorio);
	
	while($arquivo = $diretorio->read()):
		if(is_file($main_diretorio.$arquivo)):
		
			$conteudo = '';
			$conteudo = file_get_contents($main_diretorio.$arquivo);
			
			preg_match_all('#\[obj=(.*?)\]#',addslashes($conteudo), $OBJS);
			
			if(count($OBJS[1])>0):
				foreach($OBJS[1] as $k=>$v):
					
					$its = explode('?',$v);
					$array = '';
					if(!empty($its[1])):
						$itens_var = explode('&',$its[1]);
						foreach($itens_var as $it=>$list_it):
							$its_list = explode('=',$list_it);
							
							$array .= "\$BANCO['".$its_list[0]."'] = '".$its_list[1]."';".chr(13);
						endforeach;
					endif;
					
					if($array != ''):
						$add = '<?php '.chr(13).$array.'?>'.chr(13);
					else:
						$add = '';
					endif;
					$conteudo = $add.str_replace('[obj='.$v.']','<?php include "admin/exe_system/'.$its[0].'.php"?>',$conteudo);
				endforeach;
			endif;
			
			@unlink("admin/exe_system/".$arquivo);
			@$fp = fopen("admin/exe_system/".$arquivo, "a+");
			@$escreve = fwrite($fp, $conteudo);
			@fclose($fp);
		   
		endif;
	endwhile;
	
	$diretorio->close();
endif;


/*
--- ESCREVE OS ARQUIVOS NO FORMATO CORRETO PARA O SISTEMA - Páginas do site.
*/
/*
$dir_list = "pages/";

$main_diretorio = $dir_list."pages/"; 
if(is_dir($main_diretorio)):
	$diretorio = dir($main_diretorio);
	
	while($arquivo = $diretorio->read()):
		if(is_file($main_diretorio.$arquivo)):
		
			$conteudo = '';
			$conteudo = file_get_contents($main_diretorio.$arquivo);
			
			preg_match_all('#\[obj=(.*?)\]#',addslashes($conteudo), $OBJS);
			
			if(count($OBJS[1])>0):
				foreach($OBJS[1] as $k=>$v):
					
					$its = explode('?',$v);
					$array = '';
					if(!empty($its[1])):
						$itens_var = explode('&',$its[1]);
						foreach($itens_var as $it=>$list_it):
							$its_list = explode('=',$list_it);
							
							$array .= "\$BANCO['".$its_list[0]."'] = '".$its_list[1]."';".chr(13);
						endforeach;
					endif;
					
					if($array != ''):
						$add = '<?php '.chr(13).$array.'?>'.chr(13);
					else:
						$add = '';
					endif;
					$conteudo = $add.str_replace('[obj='.$v.']','<?php include "'.$dir_list.''.$its[0].'.php"?>',$conteudo);
				endforeach;
			endif;
			
			@unlink($dir_list."".$arquivo);
			@$fp = fopen($dir_list."".$arquivo, "a+");
			@$escreve = fwrite($fp, $conteudo);
			@fclose($fp);
		   
		endif;
	endwhile;
	
	$diretorio->close();
endif;*/
/*
--- ESCREVE OS ARQUIVOS NO FORMATO CORRETO PARA O SISTEMA - Containers.
*/

$dir_list = "containers/";

$main_diretorio = $dir_list; 
if(is_dir($main_diretorio)):
	$diretorio = dir($main_diretorio);
	
	while($arquivo = $diretorio->read()):
		if(is_file($main_diretorio.$arquivo)):
		
			$conteudo = file_get_contents($main_diretorio.$arquivo);
			
			$INFO_CONTAINER  = explode('[CONTENT-PLACE]',$conteudo);
			
			
			@unlink($dir_list."exe_system/".str_replace('.php','_head.php',$arquivo));
			@unlink($dir_list."exe_system/".str_replace('.php','_foot.php',$arquivo));
			
			@$fp = fopen($dir_list."exe_system/".str_replace('.php','_head.php',$arquivo), "a+");
			@$escreve = fwrite($fp, $INFO_CONTAINER[0]);
			@fclose($fp);
		   
			@$fp = fopen($dir_list."exe_system/".str_replace('.php','_foot.php',$arquivo), "a+");
			@$escreve = fwrite($fp, $INFO_CONTAINER[1]);
			@fclose($fp);
		   
		endif;
	endwhile;
	
	$diretorio->close();
endif;

/*
-- inclui o arquivo que gera as definiçoes das tabelas.
*/


?>