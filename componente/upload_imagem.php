<?php
class Componente__upload_imagem{
	function listagem($tabela,$id,$valor=null){
		if(!empty($valor) && is_file('images/upload/'.$valor)):
			echo '<img src="'.ROOT.'images/upload/view_'.$valor.'" />';	
		endif;
	}
	function exibe($tabela,$valor=null,$PARAM=null){
		
		global $MAP;
	
		$paramh = trim($PARAM['h']);
		$paramw = trim($PARAM['w']);
		$paramview = trim($PARAM['view']);
		$campo = trim($PARAM['campo_tabela']);
	
		$mostraNomeArquivo = '';
		if( $_GET[':edit'] != ''){
			$aux = DB::read($tabela);
			$aux->id = $_GET[':edit'];
			$aux->load();
			
			
			if($aux->size()>0){
				if($aux->$PARAM['campo_tabela']!=''){
					$mostraImagemArquivo = $aux->$PARAM['campo_tabela'];	
				}
			}
		}
		
		
		ob_start();
		?>
		<script>
		if(typeof removeImageUpload != 'function'){
			function removeImageUpload(campo){
				conf('Deseja remover esta imagem',function(){
					$(".input_"+campo).html('<input type="hidden" value="removerimagem" name="rm_'+campo+'" />');
				})
			}
		}
		</script>
        
        	<label><?php echo $PARAM['nome_campo']?></label>
            <br />
            <input type="hidden" name="param_<?php echo $campo?>[w]" value="<?php echo $paramw?>" />
            <input type="hidden" name="param_<?php echo $campo?>[h]" value="<?php echo $paramh?>" />
            <input type="hidden" name="param_<?php echo $campo?>[v]" value="<?php echo $paramview?>" />
            <input type="file" name="<?php echo $campo; if($_GET['edit'] == ''){echo '[]';}?>" id="<?php echo $campo?>" <?php if($_GET['edit'] == ''){ echo 'multiple';}?> />
           	<div id="retImgCrop" style="margin-top:15px;" class="input_<?php echo trim($campo)?>">

			<?php
            if(!empty($valor) && is_file('images/upload/view_'.$valor)){
            echo '<img src="'.ROOT.'images/upload/view_'.$valor.'" width="'.$paramciew.'"><br />';
            echo '<div class="removeImageCrop" onclick="removeImageUpload(\''.trim($campo).'\')">Remover imagem</div>';	
            }?>
            
            </div>
            
            <style>
			.removeImageCrop{ background:#16a085; color:#fff; border-radius:3px; float:left; clear:both; cursor:pointer; margin:5px 0px 0px 0px; padding:2px 2px 2px 2px;}
			</style>
		<?php
		$ret = ob_get_clean();
		return $ret;
	}
	
	function save($registro,$tabela,$campo){
		
		
		$largItem =  $_POST['param_'.$campo]['w'];
		$altuItem =  $_POST['param_'.$campo]['h'];
		$viewItem =  $_POST['param_'.$campo]['v'];
		
		$aux = DB::read($tabela);
		$aux->id = $registro;
		$aux->load();

		

		if(count($_FILES[$campo]['name'])>0){
			
			for($i=0;$i<=count($_FILES[$campo]['name']);$i++){	
			
				if($_FILES[$campo]['name'][$i]!=''){
					$arquivo = $_FILES[$campo];
					$ext = explode('.',$arquivo['name'][$i]);
					$ext = array_reverse($ext);
					$nomeImagem = md5(rand(0,9999).time).'.'.$ext[0];
					$arquivoOrigem = $arquivo['tmp_name'][$i];
					resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);
					resizeImage($arquivoOrigem ,$viewItem,'','images/upload/view_'.$nomeImagem);
					marcaImage('images/upload/'.$nomeImagem);
					marcaImage('images/upload/view_'.$nomeImagem);
					
					$aux2 = DB::read($tabela);
					$aux2->pessoa = $aux->pessoa;
					$aux2->created_on = date("Y-m-d H:i:s");
					$aux2->imagem = $nomeImagem;
					$aux2->save();
					@move_uploaded_file($arquivoOrigem,'images/upload/full/'.$nomeImagem);
				
				}
				
			
			}
			
		}
		
		//$aux->$campo = $nomeImagem;
		$aux->delete();
		//exit;
		
		
		return true;
		
	}
	function update($registro,$tabela,$campo){
		
		$largItem =  $_POST['param_'.$campo]['w'];
		$altuItem =  $_POST['param_'.$campo]['h'];
		$viewItem =  $_POST['param_'.$campo]['v'];
		
		$aux = DB::read($tabela);
		$aux->id = $registro;
		$aux->load();


		if($_POST['rm_'.$campo] == 'removerimagem'){
			if(is_file('images/upload/'.$aux->$campo)){
				unlink('images/upload/'.$aux->$campo);	
			}
			if(is_file('images/upload/view_'.$aux->$campo)){
				unlink('images/upload/view_'.$aux->$campo);	
			}
			$nomeImagem = '';	
		}
		
		if($_FILES[$campo]['size']>0){
			
			
			$arquivo = $_FILES[$campo];
			$ext = explode('.',$arquivo['name']);
			$ext = array_reverse($ext);
			$nomeImagem = md5(rand(0,9999).time).'.'.$ext[0];
			$arquivoOrigem = $arquivo['tmp_name'];
			resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);
			resizeImage($arquivoOrigem ,$viewItem,'','images/upload/view_'.$nomeImagem);
			marcaImage('images/upload/'.$nomeImagem);
			marcaImage('images/upload/view_'.$nomeImagem);
			
		}
		
		$aux->$campo = $nomeImagem;
		$aux->update();
		

		return true;
		
	}
	function view($tabela,$valor=''){
		$name = explode("\n",$valor);
		return '<a target="_blank" href="ROOT/arquivos/'.$name[1].'">'.$name[0].'</a>';
	}
	
}
?>

