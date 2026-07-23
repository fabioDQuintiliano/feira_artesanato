<?php

class Componente__editor_de_texto{

	public function listagem($tabela,$id,$valor=null){

		return  $valor;

	}

	public function exibe($tabela,$valor=null,$PARAM=null){

		global $MAP;

		

		 //USE_JS('js/froala_editor.min.js');
		 //USE_CSS('css/froala_editor.pkgd.min.css');
		 USE_JS('componente/froala_editor/js/froala_editor.min.js');

		 USE_JS('componente/froala_editor/js/langs/pt_br.js');

		 USE_CSS('componente/froala_editor/css/font-awesome.min.css');

		 USE_CSS('componente/froala_editor/css/froala_editor.min.css');

		

		?>
	
		<script>
		  $(function(){
		  	/*new FroalaEditor('#<?php echo $MAP['campo_tabela']?>',{

			  imageUploadURL:'<?php echo ROOT?>fn-do_auto_editor_texto_upload'

		  	});
		  	*/
/*

      		$('#<?php echo $MAP['campo_tabela']?>').froalaEditor({

			  imageUploadURL:'<?php echo ROOT?>fn-do_auto_editor_texto_upload'

		  	})*/
    
		  })
		</script>
		<script>



		$(function(){

		  $('#<?php echo $MAP['campo_tabela']?>').editable({

			  language: 'pt_br',

			  inlineMode: false,

			  imageUploadURL:'<?php echo ROOT?>fn-do_auto_editor_texto_upload'

		  });

		});

		</script>

		<label><?php echo $PARAM['nome_campo']?></label>
		<div class="item-input-form">

			<textarea   name="<?php echo $MAP['campo_tabela']?>" id="<?php echo $MAP['campo_tabela']?>" ><?php echo $valor;?></textarea>
		</div>
		

<?php

	}

	

	function view($tabela,$valor=''){

		return $valor;

	}

	

}

?>



