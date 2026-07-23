<?php

class Componente__galeria_de_imagens{

	function listagem($tabela,$id,$valor=null){

		

		$name = explode("\n",$valor);

		return $name[0];

		

		

	}

	function exibe($tabela,$valor=null,$PARAM=null){

		/*

		tabela => tabela onde as imagens serão salvas
		referencia => nome da coluna que recebe o id do modulo atual
		imagem => nome da coluna que guarda o nome da imagem


		*/

		global $MAP;

		USE_JS('script/jquery.uploadifive.min.js');

		USE_CSS('css/uploadifive.css');

		$identificador = trim($PARAM['campo_tabela']);

		ob_start();

		

		

		?>

        <label><?php echo $PARAM['nome_campo']?></label>

        <div class="item-input-form">

        	<input type="hidden" name="<?php echo $identificador?>[tabela]" value="<?php echo trim($PARAM['tabela'])?>" />

        	<input type="hidden" name="<?php echo $identificador?>[referencia]" value="<?php echo trim($PARAM['referencia'])?>" />

        	<input type="hidden" name="<?php echo $identificador?>[imagem]" value="<?php echo trim($PARAM['imagem'])?>" />

            

        	<style>

        	.imagemGaleriaBox{
        		display: block;
        	}
			.imageGaleriaShow{ display: inline-block; margin:5px 5px 5px 5px; position:relative; width: 150px; height: 150px;background: #f00;  vertical-align: top; background-repeat: no-repeat; background-color: #ddd;border-radius: 3px; background-size: contain; background-position: center center;}

			.delImagemGaleria{ position:absolute; top:0px; right:0px; margin:0px 0px 0px 0px; cursor:pointer; background:#fff; border-bottom-left-radius:3px; padding:2px;}

			</style>

			<script>

                <?php $timestamp = time();?>

                $(function() {

                    $('#file_upload<?php echo $identificador?>').uploadifive({

                        'formData'     : {

                            'timestamp' : '<?php echo $timestamp;?>',

                            'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',

							'larguraMAX'     : '<?php echo trim($PARAM['largura'])?>',

							'alturaMAX'     : '<?php echo trim($PARAM['altura'])?>',

							'viewImage'     : '<?php echo (trim($PARAM['view'])!=''?trim($PARAM['view']):150)?>',

                        },

						'queueID'  :'queue<?php echo $identificador?>',
						'buttonText': 'Adicionar imagens',

                        'uploadScript' : 'ROOT/uploadifive.php',

						'onUploadComplete':function(a,o){

							console.log(o);
							let dados = JSON.parse(o);
							
							console.log(dados);

							addImageGaleria<?php echo $identificador?>(dados.url,dados.view);

						}

                    });

                });

				

				function addImageGaleria<?php echo $identificador?>(img,view){

					var elemento = $('#imagemGaleriaBox<?php echo $identificador?>');

					var contaElementos = elemento.find('input[type="hidden"]').length;

					var classeImagem = "Imagem__<?php echo $identificador?>"+contaElementos;

					var html = '<input type="hidden" name="Images_<?php echo $identificador?>[]" value="'+img+'" class="'+classeImagem+'" />';

					html += '<div class="imageGaleriaShow '+classeImagem+'"  style="background-image:url(\'<?php echo ROOT?>images/upload/'+view+'\')"><div class="delImagemGaleria" onclick="delImageGaleria<?php echo $identificador?>(\''+classeImagem+'\')"><i class="fas fa-trash-alt"></i></div></div>';

					elemento.append(html);

				}

				function delImageGaleria<?php echo $identificador?>(img){

					/*if(confirm('Deseja remover esta imagem?')){

						$('.'+img).remove();

					}*/

					conf('Tem certeza que deseja deletar este item?',function(){

						$('.'+img).remove();;

					});

				}

            </script>

        	



           

                

            <input id="file_upload<?php echo $identificador?>" name="file_upload<?php echo $identificador?>" type="file" multiple="true" />

            

            <div id="queue<?php echo $identificador?>"></div>

            <div class="imagemGaleriaBox" id="imagemGaleriaBox<?php echo $identificador?>">  </div><!-- imagemGaleriaBox -->

            

            

            <?php



//            var_dump($PARAM);
            if($_GET['edit']!=''){

				$tabela = trim($PARAM['tabela']);

				$buscaImg = DB::read($tabela);

			//	$buscaImg->referencia = $identificador;

				$buscaImg->{$PARAM['referencia']} = $_GET['edit'];

				$buscaImg->load();

				
				
				if($buscaImg->size()>0){do{

					

				?>

                <script>

					addImageGaleria<?php echo $identificador?>('<?php echo trim($buscaImg->{$PARAM['imagem']})?>','thumb_<?php echo trim($buscaImg->{$PARAM['imagem']})?>');

				</script>

                <?php	

				}while($buscaImg->next());}

				

			}

			?>

            

        
		</div>
		<?php

		$ret = ob_get_clean();

		return $ret;

	}

	

	function save($registro,$tabela,$campo){

		

	

		$tabelaImages = $_POST[$campo]['tabela'];

		$campoReferencia = $_POST[$campo]['referencia'];

		$campoImagens = $_POST[$campo]['imagem'];




		$del = DB::read($tabelaImages);

		//$del->referencia = $campo;

		$del->{$campoReferencia} = $registro;

		$del->delete();



		if(count($_POST['Images_'.$campo])>0){

			foreach($_POST['Images_'.$campo] as $k=>$v){

				$aux = DB::read($tabelaImages);

			//	$aux->referencia = $campo;

				$aux->{$campoReferencia} = $registro;

				$aux->{$campoImagens} = $v;

				$aux->save();

			}

	

		}
		

		return;

		

	}

	function update($registro,$tabela,$campo){

		$tabelaImages = $_POST[$campo]['tabela'];

		$campoReferencia = $_POST[$campo]['referencia'];

		$campoImagens = $_POST[$campo]['imagem'];
		


		

		$del = DB::read($tabelaImages);

		//$del->referencia = $campo;

		$del->{$campoReferencia} = $registro;

		$del->delete();

		

	
	
		if(count($_POST['Images_'.$campo])>0){

			foreach($_POST['Images_'.$campo] as $k=>$v){

				
				$aux = DB::read($tabelaImages);

				//$aux->referencia = $campo;

				$aux->{$campoReferencia} = $registro;

				$aux->{$campoImagens} = $v;

				$aux->save();

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



