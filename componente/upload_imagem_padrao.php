<?php

class Componente__upload_imagem_padrao{

	function listagem($tabela,$id,$valor=null){

		// if(!empty($valor) && is_file('images/upload/'.$valor)):

		// 	echo '<img src="'.ROOT.'images/upload/thumb_'.$valor.'" />';	

		// endif;

		if(!empty($valor)):

			echo '<div style="text-align:left;"><img style="max-width:150px;" src="'.imageUrl($valor).'" /></div>';	

		endif;

	}

	function exibe($tabela,$valor=null,$PARAM=null){

		

		global $MAP;

	

		$paramh = trim($PARAM['h'])?trim($PARAM['h']):600;

		$paramw = trim($PARAM['w'])?trim($PARAM['w']):600;

		$paramview = trim($PARAM['view'])?trim($PARAM['view']):150;

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

            <div class="item-input-form">

	            <input type="hidden" name="param_<?php echo $campo?>[w]" value="<?php echo $paramw?>" />

	            <input type="hidden" name="param_<?php echo $campo?>[h]" value="<?php echo $paramh?>" />

	            <input type="hidden" name="param_<?php echo $campo?>[v]" value="<?php echo $paramview?>" />

	            <input type="file" name="<?php echo $campo;?>" id="<?php echo $campo?>" />

	           	<div id="retImgCrop" style="margin-top:15px;" class="input_<?php echo trim($campo)?>">



				<?php

	            if(!empty($valor) && is_file('images/upload/thumb_'.$valor)){

	            echo '<img src="'.ROOT.'images/upload/thumb_'.$valor.'" width="'.$paramciew.'"><br />';

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










		if($_FILES[$campo]['size']>0){

			

			$arquivo = $_FILES[$campo];

			$ext = explode('.',$arquivo['name']);

			$ext = array_reverse($ext);

			$nomeImagem = md5(rand(0,9999).time()).'.'.$ext[0];

			$arquivoOrigem = $arquivo['tmp_name'];

			resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);

			resizeImage($arquivoOrigem ,$viewItem,'','images/upload/thumb_'.$nomeImagem);

			$aux->$campo = $nomeImagem;

			$aux->update();

		}
		if($_FILES[$campo]['error'] == 1){
			$_SESSION['resposta_no'] = "A imagem seleciona é muito grande e por isso foi ignorada.";
		}
		



		

		

		

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

			if(is_file('images/upload/thumb_'.$aux->$campo)){

				unlink('images/upload/thumb_'.$aux->$campo);	

			}

			$nomeImagem = '';	
			$aux->$campo = $nomeImagem;

		}
	
		
	//	var_dump($_FILES);
		if($_FILES[$campo]['size']>0){

			

			$arquivo = $_FILES[$campo];

			$ext = explode('.',$arquivo['name']);

			$ext = array_reverse($ext);

			$nomeImagem = md5(rand(0,9999).time()).'.'.$ext[0];

			$arquivoOrigem = $arquivo['tmp_name'];

			resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);

			resizeImage($arquivoOrigem ,$viewItem,'','images/upload/thumb_'.$nomeImagem);
			//resizeImage($arquivoOrigem ,$viewItem,'','images/upload/thumb_'.$nomeImagem);

			$aux->$campo = $nomeImagem;


		}
		if($_FILES[$campo]['error'] == 1){
			$_SESSION['resposta_no'] = "A imagem seleciona é muito grande e por isso foi ignorada.";
		}
		

		

		
		$aux->update();

		



		return true;

		

	}

	function view($tabela,$valor=''){

		$name = explode("\n",$valor);

		return '<a target="_blank" href="ROOT/arquivos/'.$name[1].'">'.$name[0].'</a>';

	}

	

}

?>



