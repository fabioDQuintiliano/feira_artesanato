<?php

class Componente__auto_jcrop{

	public function listagem($tabela,$id,$valor=null){

		if(!empty($valor)):

			echo '<div style="text-align:center;"><img style="max-width:150px;" src="'.imageUrl($valor).'" /></div>';	

		endif;



	}

	public function exibe($tabela,$valor=null,$PARAM=null){



		global $url,$MAP;

		

			$paramh = trim($PARAM['h']);

			$paramw = trim($PARAM['w']);

		?>

		<script>

		if(typeof reloadDiv != 'function'){

			function reloadDiv(o,campo){

				$(".input_"+campo).html(o);

			}

		}

		if(typeof removeImageCrop != 'function'){

			function removeImageCrop(campo){

				conf('Deseja remover esta imagem',function(){

					$(".input_"+campo).html('<input type="hidden" value="" name="'+campo+'" />');

				})

			}

		}

		</script>

        <label><?php echo $PARAM['nome_campo']?></label>

        <div class="item-input-form">

        

			<a style="color:#09F; white-space:nowrap;" onclick="window.open('ROOT/jcrop.php?h=<?php echo trim($paramh)?>&w=<?php echo trim($paramw);?>&campo=<?php echo trim($MAP['campo_tabela']);?>&view=<?php echo trim($PARAM['view'])?>','Imagem','width=1024,height=640')" >Selecionar Imagem (<?=$paramw?> X <?=$paramh?>)</a>

			

			<br />

			<div id="retImgCrop" style="margin-top:15px;" class="input_<?php echo trim($MAP['campo_tabela'])?>">

				

				<?php

				if(!empty($MAP['linha_input']) && is_file('images/upload/'.$MAP['linha_input'])){
					if(is_file('images/upload/view_'.$MAP['linha_input'])){
						echo '<img src="'.ROOT.'images/upload/view_'.$MAP['linha_input'].'"><br />';

					}else{
						echo '<img src="'.ROOT.'images/upload/'.$MAP['linha_input'].'"><br />';
					}

				echo '<div class="removeImageCrop" onclick="removeImageCrop(\''.trim($MAP['campo_tabela']).'\')">Remover imagem</div>';	

				}

				?>

			

			</div><!-- retImgCrop -->

		</div>
            <style>

			.removeImageCrop{ background:#16a085; color:#fff; border-radius:3px; float:left; clear:both; cursor:pointer; margin:5px 0px 0px 0px; padding:2px 2px 2px 2px;}

			</style>

           

    

<?php

	}

	

	

	function view($tabela,$valor=''){

		

		if(is_file('images/upload/'.$valor)){

			return '<img src="'.ROOT.'images/upload/view_'.$valor.'"/>';	

		}

		//return $valor;

	}

}

?>