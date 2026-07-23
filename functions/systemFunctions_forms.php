<?php
/*
--- Gera um formulario com os dados gravados no banco de dados.
*/
function loadForm($id,$edit=null,$joinnn_item=null){
	
	
	
	$q = new Model;
	/*Solicita o arquivo de definições da tabela*/
	require("tables/def_".$id.".php");
	$dados[0] = $TABLE_DEF;
	$dados_inputs = $TABLE_DEF_INPUT;
	
	
	global $MAP;
	$MAP['row'] = $TABLE_DEF_INPUT;
	/*
	-- abre o formulario para edição
	*/
	
	if(!empty($edit)):
		$dados_edita = $q->read($dados[0]['tabela'],"id = '".$edit."'");
		$list_edt = $dados_edita[0];
	else:
		$dados_edita = null;
	endif;
	
	
	
	//define a "action" do formulario. Caso nenhuma action tenha sido definida, atribui a action padrão
	if(!empty($dados[0]['action']) && empty($edit)):
		$action = $dados[0]['action'];
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
	$abas = $dados[0]['system_form_abas_list'];	
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
	
	
	$ID_FORM_LIST = ($dados[0]['id_form']!=''?$dados[0]['id_form']:($dados[0]['tabela']!=''?'form_'.$dados[0]['tabela']:'formid_'.$dados[0]['id']));
	?>

	
	
	
	<form name="<?php echo $ID_FORM_LIST;?>" id="<?php echo $ID_FORM_LIST;?>" method="<?php echo $dados[0]['method']?>" action="ROOT/action-<?php echo $action?>" class="formgeral <?php echo $dados[0]['class'];?>" enctype="multipart/form-data" onsubmit="return verificaValidacao(this.id)">
	<input type="hidden" name="formId" value="<?php echo $id;?>" />
    
    <?php global $url;?>
    <input type="hidden" name="onsucesso" value="
		
		<?php 
			if($_SESSION['onsucesso'] != ''){
				$redirect = $_SESSION['onsucesso'];
				unset($_SESSION['onsucesso']);
				echo $redirect;
			}else{
				echo ($dados[0]['url_retorno']!=''?$dados[0]['url_retorno']:ROOT.'adm-'.str_replace('_info','',$url[0]).'/'.$url[1].'/'.$url[2].'/');
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
	
	if(($li['exb_cadastro'] == 1 && empty($edit)) || ($li['exb_edicao'] == 1 && !empty($edit))):
		
		$MAP['linha_input'] = $list_edt[$li['campo_tabela']];
		
		$MAP['campo_tabela'] = $li['campo_tabela'];
		
		
		$INPUT_ID = ($li['id_input']!=''?$li['id_input']:($li['campo_tabela']!=''?$li['campo_tabela']:'idinput_'.$li['id']));
		
		?>
            <div id="<?php echo $ID_FORM_LIST.'_'.$INPUT_ID?>" class="system_item_form <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}elseif($li['type'] == 'submit'){ echo '';}else{echo 'aba_geral';}?>">
            <?php
            if(!empty($li['mapear_componente'])):
            
                if(is_file('componente/'.$li['mapear_componente'].'.php')):
					if($li['parametros_componente']!=''){
						$paramC = explode("\n",$li['parametros_componente']);
						if(is_array($paramC)){
							$PARAM = array();
							foreach($paramC as $p){
								
								$itP = explode('=',$p);
								$PARAM[$itP[0]]=$itP[1];
								
							}
						}
					}
					
                    include 'componente/'.$li['mapear_componente'].'.php';
                else:
                    echo 'Erro ao mapear componente <strong>'.$li['mapear_componente'].'.php</strong>';
                endif;
            
            else:
            ?>
            
            
            
                <?php if($li['type'] != 'hidden' && $li['type'] != 'submit' &&  $li['type'] != 'button'): ?>
                    <label for="<?php echo $INPUT_ID;?>"><?php echo $li['nome']?></label>
                    <br />
                <?php elseif($li['type'] == 'submit' || $li['type'] == 'buttom'): ?>
                    <label for="<?php echo $INPUT_ID;?>">&nbsp;</label>
                    <br />
                <?php endif;?>
                
                
                <?php if($li['type'] == 'select'):?>
                
                <?php
                /*
                --- verifica se o campo deve ser uma busca dinamica
                */
                global $listapar;
                ?>
                <?php if(in_array($dados[0]['tabela'].'----'.$li['campo_tabela'],$listapar)):?>
                
                
						<script>
                        $(function(){
                            $("#<?php echo $INPUT_ID.'_LSTP'?>").keyup(function(){
                                val = $(this).val();
								box = $(this).parent('.system_item_form ');
								box.css('z-index',2);
                                
                                $.post('<?=ROOT?>fn-carrega_lista',{p0:'<?=$dados[0]['tabela']?>',p1:'<?=$li['campo_tabela']?>',p2:val},function(a){
                                    
                                    
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
                
                        <select name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>" id="<?php echo $INPUT_ID?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>">
                            <option value="-1">Selecione...</option>
                            <?php
                            if($li['caracteristica'] == 2):
                                $dados_join = $q->read($li['join_tabela']);
                                foreach($dados_join as $list_join):
                                    if($list_join[$li['join_chave_extrangeira']] == $list_edt[$li['campo_tabela']]){
                                        $sele = 'selected="selected"';	
                                    }else{
                                        $sele = '';
                                    }
                                
                                    echo '<option '.$sele.' value="'.$list_join[$li['join_chave_extrangeira']].'">'.$list_join[$li['join_campo_exibido']].'</option>';
                                endforeach;
                            else:
                                if(!empty($li['valor'])):
                                    $dados_select = explode(',',$li['valor']);
                                    for($i = 0; $i<count($dados_select); $i++):
                                    
                                        if($i == $list_edt[$li['campo_tabela']] && $edit != ''){
											
                                            $sele = 'selected="selected"';	
											
                                        }else{
                                            $sele = '';
                                        }
                                        
                                        echo '<option '.$sele.' value="'.$i.'">'.$dados_select[$i].'</option>';
                                    
                                    endfor;
                                endif;
                            endif;
                            ?>
                        </select>
                        
                        
                   <?php endif;?>     
                        
                <?php elseif($li['type'] == 'checkbox'):?>
                <?php
						if($li['caracteristica'] == 2):
							$dados_join = $q->read($li['join_tabela']);
							
							$resp = unserialize($list_edt[$li['campo_tabela']]);
							
							foreach($dados_join as $list_join):
								
								?>
								<input type="checkbox" 
								<?=(is_array($resp) && in_array($list_join[$li['join_chave_extrangeira']],$resp)?'checked="checked"':'')?>  
								value="<?=$list_join[$li['join_chave_extrangeira']]?>" 
								name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>[]" 
								id="<?php echo $INPUT_ID?>"  
								/>
								<?php echo $list_join[$li['join_campo_exibido']]?><br />
								<?
							endforeach;
						else:
						
							if(!empty($li['valor'])):
								$dados_select = explode(',',$li['valor']);
								$resp = array();
								$resp_u = unserialize( $list_edt[$li['campo_tabela']]);
								if(!empty($resp_u))
								$resp = $resp_u;
								
							
								for($i = 0; $i<count($dados_select); $i++):
							   
									?>
									<input type="checkbox" <?=(in_array($i,$resp)?'checked="checked"':'')?>  value="<?=$i?>" name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>[]" id="<?php echo $INPUT_ID?>"  /><?php echo $dados_select[$i]?>
									<?
								endfor;
							endif;
						endif;
								?>



                <?php elseif($li['type'] == 'textarea'):?>
                
                    <textarea name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>" id="<?php echo $INPUT_ID?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"><?php echo $list_edt[$li['campo_tabela']]?></textarea>
                 
                <?php elseif($li['type'] == 'image'):?>
                
                        <input type="<?php echo $li['type']?>"  name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>" id="<?php echo $INPUT_ID?>" value="<?php if(empty($edit)){echo $li['valor'];}else{echo $list_edt[$li['campo_tabela']];}?>" src="<?php echo ROOT.$li['valor']?>"  class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"   />
                
                <?php elseif($li['type'] == 'button'):?>
                
                        <input type="button"  name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>" id="<?php echo $INPUT_ID?>" value="<?php echo $li['valor'];?>"  class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"   />
                
                <?php else:
                        if($li['caracteristica'] == 2):
                            $dados_join = $q->read($li['join_tabela']);
                            foreach($dados_join as $list_join):
                                ?>
                                <div class="system_item_join">
                                <input type="<?php echo $li['type']?>"  name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>[]" id="<?php echo $INPUT_ID.'_'.$list_join['id']?>" value="<?php echo $list_join[$li['join_chave_extrangeira']]?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"  /><?php echo $list_join[$li['join_campo_exibido']];?>
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
                
                		
                        
                        <input type="<?php echo $li['type']?>" class="<?php echo $li['class']?> <?php echo($li['mascara']!=''?'mask_type_'.$li['mascara']:'')?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>" name="<?php echo (($li['campo_tabela'])!=''?$li['campo_tabela']:'idinput_'.$li['id'])?>"   id="<?php echo $INPUT_ID?>" 
                         value="<?php echo $value;?>" />
                        
                
                <?php
                
                        endif;
                    endif;
                    
                ?>
            <?php endif;?>    
            </div>
            <div class="clear_system  <?php echo ($li['linha_separadora'] == 1?'mostra_linha_clear':'')?> clear_system_<?=$li['id']?> <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}else{echo 'aba_geral';}?>"  ></div>
		<?php	
		endif;
	endforeach;
	
	
	
	?>
	</form>
	<?php
	
}


/*
 -- lista os dados de um determinado formulario criado atravez do sistema.
*/


function listForm($id,$ordem = null,$pagina_form = null,$menu_list = 0,$startPaginacao = 0,$QUERY_FILTRO=null){
	
	if($QUERY_FILTRO ==''):
		$QUERY_FILTRO = '(1=1)';
	endif;
	
	$q = new Model;
	
	/*SOLICITA O ARQUIVO DE DEFINIÇÕES DA TABELA*/
	
	require("tables/def_".$id.".php");
	
	$dados[0] = $TABLE_DEF;
	$tabelaListada = $dados[0]['tabela'];
	
	$dados_inputs = $TABLE_DEF_INPUT;
	
	
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
	$contaResultados = count($q->read($dados[0]['tabela'],"(1=1 ".(($sql_adicional != ''?" AND (".$sql_adicional.")":"")).") AND ".$QUERY_FILTRO.""));
	
	
	$quantidadePorPagina = 50;
	$numeroPaginas = $contaResultados / $quantidadePorPagina;
	$offsetPagina = $startPaginacao * $quantidadePorPagina;

	
	if($contaResultados == 0):
		echo '<div class="no_result">Nenhum resultado encontrado</div>';
	else:
		
		/*-------------------------------------------------------*/
		if(function_exists($dados[0]['pre_listagem'])):
			echo $dados[0]['pre_listagem']($id);
		endif;
		/*guarda todo o form em uma variavel*/
		ob_start();
		
		if(verificaCampoTabela($tabelaListada,'order_by')){
			$ordem = 'order_by';
		?>
		<script>
		
		
		$(function(){
			$('.tableList tbody').sortable({
				  revert: true,
				  beforeStop: function( event, ui ) {
					  var ordemLinha = 0;
				      var enviaOrdem = '';
					  $('.tableList tbody').find('tr').each(function(){
						 
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
        <table class="tableList" cellpadding="0" cellspacing="0">
			<?php
            ob_start();
			?>
            <tbody>
				<?php
				
				$lista_dados = $q->read($dados[0]['tabela'],"(1=1 ".(($sql_adicional != ''?" AND (".$sql_adicional.")":"")).") AND (".$QUERY_FILTRO.")",$quantidadePorPagina,$offsetPagina, $ordem);
				
				
					$l = 0;
					if(count($lista_dados)>0)
					foreach($lista_dados as $ld):
						$idRegistroAtual = $ld['id'];
					
					$row = $l%2;
					if($row == 0){ $class = 'odd';}else{$class = 'even';}
					
					$l +=1;
				
				?>
				<tr class="<?php echo $class;?>" reg="<?=$ld['id']?>">
					<?php
					$first = 0;
					
					if($dados[0]['checkbox'] == 1):
					$first ++;
					?>
                    <!--[PDF-OFF-->
						<td width="20px">
						
							<?php 
							if(function_exists($dados[0]['condicao_checkbox'])):
								$exbCheckboxSys = $dados[0]['condicao_checkbox']($ld['id']);	
							else:
								$exbCheckboxSys = true;
							endif;
							
							if($exbCheckboxSys == true):
							?>
							<input type="checkbox" name="listItensForm[]"  value="<?php echo $ld['id']?>" />
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
					//if(count($dados_inputs)>0)
					
					foreach($dados_inputs as $li):
					$MAP['itemList_'.$li['campo_tabela']] = $ld[$li['campo_tabela']];
					$MAP['linha_input'] = $ld[$li['campo_tabela']];
					if($li['exb_listagem']==0)continue;
					?>
				  
					<td <?php if($first == 0){echo 'class="first"';}?>>
					
						<?php
						
						//echo '<br>'.$li['join_tabela'];
						if($li['join_tabela']!=""):
							
							$tj = $q->read($li['join_tabela'],$li['join_chave_extrangeira']." = '".$ld[$li['campo_tabela']]."'");
							if(function_exists($li['funcao_exibicao'])){
								//echo $li['funcao_exibicao']($tj[0]['id'],$tj[0][$li['join_campo_exibido']]);
								echo $li['funcao_exibicao']($ld['id'],$tj[0][$li['join_campo_exibido']]);
							}else{
								echo $tj[0][$li['join_campo_exibido']];
							}
							
						elseif(!empty($li['mapear_componente']) && is_file('componente/'.$li['mapear_componente'].'.php')):
							
							$li['mapear_componente'];
							$TIPO_EXIBE = 'list';
							
							include "componente/".$li['mapear_componente'].".php";
						
						elseif($li['type']=='select' && !empty($li['valor'])):
							 $dados_select = explode(',',$li['valor']);
							 echo $dados_select[$ld[$li['campo_tabela']]];
						else:
						
				
							if(preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $ld[$li['campo_tabela']])){
								$value = substr($ld[$li['campo_tabela']],8,2).'/'.substr($ld[$li['campo_tabela']],5,2).'/'.substr($ld[$li['campo_tabela']],0,4).' '.(substr($ld[$li['campo_tabela']],11,5) != 0?'as '.substr($ld[$li['campo_tabela']],11,5):'');
							}elseif($li['mascara']=='decimal'){
								$value = number_format($ld[$li['campo_tabela']],2,',','.');
							}else{
								$value = $ld[$li['campo_tabela']];
							}
							//echo $ld[$li['campo_tabela']];
							/*
							echo '<pre>';
							print_r($li);
							echo '</pre>';
							
							*/
							if(function_exists($li['funcao_exibicao'])){
								
								echo $li['funcao_exibicao']($ld['id'],$value);
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
					/*
					echo '<pre>';
					print_r($MAP);
					echo '</pre>';
					*/
					if($dados[0]['editar'] != '1' && in_array($MAP['id_form_list_menu'],$PERFIL_PERMISSOES['edit'])):
						$fumCont++;
						?>
						<td align="center" class="noPDF  <?php echo ($fumCont==1?"listBtsFirst":'listBts')?>" width="30" style="padding:5px 5px 5px 5px ;"><a href="ROOT/adm-<?=$pagina_form?>/<?=$url[1]?>/<?=$menu_list?>/<?=$ld['id']?>"><img src='ROOT/images/admin/bt_edit.png' title="Editar Item" alt="Editar"/></a></td>
						<?php
					endif;
					if($dados[0]['visualizar'] != '1' && in_array($MAP['id_form_list_menu'],$PERFIL_PERMISSOES['view'])):
						$fumCont++;
						?>
						<td align="center" class="noPDF <?php echo ($fumCont==1?"listBtsFirst":'listBts')?>" width="30" style="padding:5px 5px 5px 5px ;"><a href="ROOT/adm-<?=$MAP['id_form_list_menu']?>_view?view=<?=$ld['id']?>"><img src='ROOT/images/admin/bt_view.png' title="Visualizar Item" alt="Visualizar" /></a></td>
						<?php
					endif;
					
					if($dados[0]['deletar'] != '1' && in_array($MAP['id_form_list_menu'],$PERFIL_PERMISSOES['del'])):
						$fumCont++;
						?>
						<td align="center" class="noPDF <?php echo ($fumCont==1?"listBtsFirst":'listBts')?>" width="30" style="padding:5px 5px 5px 5px ;"><a onclick="conf('Tem certeza que deseja deletar este item?',
                        
                        function(){
                        wait();
                        location.href = 'ROOT/action-delete_global/<?=$id?>/<?=$ld['id']?>'
                        }
                       
                        
                        
                        );"><img src='ROOT/images/admin/bt_del.png' title="Deletar Item" align="Deletar" /></a></td>
						<?php
					endif;
					$btAdicional = $dados[0]['botoes_adicionais'];
					
					if(in_array($MAP['id_form_list_menu'],$PERFIL_PERMISSOES['bt_adicional'])):
					
						/*Join N x N*/
						$btjoin_n_n = $dados[0]['join_n_n'];
						
						if(is_array($btjoin_n_n))
						for($i=0;$i<count($btjoin_n_n['nome_bt']);$i++):
							if($btjoin_n_n['nome_bt'][$i]!=''){
								$fumCont++;
								if(is_file("tables/def_".$btjoin_n_n['tabela_join'][$i].".php") && $linkTbJoin == ''){
									require_once("tables/def_".$btjoin_n_n['tabela_join'][$i].".php");
									$tb_joinnn = $TABLE_DEF;
									$linkTbJoin = $tb_joinnn['link'];
								}

								$qtdJoin = DB::read($tb_joinnn['tabela']);
								$qtdJoin->$btjoin_n_n['chave_estrangeira'][$i] = $ld[$btjoin_n_n['chave_primaria'][$i]];
								$qtdJoin->load();
								
								if($qtdJoin->size()>0){
									$qtdJoinExb = ' ('.$qtdJoin->size().')';
								}else{
									$qtdJoinExb = '';
								}
								
								echo '<td class="bts_adcl noPDF '.($fumCont==1?"listBtsFirst":'listBts').'" align="center" width="50">
								
								<a href="ROOT/adm-'.$linkTbJoin.'/joinnn/'.$MAP['id_form_list'].'__'.$i.'__'.$ld[$btjoin_n_n['chave_primaria'][$i]].'/0/"><div class="adicionalBtItem">'.$btjoin_n_n['nome_bt'][$i].$qtdJoinExb.'</div></a></td>';
							}
						endfor;
						
						/*-----------------------*/
						if(!empty($btAdicional))
						foreach($btAdicional as $k=>$v):
							if(!empty($v)):
								if(function_exists($v)):
									$exbBtAdicional = $v($ld['id'],$dados[0]['tabela']);
									$fumCont++;
									if($exbBtAdicional != ''){
										$exbBotao = '<div class="adicionalBtItem">'.$exbBtAdicional.'</div>';
									}else{
										$exbBotao = '';
									}
									echo '<td class="bts_adcl noPDF '.($fumCont==1?"listBtsFirst":'listBts').'" align="center" width="50">'.$exbBotao.'</td>';
								endif;
							endif;
						endforeach;
						/* ---------------------- */
					endif;
					?>
                    
                    <!--PDF-OFF]-->
				<tr>
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
					if($dados[0]['checkbox'] == 1):
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
					
						<th <?php if($first == 0){echo 'class="first"';}?>><?php echo $li['nome']?></th>
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
					echo '<div class="itemPaginacaoSys"><a href="'.ROOT.'adm-'.$url[0].'/'.$url[1].'/'.$url[2].'/0">1</a></div>';
					echo '<div class="retListPaginacao"></div>';
				endif;
				for($pg=$startAnterior;$pg<$startProximo;$pg++):
					
					echo '<div class="itemPaginacaoSys '.($pg == $startPaginacao?"itemPaginacaoSysSelect":"").'"><a href="'.ROOT.'adm-'.$url[0].'/'.$url[1].'/'.$url[2].'/'.$pg.'">'.($pg+1).'</a></div>';
				
				endfor;
				if($startProximo<$numeroPaginas):
					echo '<div class="retListPaginacao"></div>';
					echo '<div class="itemPaginacaoSys"><a href="'.ROOT.'adm-'.$url[0].'/'.$url[1].'/'.$url[2].'/'.($numeroPaginas-1).'">'.($numeroPaginas).'</a></div>';
				endif;
			endif;
			?>
		
		</div>
		
		<?php
		if(function_exists($dados[0]['pos_listagem'])):
			echo $dados[0]['pos_listagem']($id);
		endif;
		
	endif;
}

/*
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/

function loadFormInside($id,$edit=null){
	
	$q = new Model;
	
	$q = new Model;
	/*Solicita o arquivo de definições da tabela*/
	require("tables/def_".$id.".php");
	$dados[0] = $TABLE_DEF;
	$dados_inputs = $TABLE_DEF_INPUT;
		
	/*
	-- abre o formulario para edição
	*/
	
	if(!empty($edit)):
	
		$dados_edita = $q->read($dados[0]['tabela'],"id = '".$edit."'");
		$list_edt = $dados_edita[0];
		
	else:
	
		$dados_edita = null;
	
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
    //$abas = $q->read_ds("DISTINCT aba", "system_inputs", "system_form = '".$id."'",null,null,"ordem");
	$abas = $dados[0]['system_form_abas_list'];	

	if(count($abas)>1):
	
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
	?>

	
	<input type="hidden" name="inside_formId" value="<?php echo $id;?>" />
	
    
    <?php if(!empty($edit)):?>
	<input type="hidden" name="inside_formIdEdit" value="<?php echo $edit;?>" />
    <?php endif;?>
    
    
	<?php
	/*
	-- Começa a gerar os campos do formulário -----------------------------------------------------------------------------------------------------------------------------
	*/
	
	foreach($dados_inputs as $li):
	
	if(($li['exb_cadastro'] == 1 && empty($edit)) || ($li['exb_edicao'] == 1 && !empty($edit))):
		?>
            <div id="<?php echo ($dados[0]['id_form']!=''?'inside_'.$dados[0]['id_form']:'inside_'.'formid_'.$dados[0]['id']).'_'.($li['id_input']!=''?$li['id_input']:'idinput_'.$li['id'])?>" class="system_item_form <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}elseif($li['type'] == 'submit'){ echo '';}else{echo 'aba_geral';}?>">
            <?php
            if(!empty($li['mapear_componente'])):
            
                if(is_file('componente/'.$li['mapear_componente'].'.php')):
                    include 'componente/'.$li['mapear_componente'].'.php';
                else:
                    echo 'Erro ao mapear componente <strong>'.$li['mapear_componente'].'.php</strong>';
                endif;
            
            else:
            ?>
            
            
            
                <?php if($li['type'] != 'hidden' && $li['type'] != 'submit'): ?>
                    <label for="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']);?>"><?php echo $li['nome']?></label>
                    <br />
                <?php elseif($li['type'] == 'submit'): ?>
                    <label for="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']);?>">&nbsp;</label>
                    <br />
                <?php endif;?>
                
                
                <?php if($li['type'] == 'select'):?>
                
                <?php
                /*
                --- verifica se o campo deve ser uma busca dinamica
                */
                global $listapar;
                ?>
                <?php if(in_array($dados[0]['tabela'].'----'.'inside_'.$li['campo_tabela'],$listapar)):?>
                
                
                <script>
                $(function(){
                    $("#<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_LSTP'?>").keyup(function(){
                        val = $(this).val();
                        
                        $.post('<?=ROOT?>fn-carrega_lista',{p0:'<?=$dados[0]['tabela']?>',p1:'<?=$li['campo_tabela']?>',p2:val},function(a){
                            
                            
                            $("#<?php echo  'inside_'.$li['campo_tabela'].'_'.($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_LSTPBOX';?>").html(a);
                            
                            $(".select_auto").click(function(){
                                $("#<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id'])?>").val($(this).attr('valor'));
                                $("#<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_LSTP'?>").val($(this).attr('nome'))
                                $("#<?php echo  'inside_'.$li['campo_tabela'].'_'.($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_LSTPBOX';?>").html('');
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
                
                
                <input type="text"  name="<?php echo 'inside_'.$li['campo_tabela']?>_LSTP" id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_LSTP'?>" class="autocomplete_lista_input" value="<?php echo $info_auto[0][$li['join_campo_exibido']]?>"  autocomplete="off"/>
                
                <input type="hidden"  name="<?php echo 'inside_'.$li['campo_tabela']?>" id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id'])?>" value="<?php echo  $list_edt[$li['campo_tabela']]?>" class="<?php echo ($li['validacao']!=''?'validacao_lstp':'')?>" />
                <div class="list_auto_comp" id="<?php echo  'inside_'.$li['campo_tabela'].'_'.($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_LSTPBOX';?>">
                
                
                
                
                
                
                </div>
                <?php else:?>
                
                        <select name="<?php echo 'inside_'.$li['campo_tabela']?>" id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id'])?>"  class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?> "  >
                            <option value="">Selecione...</option>
                            <?php
                            if($li['caracteristica'] == 2):
                                $dados_join = $q->read($li['join_tabela']);
                                foreach($dados_join as $list_join):
                                    if($list_join[$li['join_chave_extrangeira']] == $list_edt[$li['campo_tabela']]){
                                        $sele = 'selected="selected"';	
                                    }else{
                                        $sele = '';
                                    }
                                
                                    echo '<option '.$sele.' value="'.$list_join[$li['join_chave_extrangeira']].'">'.$list_join[$li['join_campo_exibido']].'</option>';
                                endforeach;
                            else:
                                if(!empty($li['valor'])):
                                    $dados_select = explode(',',$li['valor']);
                                    for($i = 0; $i<count($dados_select); $i++):
                                    
                                        if($dados_select[$i] == $list_edt[$li['campo_tabela']]){
                                            $sele = 'selected="selected"';	
                                        }else{
                                            $sele = '';
                                        }
                                        
                                        echo '<option '.$sele.' value="'.$dados_select[$i].'">'.$dados_select[$i].'</option>';
                                    
                                    endfor;
                                endif;
                            endif;
                            ?>
                        </select>
                        
                        
                   <?php endif;?>     
                        
                <?php elseif($li['type'] == 'textarea'):?>
                
                    <textarea name="<?php echo 'inside_'.$li['campo_tabela']?>" id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id'])?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"  ><?php echo $list_edt[$li['campo_tabela']]?></textarea>
                 
                <?php elseif($li['type'] == 'image'):?>
                
                        <input type="<?php echo $li['type']?>"  name="<?php echo 'inside_'.$li['campo_tabela']?>" id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id'])?>" value="<?php if(empty($edit)){echo $li['valor'];}else{echo $list_edt[$li['campo_tabela']];}?>" src="<?php echo ROOT.$li['valor']?>"  class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"   />
                
                <?php else:
                        if($li['caracteristica'] == 2):
                            $dados_join = $q->read($li['join_tabela']);
                            foreach($dados_join as $list_join):
                                ?>
                                <div class="system_item_join">
                                <input type="<?php echo $li['type']?>"  name="<?php echo 'inside_'.$li['campo_tabela']?>[]" id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id']).'_'.$list_join['id']?>" value="<?php echo $list_join[$li['join_chave_extrangeira']]?>" class="<?php echo $li['class']?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"   /><?php echo $list_join[$li['join_campo_exibido']];?>
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
                    
                    if(preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $list_edt[$li['campo_tabela']])){
                        $value = substr($list_edt[$li['campo_tabela']],8,2).'/'.substr($list_edt[$li['campo_tabela']],5,2).'/'.substr($list_edt[$li['campo_tabela']],0,4).' '.substr($list_edt[$li['campo_tabela']],11,8);
                    }elseif($li['mascara']=='decimal'){
                        $value = number_format($list_edt[$li['campo_tabela']],2,',','.');
                    }else{
                        $value = $list_edt[$li['campo_tabela']];
                    }
                }
                
                
                ?>
                
                		<?php
                        if($li['type']!='submit'):
						?>
                        <input type="<?php echo $li['type']?>" class="<?php echo $li['class']?> <?php echo($li['mascara']!=''?'mask_type_'.$li['mascara']:'')?> <?php echo ($li['validacao']!=''?'validacao_'.$li['validacao']:'')?>"  <?php if($li['campo_tabela']!=""){echo 'name="'.'inside_'.$li['campo_tabela'].'"';}else{echo 'name="'.'inside_'.($li['id_input']!=''?$li['id_input']:'idinput_'.$li['id']).'"';}?> id="<?php echo ($li['id_input']!=''?'inside_'.$li['id_input']:'inside_'.'idinput_'.$li['id'])?>" 
                         value="<?php echo $value;?>" />
                         <?php endif;?>
                        
                
                <?php
                
                        endif;
                    endif;
                    
                ?>
            <?php endif;?>    
            </div>
            <div class="clear_system clear_system_<?=$li['id']?> <?php if($li['aba']!=''){ echo 'aba_oculta aba_mostra_'.removeCaracteres($li['aba']);}else{echo 'aba_geral';}?>" <?php echo ($li['linha_separadora'] == 1?'style="display:block; width:100%;"':'')?> ></div>
		<?php	
		endif;
	endforeach;
	

	
}


function viewForm2($idForm,$idReg){
	global $MAP;
	$q = new Model;
	require("tables/def_".$idForm.".php");
	$defTabela = $TABLE_DEF;
	$dados_inputs = $TABLE_DEF_INPUT;
	
	$MAP['row'] = $TABLE_DEF_INPUT;
	
	
	$dadosReg = DB::read($defTabela['tabela']);
	$dadosReg->id = $idReg;
	$dadosReg->load();
	
	
	foreach($dados_inputs as $li):
		if(isset($dadosReg->$li['campo_tabela'])){
			$MAP['itemList_'.$li['campo_tabela']] = $dadosReg->$li['campo_tabela'];
			$MAP['linha_input'] = $dadosReg->$li['campo_tabela'];
			if($li['exb_view']==0)continue;
		
		
		
			ob_start();
			if($li['join_tabela']!=""):
							
				$tj = DB::read($li['join_tabela']);
				$tj->$li['join_chave_extrangeira'] = $dadosReg->$li['campo_tabela'];
				$tj->load();
				
				if(function_exists($li['funcao_exibicao'])){
					//echo $li['funcao_exibicao']($tj[0]['id'],$tj[0][$li['join_campo_exibido']]);
					echo $li['funcao_exibicao']($dadosReg->id,$tj->$li['join_campo_exibido']);
				}else{
					echo $tj->$li['join_campo_exibido'];
				}
				
			elseif(!empty($li['mapear_componente']) && is_file('componente/'.$li['mapear_componente'].'.php')):
				
				$li['mapear_componente'];
				$TIPO_EXIBE = 'view';
				
				include "componente/".$li['mapear_componente'].".php";
			
			elseif($li['type']=='select' && !empty($li['valor'])):
				 $dados_select = explode(',',$li['valor']);
				 echo $dados_select[$dadosReg->$li['campo_tabela']];
			else:
			
				if(preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $dadosReg->$li['campo_tabela'])){
					$value = substr($dadosReg->$li['campo_tabela'],8,2).'/'.substr($dadosReg->$li['campo_tabela'],5,2).'/'.substr($dadosReg->$li['campo_tabela'],0,4).' '.(substr($dadosReg->$li['campo_tabela'],11,5) != 0?'as '.substr($dadosReg->$li['campo_tabela'],11,5):'');
				}elseif($li['mascara']=='decimal'){
					$value = number_format($dadosReg->$li['campo_tabela'],2,',','.');
				}else{
					$value = $dadosReg->$li['campo_tabela'];
				}
				
				if(function_exists($li['funcao_exibicao'])){
					echo $li['funcao_exibicao']($dadosReg->id,$value);
				}else{
					echo $value;	
				}
			
			endif;
			
			$EXIBE_ITEM = ob_get_clean();
	
			echo '<div id="inside_'.$MAP['tabela'].'_'.$li['campo_tabela'].'" class="system_item_form_LIST">';
				echo '<label>'.$li['nome'].'</label><br />';
				echo $EXIBE_ITEM;
			echo '</div>';
	
			echo '<div class="clear_system  '.($li['linha_separadora'] == 1?'mostra_linha_clear':'').'"></div>';


/*
		echo '<pre>';
		print_r($li);
		echo '</pre>';/**/
		}
	endforeach;

	
}
?>