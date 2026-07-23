<?php
class Componente__upload_arquivo{
	function listagem($tabela,$id,$valor=null){
		
		$name = explode("\n",$valor);
		return $name[0];
		
		
	}
	function exibe($tabela,$valor=null,$PARAM=null){
		global $MAP;
		$mostraNomeArquivo = '';
		if( $_GET[':edit'] != ''){
			$aux = DB::read($tabela);
			$aux->id = $_GET[':edit'];
			$aux->load();
			
			
			if($aux->size()>0){
				
				$nomeEXB = explode("\n",$aux->$PARAM['campo_tabela']);
				
				if($nomeEXB[0]!=''){
					$mostraNomeArquivo = $nomeEXB[0];	
				}
					
			}
		}
		ob_start();
		
		
		?>
        	<label><?php echo $PARAM['nome_campo']?></label>
            <br />
            <input type="hidden" name="NomeInputCampoArquivo[]" value="<?php echo $PARAM['campo_tabela']?>" />
            <input type="file" name="<?php echo $PARAM['campo_tabela']?>" id="<?php echo $PARAM['campo_tabela']?>" />
            <br />
            <?php if($mostraNomeArquivo != ''){?>
			Arquivo atual: <a target="_blank" href="ROOT/arquivos/<?php echo $nomeEXB[1]?>"><strong><?php echo $mostraNomeArquivo?></strong></a>
            <?php }?>
		<?php
		$ret = ob_get_clean();
		return $ret;
	}
	
	function save($registro,$tabela,$campo){
		
		if($_POST['NomeInputCampoArquivo']>0){
		foreach($_POST['NomeInputCampoArquivo'] as $k=>$v){
			if($_FILES[$v]['size']>0){
			$arquivo = $_FILES[$v];
			//$arquivo = $_FILES['arquivo'];
			$nome_arq = $arquivo['name'];
			$ext = explode('.',$arquivo['name']);
			$ext = array_reverse($ext);
			
			$nome_arquivo = md5(rand(0,9999).time).'.'.$ext[0];
			
			$aux = DB::read($tabela);
			$aux->id = $registro;
			$aux->load();
			
			$aux->$campo = $nome_arq."\n".$nome_arquivo;
			$aux->update();
			move_uploaded_file($arquivo['tmp_name'],'arquivos/'.$nome_arquivo);
			}
			
			
			
		}
		}
	
		return;
		
	}
	function update($registro,$tabela,$campo){
		
		if(count($_POST['NomeInputCampoArquivo'])>0){
		foreach($_POST['NomeInputCampoArquivo'] as $k=>$v){
			
			
			if($_FILES[$v]['size']>0){
			$arquivo = $_FILES[$v];
			//$arquivo = $_FILES['arquivo'];
			$nome_arq = $arquivo['name'];
			$ext = explode('.',$arquivo['name']);
			$ext = array_reverse($ext);
			
			$nome_arquivo = md5(rand(0,9999).time).'.'.$ext[0];
			
			$aux = DB::read($tabela);
			$aux->id = $registro;
			$aux->load();
			
			$aux->$campo = $nome_arq."\n".$nome_arquivo;
			$aux->update();
			move_uploaded_file($arquivo['tmp_name'],'arquivos/'.$nome_arquivo);
			}
			
			
		}
		}
		
		return;
		
	}
	function view($tabela,$valor=''){
		$name = explode("\n",$valor);
		return '<a target="_blank" href="ROOT/arquivos/'.$name[1].'">'.$name[0].'</a>';
	}
	
}
?>

