<style type="text/css">
#head_menu_system{ display:none;}
</style>

<script>
$(function(){
	$("#caracteristica").change(function(){
		var val = $(this).val();
		
		if(val == 1){
			$("#value").show();
			$(".join").hide();
		}else if(val == 2){
			$(".join").show();
			$("#value").hide();
		}
		
	})
	
	$("#tabela_join").change(function(){
		$.post('<?=ROOT;?>fn-inputForm',{p1:$(this).val()}, function(a){
		
			$("#opt_input").html(a);
			$("#opt_input2").html(a);
			$("#opt_input2").find("#chave_extrangeira").attr('name','chave_exibe');
		
		})	
			
	
	});
	
	$("#mascara").change(function(){
		if($(this).val() == 'p'){
			$("#mascara_personalizada").show();	
		}else{
			$("#mascara_personalizada").hide();				
		}
	
	});	



	$("#opcAvancada").click(function(){
		
		if($('.optionAdvanced').css('display') == 'none'){
			$('.optionAdvanced').show();
		}else{
			$('.optionAdvanced').hide();
		}
			
	});
	
	$("#adicionar_novo_bt").click(function(){
		conteudo = $("#bt_personalozado").html();
		$("#content_bt_adicional").append(conteudo);	
	
	});

	
})

</script>
<?php

if(!empty($_POST)):
	
	
	
	if($_POST['mascara'] != 'p'){
		$_POST['mascara_personalizada'] = '';
	}else{
		$_POST['mascara'] = '';	
	}
	$dados = array(
		'system_form' => $url[1],
		'nome' => $_POST['nome'],
		'id_input' => $_POST['id'],
		/*'class' => $_POST['class'],*/
		'campo_tabela' => $_POST['campo_tabela'],
		'type' => $_POST['type'],
		'caracteristica' => $_POST['caracteristica'],
		'valor' => $_POST['value'],
		'join_tabela' => $_POST['tabela_join'],
		'join_chave_extrangeira' => $_POST['chave_extrangeira'],
		'join_campo_exibido' => $_POST['chave_exibe'],
		'sql_adicional' => base64_encode($_POST['sql_adicional']),
		'exb_cadastro' => $_POST['exb_cadastro']?$_POST['exb_cadastro']:0,
		'exb_edicao' => $_POST['exb_edicao']?$_POST['exb_edicao']:0,
		'exb_listagem' => $_POST['exb_listagem']?$_POST['exb_listagem']:0,
		'exb_filtro' => $_POST['exb_filtro']?$_POST['exb_filtro']:0,
		'exb_view' => $_POST['exb_view']?$_POST['exb_view']:0,
		'edicao_restrita' => $_POST['edicao_restrita'],
		'validacao' => $_POST['validacao'],
		'aba' => $_POST['aba'],
		'mapear_componente' => $_POST['componente'],
		'parametros_componente' => $_POST['parametros_componente'],
		'funcao_exibicao' => $_POST['funcao_exibicao'],
		/*'linha_separadora' => $_POST['linha_separadora'],*/
		'mascara' => $_POST['mascara'],
		'mascara_personalizada' => $_POST['mascara_personalizada']
	);

	if($url[2]==''):

		$busca_ordem = $q->read("system_inputs","system_form = '".$url[1]."'", null,null,"ordem DESC");
		$dados['ordem'] = $busca_ordem[0]['ordem'] + 1;
	
	endif;

	
	if(!empty($_POST['ordem'])):
		$da_confere = $q->read("system_inputs", "id = '".$url[2]."'");
		
		if($da_confere[0]['ordem'] != $_POST['ordem']):
			$da = $q->read("system_inputs","system_form = '".$url[1]."'");
			foreach($da as $list_da):
				
				$q->update("system_inputs",array('ordem' => ($list_da['ordem'] + 1)),"id = '".$list_da['id']."'");
			
			endforeach;
		endif;
	endif;
	
	

	if(!empty($url[2])):
		$q->update("system_inputs",$dados,"id = '".$url[2]."'");
	else:
		$q->insert("system_inputs", $dados);
	endif;
	
	?>
	<script>
    	window.parent.location.href = '<?=ROOT?>system-formInput/<?=$url[1]?>/'; 
	</script>
	<?php
	exit();	
endif;

/*
--- =============================
--- =============================
--- =============================
--- =============================
*/


$dados = $q->read('system_form', 'id = "'.$url[1].'"');

if(!empty($url[2])):
	$l = $q->read("system_inputs", "id = '".$url[2]."'");
endif;



if($l[0]['caracteristica'] == 2):
?>
<style type="text/css">
#value{ display:none;}
.join{ display:table-row;}
</style>
<?php endif;?>
<div class="opc_avancada" id="opcAvancada" title="Opções Avançadas"><img src="<?php echo ROOT?>system/img/opc_avancadas_ico.png" /></div>

<form method="post">
<table align="center" width="700px" cellspacing="0">
	<tr>
    	<td width="200"><label>Exibir:</label></td>
        <td>
        	<input name="exb_cadastro" type="checkbox" value="1" <?php echo (empty($url[2])?'checked="checked"':'')?>  <?php if($l[0]['exb_cadastro'] == 1){echo 'checked="checked"';}?> />Cadastro
            &nbsp;&nbsp;
        	<input name="exb_edicao" type="checkbox" value="1" <?php echo (empty($url[2])?'checked="checked"':'')?>  <?php if($l[0]['exb_edicao'] == 1){echo 'checked="checked"';}?> />Edição
            &nbsp;&nbsp;
        	<input name="exb_listagem" type="checkbox" value="1" <?php echo (empty($url[2])?'checked="checked"':'')?>  <?php if($l[0]['exb_listagem'] == 1){echo 'checked="checked"';}?> />Listagem
            &nbsp;&nbsp;
        	<input name="exb_view" type="checkbox" value="1" <?php echo (empty($url[2])?'checked="checked"':'')?>  <?php if($l[0]['exb_view'] == 1){echo 'checked="checked"';}?> />Visualizar
            &nbsp;&nbsp;
        	<input name="exb_filtro" type="checkbox" value="1" <?php echo (empty($url[2])?'checked="checked"':'')?>  <?php if($l[0]['exb_filtro'] == 1){echo 'checked="checked"';}?> />Filtro
        </td>
    </tr>
    
	<tr>
    	<td> <label>Campo da tabela</label></td>
    	<td>
        	<select name="campo_tabela" id="type">
            	<option value=""></option>
				<?php
                $inputs = $q->listaCampos($dados[0]['tabela']);
				foreach($inputs as $list):
					
					echo '<option '.selected($list['Field'],$l[0]['campo_tabela']).' value="'.$list['Field'].'">'.$list['Field'].'</option>';
				
				endforeach;
				?>                
            </select>
        </td>
    </tr>
    
    <? /*
    
	<tr>
    	<td width="150"><label>Inserir antes de:</label></td>
        <td>
        	<select name="ordem">
            	<option value="<?php echo $l[0]['ordem']?>"></option>
        		<?php
                $ordem = $q->read("system_inputs","system_form = '".$url[1]."'",null,null,"ordem");
				foreach($ordem as $list_ordem): 
					echo '<option  value="'.$list_ordem['ordem'].'">'.$list_ordem['nome'].'</option>';
				endforeach;
				?>
            
            </select>
        </td>
    </tr>
	*/ ?>
	
	<tr>
    	<td width="150"><label>Nome</label></td>
        <td><input type="text" name="nome" value="<?=$l[0]['nome'];?>" /></td>
    </tr>
	<tr>
    	<td> <label>Tipo</label></td>
    	<td>
        	<select name="type" id="type">
            
            	<option <?=selected('text',$l[0]['type'])?> value="text">Text</option>
            	<option <?=selected('textarea',$l[0]['type'])?> value="textarea">Textarea</option>
            	<option <?=selected('password',$l[0]['type'])?> value="password">Password</option>
            	<option <?=selected('checkbox',$l[0]['type'])?> value="checkbox">Checkbox</option>
            	<option <?=selected('file',$l[0]['type'])?> value="file">File</option>
            	<option <?=selected('image',$l[0]['type'])?> value="image">Image</option>
            	<option <?=selected('radio',$l[0]['type'])?> value="radio">Radio</option>
            	<option <?=selected('hidden',$l[0]['type'])?> value="hidden">Hidden</option>
            	<option <?=selected('select',$l[0]['type'])?> value="select">Select</option>
            	<option <?=selected('submit',$l[0]['type'])?> value="submit">Submit</option>
            	<option <?=selected('button',$l[0]['type'])?> value="button">Button</option>
            </select>
        </td>
    </tr>
    
	<tr>
    	<td> <label>Validação</label></td>
    	<td>
        	<select name="validacao" id="validacao">
            
            	<option value="">Sem validação</option>
            	<option <?=selected('text',$l[0]['validacao'])?> value="text">Texto obrigatório</option>
            	<option <?=selected('select',$l[0]['validacao'])?> value="select">Select obrigatório</option>
            	<? /*<option <?=selected('password',$l[0]['validacao'])?> value="password">Password</option>
            	<option <?=selected('checkbox',$l[0]['validacao'])?> value="checkbox">Checkbox</option>
            	<option <?=selected('file',$l[0]['validacao'])?> value="file">File</option>
            	<option <?=selected('image',$l[0]['validacao'])?> value="image">Image</option>
            	<option <?=selected('radio',$l[0]['validacao'])?> value="radio">Radio</option>
            	<option <?=selected('hidden',$l[0]['validacao'])?> value="hidden">Hidden</option>
            	<option <?=selected('select',$l[0]['validacao'])?> value="select">Select</option>*/?>

            </select>
        </td>
    </tr>
    
	<tr  class="optionAdvanced">
    	<td width="150"><label>Aba:</label></td>
        <td>
        	<input name="aba" type="text" value="<?php echo $l[0]['aba'];?>" /><br /><span style=" font-size:10px;">Aba onde será exibido este item. Deixe vazio para não criar abas.</span>
        </td>
    </tr>
	<tr  class="optionAdvanced">
    	<td width="150"><label>Edição restrita:</label></td>
        <td>
        	<select name="edicao_restrita">
            	<option <?=selected(0,$l[0]['edicao_restrita'])?>  value="0">Não</option>
            	<option <?=selected(1,$l[0]['edicao_restrita'])?> value="1">Sim</option>
            </select>
            <br /><span style=" font-size:10px;">Aba onde será exibido este item. Deixe vazio para não criar abas.</span>
        </td>
    </tr>


    
	<tr  class="optionAdvanced" style=" background:#e4e4e4;">
    	<td width="150"><label>Função de Exibição:</label></td>
        <td>
        	<input name="funcao_exibicao" type="text" value="<?php echo $l[0]['funcao_exibicao'];?>" /><br /><span class="legenda">Nome de uma função para formatar os dados na exibição. Recebe como paramentro o id e o valor do campo. ( $id,$valor )</span>
        </td>
    </tr>
    
    
    
    
    
    
	<tr  class="optionAdvanced">
    	<td width="150"><label>Mapear Página</label></td>
        <td>
                    
            <select  name="componente">   
            <option></option>         
            <?php
            $diretorio = dir("componente/");
            while($arquivo = $diretorio->read()){
              if(is_file("componente/".$arquivo)):
			  	$arquivo = str_replace('.php','',$arquivo);
                echo '<option '.($l[0]['mapear_componente']==$arquivo?'selected="selected"':'').' value="'.$arquivo.'">'.$arquivo.'</option>';
              endif;
            }
            ?>            
            </select>
            
        	<br /><span style=" font-size:10px;">Carrega uma página no lugar do input.<br />A página deve estar dentro a pasta "componente". </span>
        </td>
    </tr>
    

	<tr  class="optionAdvanced">
    	<td width="150"><label>Mapear Página(parametros)</label></td>
        <td>
                    
            <textarea style=" width:100px; height:100px;" name="parametros_componente"><?php echo $l[0]['parametros_componente']?></textarea>
            
        	<br /><span style=" font-size:10px;">Paramentos que serão passados para o componente. Um parametros por linha. Ex.: id=1 </span>
        </td>
    </tr>
    




	<tr  class="optionAdvanced">
    	<td width="150"><label>Identificador (id)</label></td>
    	<td><input type="text" name="id" value="<?=$l[0]['id_input'];?>" /></td>
    </tr>
	<!--tr class="optionAdvanced">
    	<td width="150"><label>Class</label></td>
    	<td><input type="text" name="class" value="<?=$l[0]['class'];?>" /></td>
    </tr-->
	<tr  class="optionAdvanced">
    	<td><label>Caracteristica</label></td>
    	<td>
        	<select name="caracteristica" id="caracteristica">
            	<option <?=selected('1',$l[0]['caracteristica'])?> value="1">Simples</option>
            	<option <?=selected('2',$l[0]['caracteristica'])?> value="2">Join</option>
        	</select>
        </td>
    </tr>
    
    
	<tr id="value"  class="optionAdvanced">
    	<td><label>Valor</label></td>
    	<td>
        	<input type="text" name="value" value="<?=$l[0]['valor']?>" />
        </td>
  
    </tr>
	<!--tr id="linha"  class="optionAdvanced">
    	<td><label>Linha</label></td>
    	<td>
        	<input type="checkbox" name="linha_separadora" value="1" <?=($l[0]['linha_separadora']==1?'checked="checked"':'')?>  /><span class="legenda">Insere uma linha de separação após o campo</span>
        </td>
  
    </tr-->


	<tr  class="join">
    	<td><label>Tabela</label></td>
    	<td>
        	<select name="tabela_join" id="tabela_join">
            	<option value=""></option>
				<?php
                $a = $q->listaTabela();
                foreach($a as $lt){
                    //list($v,$b) = each($lt);
                    $b = current($lt);
                    echo '<option '.selected($b,$l[0]['join_tabela']).' value="'.$b.'">'.$b.'</option>';	
                }
                ?>        
        	</select>
        </td>
  
    </tr>
	<tr class="join">
    	<td><label>Chave Extrangeira</label></td>
    	<td id="opt_input">
        	<?php
            
			if($l[0]['caracteristica'] == 2 && $l[0]['join_chave_extrangeira'] != '')
			inputForm($l[0]['join_tabela'],$l[0]['join_chave_extrangeira'])
			?>
        </td>
    </tr>
	<tr class="join">
    	<td><label>Campo exibido</label></td>
    	<td id="opt_input2">
        	<?php
            if($l[0]['caracteristica'] == 2 && $l[0]['join_campo_exibido'] != '')
			inputForm($l[0]['join_tabela'],$l[0]['join_campo_exibido'],'chave_exibe')
			?>
        </td>
    </tr>
	<tr class="join">
    	<td><label>SQL Adicional</label></td>
    	<td id="opt_input2">
        	<textarea name="sql_adicional"><?=$l[0]['sql_adicional']?base64_decode($l[0]['sql_adicional']):''?></textarea>
        </td>
    </tr>
    
    
    
    
    <?php
    if($l[0]['mascara'] != '' && $l[0]['mascara'] != 'tel' && $l[0]['mascara'] != 'data' && $l[0]['mascara'] != 'rg' && $l[0]['mascara'] != 'cpf' && $l[0]['mascara'] != 'cnpj' && $l[0]['mascara'] != 'decimal'&&$l[0]['mascara'] != 'cep'):
		
		$personaliado = 'p';
	else:
		$personaliado = '';	
	endif;
	?>
    
    
    
	<tr  class="optionAdvanced">
    	<td><label>Mascara</label></td>
    	<td>
       
        	<select name="mascara" id="mascara">
            	<option <?=selected('',$l[0]['mascara'])?> value="">Nenhuma</option>
            	<option <?=selected('tel',$l[0]['mascara'])?> value="tel">Telefone</option>
            	<option <?=selected('data',$l[0]['mascara'])?> value="data">Data</option>
            	<option <?=selected('hora',$l[0]['mascara'])?> value="hora">Hora</option>
            	<option <?=selected('rg',$l[0]['mascara'])?> value="rg">RG</option>
            	<option <?=selected('cpf',$l[0]['mascara'])?> value="cpf">CPF</option>
            	<option <?=selected('cnpj',$l[0]['mascara'])?> value="cnpj">CNPJ</option>
            	<option <?=selected('decimal',$l[0]['mascara'])?> value="decimal">Decimal</option>
            	<option <?=selected('cep',$l[0]['mascara'])?> value="cep">CEP</option>
            	<option <?=selected('p',$personaliado)?> value="p">Personalizado</option>
            </select>
        </td>
    </tr>
	<tr id="mascara_personalizada" <?php if($personaliado == 'p'){echo 'style="display:table-row"';}?>>
    	<td><label>Mascara Personalizada</label></td>
    	<td>
        	<input type="text" name="mascara_personalizada" value="<?=$l[0]['mascara_personalizada'];?>" />
        </td>
    </tr>

	<tr>
    	<td></td>
    	<td>
        	<input type="submit" value="Cadastrar"  />
        </td>
    </tr>

</table>
</form>