<?php

function loadFormNew($configTableList,$edit=null,$joinnn_item=null,$bt=''){

	$q = new Model;

	

	/*Solicita o arquivo de definições da tabela*/

	//require("tables/def_".$id.".php");

	$DefinicaoTabela = $configTableList->TABLE_DEF;

	$dados_inputs = $configTableList->TABLE_DEF_INPUT;

	

	global $MAP;

	$MAP['row'] = $configTableList->TABLE_DEF_INPUT;

	/*

	-- abre o formulario para edição

	*/

	

	if(!empty($edit)):

		$dados_edita = $q->read($DefinicaoTabela['tabela'],"id = '".$edit."'");

		$list_edt = $dados_edita[0];

	else:

		$dados_edita = null;

	endif;

	

	

	

	//define a "action" do formulario. Caso nenhuma action tenha sido definida, atribui a action padrão

	if(!empty($DefinicaoTabela['action']) && empty($edit)):

		$action = $DefinicaoTabela['action'];

	elseif(!empty($edit)):

		$action = 'edit_global';

	else:

		$action = 'insert_global';

	endif;

	

	

	/*

	-- DEFINE AS MASCARAS PARA OS CAMPOS

	*/

	?>

	<script>

	$(function(){

	<?php foreach($dados_inputs as $li):

			if($li['mascara_personalizada'] != ""):

	?>

				$('input[name="<?php echo $li['campo_tabela']?>"]').setMask('<?php echo $li['mascara_personalizada']?>');	

	<?php 

			endif;

		  endforeach; ?>

	})

	</script>

    

    

    

    

    <?php

	

	/*

	-- CRIA AS ABAS DO FORMULÁRIO

	*/

   // $abas = $q->read_ds("DISTINCT aba", "system_inputs", "system_form = '".$id."'",null,null,"ordem");

	$abas = $DefinicaoTabela['system_form_abas_list'];	

	if(count($abas)>=1):

	

	?>

		<script>

        $(function(){

			$(".aba_geral_item").click(function(){

				$(".aba_oculta").hide();	

				$(".aba_geral").show();	

				

				$(".item_aba").css('background-position','0px 39px');	

				

				$(this).css('background-position','0px 0px');

			});

            <?php

            foreach($abas as $aba):

            ?>

            $(".<?='open_aba_'.removeCaracteres($aba)?>").click(function(){

				$(".aba_oculta").hide();	

				$(".aba_geral").hide();	

				$(".aba_mostra_<?=removeCaracteres($aba)?>").show();

				

				$(".item_aba").css('background-position','0px 39px');	

				$(this).css('background-position','0px 0px');	

			});

            <?php

            endforeach;

            ?>

        })

        </script>

	<?php

	

		echo '<div class="aba_form_load">';

			echo '<div class="item_aba aba_geral_item">Geral</div>';

		foreach($abas as $aba):

			if(!empty($aba)):

				echo '<div class="item_aba open_aba_'.removeCaracteres($aba).'">'.$aba.'</div>';

			endif;

		endforeach;

		echo '</div>';

	endif;

	

	/*

	--- FIM DAS ABAS

	*/

	$ID_FORM_LIST = ($DefinicaoTabela['id_form']!=''?$DefinicaoTabela['id_form']:($DefinicaoTabela['tabela']!=''?'form_'.$DefinicaoTabela['tabela']:'formid_'.$DefinicaoTabela['id']));

	?>



	

	

	

	<form name="<?php echo $ID_FORM_LIST;?>" id="<?php echo $ID_FORM_LIST;?>" method="<?php echo $DefinicaoTabela['method']?>" action="ROOT/action-<?php echo $action?>?<?=str_replace('pg=', 'pageant=', $_SERVER['QUERY_STRING'])?>" class="formgeral <?php echo $DefinicaoTabela['class'];?>" enctype="multipart/form-data" onsubmit="return verificaValidacao(this.id)">

	<input type="hidden" name="formId" value="<?php echo $DefinicaoTabela['id'];?>" />

    

    <?php global $url;?>

    <input type="hidden" name="onsucesso" value="

		

		<?php 

			if($_SESSION['onsucesso'] != ''){

				$redirect = $_SESSION['onsucesso'];

				unset($_SESSION['onsucesso']);

				echo $redirect;

			}else{

				echo ($DefinicaoTabela['url_retorno']!=''?$DefinicaoTabela['url_retorno']:ROOT.'adm-home?item='.$_GET[':item']);

			}

		?>"

     />

    <?php
    if($joinnn_item!=''):

	$DADOS_JOINNN = explode('__',$joinnn_item);

	

	require_once('tables/def_'.$DADOS_JOINNN[0].'.php');

	$DADOS_JOINNN_DEF = $TABLE_DEF;

	//echo '<pre>';

	//print_r($DADOS_JOINNN_DEF);

	

	?>
	
	<input type="hidden" name="joinTabelann" value="<?php echo $joinnn_item;?>" />    

	<input type="hidden" name="joinTabelannFild___<?php echo $DADOS_JOINNN_DEF['join_n_n']['chave_estrangeira'][$DADOS_JOINNN[1]]?>" value="<?php echo $DADOS_JOINNN[2];?>" />    

    <?php 

	endif;

   

	if(!empty($edit)):?>

        <input type="hidden" name="formIdEdit" value="<?php echo $edit;?>" />

    <?php 

	endif;

	?>

    

    

	<?php

	/*

	-- Começa a gerar os campos do formulário -----------------------------------------------------------------------------------------------------------------------------

	*/

	

	foreach($dados_inputs as $li):

	

	

		$NOME_CAMPO_LABEL = $li['nome'];

		if($li['validacao']!=''){

			$NOME_CAMPO_LABEL = $NOME_CAMPO_LABEL.'<span  style="color:#f00"> *</span>'	;

		}

		

		

		if($li['mascara'] == '')

		$CLASS_BOX_ITEM = 'BOX_'.trim($li['class']);

		else

		$CLASS_BOX_ITEM = '';

	

	

		if($li['secao'] != ''):

		

			echo '<div class="linhaSeparaSessao"><h5>'.$li['secao'].'</h5></div>';

		

		endif;

		

		

		

	if(($li['exb_cadastro'] == 1 && empty($edit)) || ($li['exb_edicao'] == 1 && !empty($edit))):

		

		$MAP['linha_input'] = $list_edt[$li['campo_tabela']];

		

		$MAP['campo_tabela'] = $li['campo_tabela'];

		

		

		$INPUT_ID = ($li['id_input']!=''?$li['id_input']:($li['campo_tabela']!=''?$li['campo_tabela']:'idinput_'.$li['id']));

		$INPUT_NAME = (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id']);

		

		if($INPUT_NAME == $_SESSION['geraJoinTabela'][$_GET[':item']]['CHAVE_TABLEA_JOIN_NAME']):

			

			?>

            <div class="system_item_form <?php echo $CLASS_BOX_ITEM?>">

            	<label for="<?php echo $INPUT_ID;?>"><?php echo $NOME_CAMPO_LABEL?>&nbsp;</label>

            	<div class="item-input-form">
					<div class="linha_input_exb_restrita">

	            	<?php

					if($li['caracteristica']){
						//var_dump($li,$_SESSION);

						$EXB_CAMPO = DB::read($li['join_tabela']);

						$EXB_CAMPO->{$li['join_chave_extrangeira']} = $_SESSION['geraJoinTabela'][$_GET[':item']]['CHAVE_TABELA_ATUAL_VALUE'];

						

						

						$EXB_CAMPO->load();

						echo $EXB_CAMPO->{$li['join_campo_exibido']};

					}else{

						echo $list_edt[$li['campo_tabela']];

					}

					?>

	                </div>
                </div>

                <input type="hidden" value="<?php echo $_SESSION['geraJoinTabela'][$_GET[':item']]['CHAVE_TABELA_ATUAL_VALUE']?>" name="<?php echo $_SESSION['geraJoinTabela'][$_GET[':item']]['CHAVE_TABLEA_JOIN_NAME']?>" />

            </div> 

            <div class="clear_system  <?php echo ($li['linha_separadora'] == 1?'mostra_linha_clear':'')?>"></div>

                

			<?php

			continue;

		endif;

		

		if($edit != '' && $li['edicao_restrita'] == 1):

		?>

        	<div class="system_item_form <?php echo $CLASS_BOX_ITEM?> <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}else{echo 'aba_geral';}?>">

            	<label for="<?php echo $INPUT_ID;?>"><?php echo $NOME_CAMPO_LABEL?>&nbsp;</label>
            	<div class="item-input-form">
	                <div class="linha_input_exb_restrita">

					<?php 

					if($li['caracteristica'] == 2){

						

						$EXB_CAMPO = DB::read($li['join_tabela']);

						$EXB_CAMPO->{$li['join_chave_extrangeira']} = $list_edt[$li['campo_tabela']];

						

						$EXB_CAMPO->load();

						echo $EXB_CAMPO->{$li['join_campo_exibido']};

					}else{

						
						if(function_exists($li['funcao_exibicao'])){

							echo $li['funcao_exibicao']($edit,$list_edt[$li['campo_tabela']],'edit');

						}else{

							echo $list_edt[$li['campo_tabela']];

						}

					}



					?>

	                </div>
                </div>


            </div> 

            <div class="clear_system  <?php echo ($li['linha_separadora'] == 1?'mostra_linha_clear':'')?>"></div>

		<?php

			continue;

		endif;

		

		?>

            <div id="<?php echo $ID_FORM_LIST.'_'.$INPUT_ID?>" class="system_item_form  <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}elseif($li['type'] == 'submit'){ echo '';}else{echo 'aba_geral';}?> <?php echo $CLASS_BOX_ITEM?>">




            <?php

            if(!empty($li['mapear_componente'])):

            

                if(is_file('componente/'.$li['mapear_componente'].'.php')):

					if($li['parametros_componente']!=''){

						$paramC = explode("\n",$li['parametros_componente']);

						if(is_array($paramC)){

							$PARAM = array();

							foreach($paramC as $p){

								

								$itP = explode('=',$p);

								$PARAM[$itP[0]]=trim($itP[1]);

								

							}

							

						}

					}

					

					//inclui o componente e chama a função de listagem

					$CLASS_EXIBE_COMPONENTE = 'Componente__'.$li['mapear_componente'];

					if(!class_exists($CLASS_EXIBE_COMPONENTE)):

						echo '<input type="hidden" name="componente__mapear[]" value="'.$li['mapear_componente'].'__'.$li['campo_tabela'].'" />';

						include "componente/".$li['mapear_componente'].".php";

					endif;

					

					//passa o nome do item como parametro, junto com os demais parametros

					$PARAM['nome_campo'] = $li['nome'];

					$PARAM['campo_tabela'] = $li['campo_tabela'];

					

					$EXIBE_COMPONENTE = new $CLASS_EXIBE_COMPONENTE;

					//passa como parametro o nome da tabela, o id do registro e o valor do campo.

					echo $EXIBE_COMPONENTE->exibe($DefinicaoTabela['tabela'],$list_edt[$li['campo_tabela']],$PARAM);

					

                else:

                    echo 'Erro ao mapear componente <strong>'.$li['mapear_componente'].'.php</strong>';

                endif;

            

            else:

            ?>

            

            

            

                <?php if($li['type'] != 'hidden' && $li['type'] != 'submit' &&  $li['type'] != 'button'): ?>

                    <label for="<?php echo $INPUT_ID;?>"><?php echo $NOME_CAMPO_LABEL?>&nbsp;</label>

                   

                <?php elseif($li['type'] == 'submit' || $li['type'] == 'buttom'): ?>

                    <label for="<?php echo $INPUT_ID;?>">&nbsp;</label>

                   

                <?php endif;?>

                <div class="item-input-form">

                

                

                <?php if($li['type'] == 'select'):?>

                

                <?php

                /*

                --- verifica se o campo deve ser uma busca dinamica

                */

                global $listapar;

                ?>

                <?php if(in_array($DefinicaoTabela['tabela'].'----'.$li['campo_tabela'],$listapar)):?>

                

                

						<script>

                        $(function(){

                            $("#<?php echo $INPUT_ID.'_LSTP'?>").keyup(function(){

                                val = $(this).val();

								box = $(this).parent('.system_item_form ');

								box.css('z-index',2);

                                

                                $.post('<?=ROOT?>fn-carrega_lista',{p0:'<?=$DefinicaoTabela['tabela']?>',p1:'<?=$li['campo_tabela']?>',p2:val},function(a){

                                    

                                    

                                    $("#<?php echo  $li['campo_tabela'].'_'.$INPUT_ID.'_LSTPBOX';?>").html(a);

                                    

                                    $(".select_auto").click(function(){

                                        $("#<?php echo $INPUT_ID?>").val($(this).attr('valor'));

                                        $("#<?php echo $INPUT_ID.'_LSTP'?>").val($(this).attr('nome'))

                                        $("#<?php echo $li['campo_tabela'].'_'.$INPUT_ID.'_LSTPBOX';?>").html('');

                                        $("#<?php echo $INPUT_ID?>").trigger('change');

										box.css('z-index',1);

                                    })

                                    

                                        

                                })

                            })	

                        })

                        </script>        

                

                

						<?php

                        if(!empty($list_edt[$li['campo_tabela']])):

                            

                            $info_auto = $q->read($li['join_tabela'],$li['join_chave_extrangeira']." = '".$list_edt[$li['campo_tabela']]."'");

                        

                        endif;

                        ?>

                

                        

                        <input type="text"  name="<?php echo $li['campo_tabela']?>_LSTP" id="<?php echo $INPUT_ID.'_LSTP'?>" class="autocomplete_lista_input" value="<?php echo $info_auto[0][$li['join_campo_exibido']]?>"  autocomplete="off"/>

                        <input type="hidden"  name="<?php echo $li['campo_tabela']?>" id="<?php echo $INPUT_ID?>" value="<?php echo  $list_edt[$li['campo_tabela']]?>" class="<?php echo ($li['validacao']!=''?'validacao_lstp':'')?>" />

                        <div class="list_auto_comp" id="<?php echo  $li['campo_tabela'].'_'.$INPUT_ID.'_LSTPBOX';?>">

                        

                        

                        

                        

                        

                        

                        </div>

                	<?php else:?>

                		



                        <select name="<?php echo $INPUT_NAME?>" id="<?php echo $INPUT_ID?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>">

                            <option value="-1">Selecione...</option>

                            <?php

                            if($li['caracteristica'] == 2){

                                

								

											

								//EXECUTA A FUNÇÃO QUE DETERMINA PARAMETROS ADICIONAIS DA QUERY

								$idInputAtual = $li['id'];

								if(function_exists('input_sql_adicional_'.$idInputAtual)):

									$fn = "input_sql_adicional_{$idInputAtual}";

									ob_start();

									$fn();

									$WHERE_EXIBE_CAMPO = ob_get_clean();

								else:

									$WHERE_EXIBE_CAMPO = '(1=1)';

								endif;

								

								$dados_join = $q->read($li['join_tabela'],$WHERE_EXIBE_CAMPO);

								$tabela = $li['join_tabela'];
								$daoCampoJoin = DAO::make($tabela);
								$daoCampoJoin->_where($WHERE_EXIBE_CAMPO)->_loadAll();

								

								
								if($daoCampoJoin->size()){
									do{


										if($daoCampoJoin->{$li['join_chave_extrangeira']} == $list_edt[$li['campo_tabela']]){
	                                        $sele = 'selected="selected"';	
	                                    }else{
	                                        $sele = '';
	                                    }																						

                                

                                    	echo '<option '.$sele.' value="'.$daoCampoJoin->{$li['join_chave_extrangeira']}.'">'.$daoCampoJoin->{$li['join_campo_exibido']}.'</option>';

									}while($daoCampoJoin->next());
								}
/*
                                foreach($dados_join as $list_join):

                                    if($list_join[$li['join_chave_extrangeira']] == $list_edt[$li['campo_tabela']]){

                                        $sele = 'selected="selected"';	

                                    }else{

                                        $sele = '';

                                    }

                                

                                    echo '<option '.$sele.' value="'.$list_join[$li['join_chave_extrangeira']].'">'.$list_join[$li['join_campo_exibido']].'</option>';

                                endforeach;*/

                            }else{

                                if(!empty($li['valor'])):

                                    $dados_select = explode(',',$li['valor']);

                                    for($i = 0; $i<count($dados_select); $i++):

                                    

                                        if($i === $list_edt[$li['campo_tabela']] && $edit != ''){

											

                                            $sele = 'selected="selected"';	

											

                                        }else{

                                            $sele = '';

                                        }

                                        

                                        echo '<option '.$sele.' value="'.$i.'">'.$dados_select[$i].'</option>';

                                    

                                    endfor;

                                endif;

                            }

                            ?>

                        </select>

                        

                        

                   <?php endif;?>     

                        

                <?php elseif($li['type'] == 'checkbox'):?>

                <?php

						if($li['caracteristica'] == 2){

							$dados_join = $q->read($li['join_tabela']);

							

							$resp = unserialize($list_edt[$li['campo_tabela']]);

							
                     if(is_array($dados_join)){
                        foreach($dados_join as $list_join){

                        

                           ?>

                           <input type="checkbox" 

                           <?php echo (is_array($resp) && in_array($list_join[$li['join_chave_extrangeira']],$resp)?'checked="checked"':'')?>  

                           value="<?=$list_join[$li['join_chave_extrangeira']]?>" 

                           name="<?php echo $INPUT_NAME?>[]" 

                           id="<?php echo $INPUT_ID?>"  

                           />

                           <?php echo $list_join[$li['join_campo_exibido']]?><br />

                           <?

                        }
                     }
							

						}else{

						

							if(!empty($li['valor'])){

								$dados_select = explode(',',$li['valor']);

								$resp = array();

								$resp_u = unserialize( $list_edt[$li['campo_tabela']]);

								if(!empty($resp_u))

								$resp = $resp_u;

								

							

								for($i = 0; $i<count($dados_select); $i++){

							   

									?>

									<input type="checkbox" <?php echo (in_array($i,$resp)?'checked="checked"':'')?>  value="<?=$i?>" name="<?php echo $INPUT_NAME?>[]" id="<?php echo $INPUT_ID?>"  /><?php echo $dados_select[$i]?>

									<?php

								}

							}

						}?>







                <?php elseif($li['type'] == 'textarea'):?>

                

                    <textarea name="<?php echo $INPUT_NAME?>" id="<?php echo $INPUT_ID?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"><?php echo $list_edt[$li['campo_tabela']]?></textarea>

                 

                <?php elseif($li['type'] == 'image'):?>

                

                        <input type="<?php echo $li['type']?>"  name="<?php echo $INPUT_NAME?>" id="<?php echo $INPUT_ID?>" value="<?php if(empty($edit)){echo $li['valor'];}else{echo $list_edt[$li['campo_tabela']];}?>" src="<?php echo ROOT.$li['valor']?>"  class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"   />

                

                <?php elseif($li['type'] == 'button'):?>

                		<label>&nbsp;</label>

                        <br />

                        <input type="button"  name="<?php echo $INPUT_NAME?>" id="<?php echo $INPUT_ID?>" value="<?php echo $li['valor'];?>"  class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"   />

                

                <?php else:

                        if($li['caracteristica'] == 2):

                            $dados_join = $q->read($li['join_tabela']);

                            foreach($dados_join as $list_join):

                                ?>

                                <div class="system_item_join">

                                <input type="<?php echo $li['type']?>"  name="<?php echo $INPUT_NAME?>[]" id="<?php echo $INPUT_ID.'_'.$list_join['id']?>" value="<?php echo $list_join[$li['join_chave_extrangeira']]?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"  /><?php echo $list_join[$li['join_campo_exibido']];?>

                                </div>

                                <?php

                            endforeach;

                        else:

                ?>

                

                

                <?php

                if(empty($edit)){

                    $value = $li['valor'];

                }elseif($li['type'] == 'submit' && !empty($edit)){ 

                    $value = 'Alterar';

                }else{

                    

                    //DEFINE A FORMATAÇÃO PARA CADA TIPO DE MASCARA

                    if($li['mascara'] == 'data'){

                        $value = substr($list_edt[$li['campo_tabela']],8,2).'/'.substr($list_edt[$li['campo_tabela']],5,2).'/'.substr($list_edt[$li['campo_tabela']],0,4);

                    }elseif($li['mascara']=='decimal'){

                        $value = number_format($list_edt[$li['campo_tabela']],2,',','.');

                    }else{

                        $value = $list_edt[$li['campo_tabela']];

                    }

                }

                

                

                ?>

                

                		

                        

                        <input type="<?php echo $li['type']?>" class="<?php echo $li['class']?> <?php echo($li['mascara']!=''?'mask_type_'.$li['mascara']:'')?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>" name="<?php echo $INPUT_NAME?>"   id="<?php echo $INPUT_ID?>" 

                         value="<?php echo $value;?>" />

                        

                

                <?php

                

                        endif;

                    endif;

                    

                ?>

            	</div>
            <?php endif;?>    

            </div>

            <div class="clear_system  <?php echo ($li['linha_separadora'] == 1?'mostra_linha_clear':'')?> clear_system_<?=$li['id']?> <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}else{echo 'aba_geral';}?>"  ></div>

		<?php	

		endif;

	endforeach;

	

	

	

	?>

        <div class="clear_system  mostra_linha_clear"></div>

        <div class="system_item_form">

            <input type="submit" id="btSubmitForm" value="<?php echo ($edit!=''?'Alterar':($bt!=''?$bt:'Salvar'))?>" />

        </div>    

	</form>

	<?php

	

}





/*

 -- lista os dados de um determinado formulario criado atravez do sistema.

*/





function listFormNew($FormularioListado,$ordem = null,$startPaginacao = 0,$QUERY_FILTRO=null){

	

	

	if($QUERY_FILTRO ==''):

		$QUERY_FILTRO = '(1=1)';

	endif;

	

	if(isset($_SESSION['geraJoinTabela'][$_GET[':item']])):

	

		$QUERY_FILTRO .= ' AND '.$_SESSION['geraJoinTabela'][$_GET[':item']]['CHAVE_TABLEA_JOIN_NAME'].' = '.$_SESSION['geraJoinTabela'][$_GET[':item']]['CHAVE_TABELA_ATUAL_VALUE'];

	

	endif;

	

	$q = new Model;

	

	/*SOLICITA O ARQUIVO DE DEFINIÇÕES DA TABELA*/

	//require("tables/def_".$FormularioListado->TABLE_DEF['arquivo_def'].".php");

	



	$DefinicaoTabela = $FormularioListado->TABLE_DEF;

	$id = $DefinicaoTabela['id'];

	$tabelaListada = $DefinicaoTabela['tabela'];	

	$dados_inputs = $FormularioListado->TABLE_DEF_INPUT;

	

	

	//EXECUTA A FUNÇÃO QUE DEFINE A ORDENAÇÃO DA QUERY

	if(function_exists('sql_ordem_'.$id)):

		$fn = "sql_ordem_{$id}";

		ob_start();

		$fn();

		$ordem = ob_get_clean();

	endif;

	

	//EXECUTA A FUNÇÃO QUE DETERMINA PARAMETROS ADICIONAIS DA QUERY

	if(function_exists('sql_adicional_'.$id)):

		$fn = "sql_adicional_{$id}";

		ob_start();

		$fn();

		$sql_adicional = ob_get_clean();

	endif;

	

	

	

	/*CONTA A QUANTIDADE DE ITEM CADASTRADOS NO MODULO*/

	$contaResultados = count($q->read($DefinicaoTabela['tabela'],"(1=1 ".(($sql_adicional != ''?" AND (".$sql_adicional.")":"")).") AND ".$QUERY_FILTRO.""));

	

	

	$quantidadePorPagina = 50;

	$numeroPaginas = $contaResultados / $quantidadePorPagina;

	$offsetPagina = $startPaginacao * $quantidadePorPagina;



	

	if($contaResultados == 0):

		//if(function_exists(""))

		echo '<div class="no_result">    Nada aqui. Não temos nenhum registro para mostrar.   <br /> <lottie-player src="ROOT/images/lottie/astro.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player> </div>';

	else:

		

		/*-------------------------------------------------------*/

		if(function_exists($DefinicaoTabela['pre_listagem'])):

			echo $DefinicaoTabela['pre_listagem']($FormularioListado);

		endif;

		/*guarda todo o form em uma variavel*/

		ob_start();

		/*verifica se existe o campo "order_by" na tabela. Se existir libera a opção de ordenacao*/

		if(verificaCampoTabela($tabelaListada,'order_by')){

			$ordem = 'order_by';

		?>

		<script>

		

		

		$(function(){

			$('.tabela-listagem-item tbody').sortable({

				  revert: true,

				  beforeStop: function( event, ui ) {

					  var ordemLinha = 0;

				      var enviaOrdem = '';

					  $('.tabela-listagem-item tbody').find('tr').each(function(){

						 

						 var idReg = $(this).attr('reg');

						 if(idReg > 0){

							 

							 if(ordemLinha%2 == 0){

								$(this).addClass('odd'); 

								$(this).removeClass('even'); 

							 }else{

								 $(this).addClass('even'); 

								$(this).removeClass('odd'); 

							 }

							 

						 	enviaOrdem += idReg+'__'+ordemLinha+',';

							ordemLinha+=1;

						 }

						 

					  })

					  console.log(enviaOrdem);

					   $.post('<?php echo ROOT?>fn-alteraOrdemLinhaList',{p1:enviaOrdem,p2:'<?php echo $tabelaListada?>'},function(o){

							  console.log(o);

					    });

				   }

	   

				

			});

		});

        </script>

        <?php }?>
        <div class="table-responsive p-0">
	        <table class="tabela-listagem-item table align-items-center mb-0" cellpadding="0" cellspacing="0">

				<?php

	            ob_start();

				?>

	            <tbody>

					<?php

					//busca os registros da tabela listada

					$lista_dados = $q->read($DefinicaoTabela['tabela'],"(1=1 ".(($sql_adicional != ''?" AND (".$sql_adicional.")":"")).") AND (".$QUERY_FILTRO.")",$quantidadePorPagina,$offsetPagina, $ordem);

					

				

					$LinhsListDados = 0;

					if(count($lista_dados)>0)

					foreach($lista_dados as $ld):

						$idRegistroAtual = $ld['id'];

						$row = $LinhsListDados%2;

						if($row == 0){ $class = 'odd';}else{$class = 'even';}

						

						$LinhsListDados +=1;

					

					?>

					<tr class="<?php echo $class;?>" reg="<?=$idRegistroAtual?>">

						<?php

						$first = 0;

						

						//verifica se deve exibir um checkbox em cada linha

						if($DefinicaoTabela['checkbox'] == 1):

						$first ++;

						?>

	                    <!--[PDF-OFF-->

							<td width="20px">

							

								<?php 

								//executa uma função, caso exista, para exibir ou não o checkbox 

								if(function_exists($DefinicaoTabela['condicao_checkbox'])):

									$exbCheckboxSys = $DefinicaoTabela['condicao_checkbox']($idRegistroAtual);	

								else:

									$exbCheckboxSys = true;

								endif;

								

								if($exbCheckboxSys == true):

								?>

								<input type="checkbox" name="listItensForm[]"  value="<?php echo $idRegistroAtual?>" />

								<?php

								endif;

								?>

							</td>

	                    <!--PDF-OFF]-->

						<?php

						endif;

						?>

						<?php

						global $MAP;

						

						foreach($dados_inputs as $li):

						$MAP['itemList_'.$li['campo_tabela']] = $ld[$li['campo_tabela']];

						$MAP['linha_input'] = $ld[$li['campo_tabela']];

						$VALUE_LISTAGEM = $ld[$li['campo_tabela']];

						if($li['exb_listagem']==0)continue;

						?>

					  

						<td <?php if($first == 0){echo 'class="first"';}?>>

						

							<?php

							

							

							if($li['join_tabela']!=""):

								

								$tj = $q->read($li['join_tabela'],$li['join_chave_extrangeira']." = '".$VALUE_LISTAGEM."'");

								if(function_exists($li['funcao_exibicao'])){

									echo $li['funcao_exibicao']($idRegistroAtual,$tj[0][$li['join_campo_exibido']],'list');

								}else{

									echo $tj[0][$li['join_campo_exibido']];

								}

								

							//Substitui o campo por uma pagina. Caso a página exista

							elseif(!empty($li['mapear_componente']) && is_file('componente/'.$li['mapear_componente'].'.php')):

								



								//inclui o componeten e chama a função de listagem

								$CLASS_EXIBE_COMPONENTE = 'Componente__'.$li['mapear_componente'];

								if(!class_exists($CLASS_EXIBE_COMPONENTE)){

									include "componente/".$li['mapear_componente'].".php";

								}

								$EXIBE_COMPONENTE = new $CLASS_EXIBE_COMPONENTE;

								//passa como parametro o nome da tabela, o id do registro e o valor do campo.

								echo $EXIBE_COMPONENTE->listagem($tabelaListada,$ld['id'],$VALUE_LISTAGEM);

								

							elseif($li['type']=='select' && !empty($li['valor'])):

								 $dados_select = explode(',',$li['valor']);

								 echo $dados_select[$VALUE_LISTAGEM];

							else:

							

								//formata a exibicao de data

								if(preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", ($VALUE_LISTAGEM?$VALUE_LISTAGEM:''))){

									$value = substr($VALUE_LISTAGEM,8,2).'/'.substr($VALUE_LISTAGEM,5,2).'/'.substr($VALUE_LISTAGEM,0,4).' '.(substr($VALUE_LISTAGEM,11,5) != 0?'as '.substr($VALUE_LISTAGEM,11,5):'');

								//formata a exibicao de decimal

								}elseif($li['mascara']=='decimal'){

									$value = number_format($VALUE_LISTAGEM,2,',','.');

								}else{

									$value = $VALUE_LISTAGEM;

								}

								//executa uma função, caso se especificada, para formatar o valor do campo atual

								if(function_exists($li['funcao_exibicao'])){

									echo $li['funcao_exibicao']($idRegistroAtual,$value,'list');

								}else{

									echo $value;	

								}

							

							endif;

							 ?>

							

						</td>

						<?php

							$first += 1;

						endforeach;	

						?>

						

						

	                    

	                    <!--[PDF-OFF-->

						

						<?php

						$fumCont = 0;

						global $PERFIL_PERMISSOES,$MAP,$url;

						if($DefinicaoTabela['editar'] != '1' && in_array(removeCaracteres($DefinicaoTabela['nome']),$PERFIL_PERMISSOES['edit'])):

							$fumCont++;

							?>

							<td align="center" class="noPDF  <?php echo ($fumCont==1?"listBtsFirst":'listBts')?>" width="30" style="padding:5px 5px 5px 5px ;"><a href="ROOT/adm-home?item=<?=$_GET[':item']?>&edit=<?=$idRegistroAtual?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
								<!-- <img src='ROOT/images/admin/bt_edit.png' title="Editar Item" alt="Editar"/> -->
								<i class="fas fa-pencil-alt"></i>
							</a></td>

							<?php

						endif;

						if($DefinicaoTabela['visualizar'] != '1' && in_array(removeCaracteres($DefinicaoTabela['nome']),$PERFIL_PERMISSOES['view'])):

							$fumCont++;

							?>

							<td align="center" class="noPDF <?php echo ($fumCont==1?"listBtsFirst":'listBts')?>" width="30" style="padding:5px 5px 5px 5px ;"><a href="ROOT/adm-home?item=<?=$_GET[':item']?>&view=<?=$idRegistroAtual?>"  data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar">
								<!-- <img src='ROOT/images/admin/bt_view.png' title="Visualizar Item" alt="Visualizar" /> -->
								<i class="fas fa-eye"></i>
							</a></td>

							<?php

						endif;

						

						if($DefinicaoTabela['deletar'] != '1' && in_array(removeCaracteres($DefinicaoTabela['nome']),$PERFIL_PERMISSOES['del'])):

							$fumCont++;

							?>

							<td align="center" class="noPDF <?php echo ($fumCont==1?"listBtsFirst":'listBts')?>" width="30" style="padding:5px 5px 5px 5px ;"><a  data-bs-toggle="tooltip" data-bs-placement="top" title="Remover" onclick="conf('Tem certeza que deseja deletar este item?',

	                        

	                        function(){

	                        wait();

	                        location.href = 'ROOT/action-delete_global?item=<?php echo $id?>&reg=<?=$idRegistroAtual?>'

	                        }

	                       

	                        

	                        

	                        );">
	                        	<!-- <img src='ROOT/images/admin/bt_del.png' title="Deletar Item" align="Deletar" /> -->
	                        	<i class="fas fa-trash-alt"></i>
	                        </a></td>

							<?php

						endif;

						

						

						//if(in_array($MAP['id_form_list_menu'],$PERFIL_PERMISSOES['bt_adicional'])):

						//if(in_array($MAP['id_form_list_menu'],$PERFIL_PERMISSOES['bt_adicional'])):

						

							/*Join N x N*/

							

							

							$btjoin_n_n = $DefinicaoTabela['join_n_n'];

							

							if(is_array($btjoin_n_n))

							for($i=0;$i<count($btjoin_n_n['nome_bt']);$i++):

								if($btjoin_n_n['nome_bt'][$i]!=''){

									

									if(in_array(removeCaracteres($DefinicaoTabela['nome'].$btjoin_n_n['nome_bt'][$i]),$PERFIL_PERMISSOES['bt_adicional'])):

									

										$fumCont++;

																			

										$tabelaCountItensJoin = getConfig($btjoin_n_n['tabela_join'][$i]);

										

										/*$countItensJoin = DB::read($tabelaCountItensJoin->tabela);

										$countItensJoin->$btjoin_n_n['chave_estrangeira'][$i] = $ld[$btjoin_n_n['chave_primaria'][$i]];

										$countItensJoin->load();

										

										if($countItensJoin->size()>0){

											$EXBcountItensJoin = ' ('.$countItensJoin->size().')';

										}else{

											$EXBcountItensJoin = '';

										}/**/

										

										echo '<td class="bts_adcl noPDF '.($fumCont==1?"listBtsFirst":'listBts').'" align="center" width="50">

										

												<a href="ROOT/adm-gera_join?item='.$_GET[':item'].'&itemJoin='.$btjoin_n_n['tabela_join'][$i].'&tapPri='.$tabelaListada.'&chPrim='.$ld[$btjoin_n_n['chave_primaria'][$i]].'&chExtr='.$btjoin_n_n['chave_estrangeira'][$i].'">

													<div class="adicionalBtItem">'.

													$btjoin_n_n['nome_bt'][$i].$qtdJoinExb.$EXBcountItensJoin.

													'</div>

												</a>

											  </td>';

																		

									endif;

								}

							endfor;

							

							/*-----------------------*/

							$btAdicional = $DefinicaoTabela['botoes_adicionais'];

							if(!empty($btAdicional))

							foreach($btAdicional as $k=>$v):

								if(!empty($v)):

									

									if(in_array(removeCaracteres($DefinicaoTabela['nome']).$v,$PERFIL_PERMISSOES['bt_adicional'])):

								

										if(function_exists($v)):

											$exbBtAdicional = $v($idRegistroAtual,$DefinicaoTabela['tabela']);

											$fumCont++;

											if($exbBtAdicional != ''){

												$exbBotao = '<div class="adicionalBtItem">'.$exbBtAdicional.'</div>';

											}else{

												$exbBotao = '';

											}

											echo '<td class="bts_adcl noPDF '.($fumCont==1?"listBtsFirst":'listBts').'" align="center" width="50">'.$exbBotao.'</td>';

										endif;

										

									endif;

								endif;

							endforeach;

							/* ---------------------- */

						//endif;

						?>

	                    

	                    <!--PDF-OFF]-->

					</tr>

					<?php	

					endforeach;

					?>

				</tbody>

				<?php

	            $BODY_TABLE = ob_get_clean();

				ob_start();

				?>

				<thead>

					<tr>

						<?php

						$first = 0;

						if($DefinicaoTabela['checkbox'] == 1):

						$first++;

						?>

							<!--[PDF-OFF--><th></th><!--PDF-OFF]-->

						<?php

						endif;

						?>

					

						<?php

						if(count($dados_inputs)>0)

						foreach($dados_inputs as $li):

						if($li['exb_listagem']==0)continue;

						?>

						

							<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 <?php if($first == 0){echo 'first';}?>"><?php echo $li['nome']?></th>

						<?php

							$first += 1;

						endforeach;

						?>

						<?php if($fumCont > 0):?>

						<!--[PDF-OFF--><th  class="noPDF" colspan="<?php echo $fumCont?>"></th><!--PDF-OFF]-->

						<?php endif;?>

					</tr>

				</thead>

	            

	            <?php

	            $HEAD_TABLE = ob_get_clean();

				echo $HEAD_TABLE;

				echo $BODY_TABLE;

				?>

				

			</table>
		</div>

        <?php

        $DAT_FORM = ob_get_clean();

		$_SESSION['save_pdf'] = $DAT_FORM;

		echo $DAT_FORM;

		?>

        

		<div id="paginacaoSys">

			<?php

			if($numeroPaginas > 1):

				global $url;

				

				$startAnterior = (($startPaginacao - 3)<0?0:$startPaginacao - 3);

				$startProximo = (($startPaginacao + 4)>$numeroPaginas?$numeroPaginas:$startPaginacao + 4);

				

				if($startAnterior>0): 

					echo '<div class="itemPaginacaoSys"><a href="'.ROOT.'adm-home?item='.$_GET[':item'].'">1</a></div>';

					echo '<div class="retListPaginacao"></div>';

				endif;

				for($pg=$startAnterior;$pg<$startProximo;$pg++):

					

					echo '<div class="itemPaginacaoSys '.($pg == $startPaginacao?"itemPaginacaoSysSelect":"").'"><a href="'.ROOT.'adm-home?item='.$_GET[':item'].'&pgini='.$pg.'">'.($pg+1).'</a></div>';

				

				endfor;

				if($startProximo<$numeroPaginas):

					echo '<div class="retListPaginacao"></div>';

					echo '<div class="itemPaginacaoSys"><a href="'.ROOT.'adm-home?item='.$_GET[':item'].'&pgini='.($numeroPaginas-1).'">'.($numeroPaginas).'</a></div>';

				endif;

			endif;

			?>

		

		</div>

		

		<?php

		if(function_exists($DefinicaoTabela['pos_listagem'])):

			echo $DefinicaoTabela['pos_listagem']($id);

		endif;

		

	endif;

}



/*

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

*/

function viewForm($configTableList,$idReg){

	global $MAP;

	$q = new Model;

	$defTabela = $configTableList->TABLE_DEF;

	$dados_inputs = $configTableList->TABLE_DEF_INPUT;

	

	$MAP['row'] = $TABLE_DEF_INPUT;

	

	

	$dadosReg = DB::read($defTabela['tabela']);

	$dadosReg->id = $idReg;

	$dadosReg->load();

	

	

	foreach($dados_inputs as $li):

		if(isset($dadosReg->{$li['campo_tabela']})){

			$MAP['itemList_'.$li['campo_tabela']] = $dadosReg->{$li['campo_tabela']};

			$MAP['linha_input'] = $dadosReg->{$li['campo_tabela']};

			if($li['exb_view']==0)continue;

		

	

			if($li['secao'] != ''):

			

				echo '<div class="linhaSeparaSessao"><h5>'.$li['secao'].'</h5></div>';

			

			endif;

		

			ob_start();

			

			

			

			if($li['join_tabela']!=""):

							

				$tj = DB::read($li['join_tabela']);

				$tj->{$li['join_chave_extrangeira']} = $dadosReg->{$li['campo_tabela']};

				$tj->load();

				

				if(function_exists($li['funcao_exibicao'])){

					//echo $li['funcao_exibicao']($tj[0]['id'],$tj[0][$li['join_campo_exibido']]);

					echo $li['funcao_exibicao']($dadosReg->id,$tj->{$li['join_campo_exibido']},'view');

				}else{

					echo $tj->{$li['join_campo_exibido']};

				}

				

			elseif(!empty($li['mapear_componente']) && is_file('componente/'.$li['mapear_componente'].'.php')):

				

				

				

			

			

				$CLASS_EXIBE_COMPONENTE = Componente__.$li['mapear_componente'];

				if(!class_exists($CLASS_EXIBE_COMPONENTE)){

					include "componente/".$li['mapear_componente'].".php";

				}

				

				$EXIBE_COMPONENTE = new $CLASS_EXIBE_COMPONENTE;

				//passa como parametro o nome da tabela, o id do registro e o valor do campo.

				echo $EXIBE_COMPONENTE->view($defTabela['tabela'],$dadosReg->{$li['campo_tabela']});

							

			elseif($li['type']=='select' && !empty($li['valor'])):

				 $dados_select = explode(',',$li['valor']);

				 echo $dados_select[$dadosReg->{$li['campo_tabela']}];

			else:

			

				if(preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $dadosReg->{$li['campo_tabela']})){

					$value = substr($dadosReg->{$li['campo_tabela']},8,2).'/'.substr($dadosReg->{$li['campo_tabela']},5,2).'/'.substr($dadosReg->{$li['campo_tabela']},0,4).' '.(substr($dadosReg->{$li['campo_tabela']},11,5) != 0?'as '.substr($dadosReg->{$li['campo_tabela']},11,5):'');

				}elseif($li['mascara']=='decimal'){

					$value = number_format($dadosReg->{$li['campo_tabela']},2,',','.');

				}else{

					$value = $dadosReg->{$li['campo_tabela']};

				}

				

				if(function_exists($li['funcao_exibicao'])){

					echo $li['funcao_exibicao']($dadosReg->id,$value,'view');

				}else{

					echo $value;	

				}

			

			endif;

			

			$EXIBE_ITEM = ob_get_clean();

	

			echo '<div id="inside_'.$MAP['tabela'].'_'.$li['campo_tabela'].'" class="system_item_form_LIST">';

				echo '<label>'.$li['nome'].'&nbsp;</label><br />';

				echo $EXIBE_ITEM;

			echo '</div>';

	

			echo '<div class="clear_system  '.($li['linha_separadora'] == 1?'mostra_linha_clear':'').'"></div>';





		}

	endforeach;



	

}



?>