<?php

if(!empty($_POST)):

	$page = removeCaracteres($_POST['nome']);
	$titulo = $_POST['nome'];
	$reg = $url[1];

	
	if($reg != '' && $url[2] != 'copiar'){
		$WHERE = 'id <> '.$reg.'';
	}else{
		$WHERE = '';
	}
	

	$aux = DB::read('system_form');
	$aux->tabela = $_POST['tabela'];
	$aux->load('',$WHERE);

	if($aux->size()==0){
		$arquivo = $_POST['tabela'];	
	}else{
		$arquivo = $_POST['tabela'].'_'.removeCaracteres($_POST['nome']);
	}

 $dados = array(
 	'nome' => $_POST['nome'],
 	'menu' => $_POST['menu'],
 	'legenda' => $_POST['legenda'],
 	'id_form' => $_POST['id_form'],
 	'class' => $_POST['class'],
 	'method' => $_POST['method'],
 	'action' => $_POST['action'],
 	'url_retorno' => $_POST['url_retorno'],
 	'tabela' => $_POST['tabela'],
 	'arquivo_def' => $arquivo,
 	'preinsert' => $_POST['preinsert'],
 	'predelete' => $_POST['predelete'],
 	'preupdate' => $_POST['preupdate'],
 	'posinsert' => $_POST['posinsert'],
 	'posdelete' => $_POST['posdelete'],
 	'posupdate' => $_POST['posupdate'],
 	'pre_listagem' => $_POST['pre_listagem'],
 	'pos_listagem' => $_POST['pos_listagem'],
 	'checkbox' => $_POST['checkbox'],
 	'condicao_checkbox' => $_POST['condicao_checkbox'],
	'item_menu' => $id_menu*1,
	'inserir' => $_POST['permicao_inserir'],
	'editar' => $_POST['permicao_editar'],
	'deletar' => $_POST['permicao_deletar'],
	'visualizar' => $_POST['permicao_visualizar'],
	'pdf' => $_POST['pdf'],
	'join_n_n'=>serialize($_POST['bt_join_n_n']),
	'sql_adicional'=>$_POST['sql_adicional']?base64_encode_checa($_POST['sql_adicional']):'',
	'sql_ordem'=>base64_encode_checa(stripslashes($_POST['sql_ordem'])),
	'botoes_adicionais'=>serialize($_POST['bt_dicional']),
	'listar_pagina'=>($_POST['listar_pagina'])
 );

 
 
 if(empty($url[1])||$_POST['copiar']==1):

	$id_reg = $q->insert('system_form',$dados);
 else:
	$o = $q->update('system_form',$dados,"id = '".$url[1]."'");
	$id_reg = $url[1];

 endif;

 
 

 //copia os itens do formulário, se forn o caso
 if($_POST['copiar']==1):
 	
	$cop = $q->read("system_inputs","system_form = '".$url[1]."'");
	if(count($cop)>0):
		foreach($cop as $copy):
		
			$copy['system_form']=$id_reg;
			unset($copy['id']);
			//print_r($copy);
			echo $q->insert("system_inputs",$copy);
			
		endforeach;
	endif;
 endif;
 
// exit;
/* cria o item de menu se necessario */
 if(!empty($_POST['menu'])):
 
 	$menu = $q->read("admin_menu", "item = '".$_POST['menu']."'");
	if(!empty($menu)):
		$id_menu = $menu[0]['id'];
	else:
		$conta_menu = $q->read("admin_menu");
		$ordem = count($conta_menu) + 1;
		$id_menu = $q->insert("admin_menu",array("item"=>$_POST['menu'], "order_by"=>$ordem));
	endif;
 
 endif;		
 /*cria o item de submenu*/	
 
 	if($url[1] == '' || $_POST['copiar']==1):
		$submenu = $q->read("admin_submenu", "item = '".$_POST['nome']."'");
		
		if(!empty($submenu)):
			$id_sub = $submenu[0]['id'];
		else:
			$conta_submenu = $q->read("admin_submenu");
			$ordem_sub = count($conta_submenu) + 1;
			$id_sub = $q->insert("admin_submenu",array('item'=>$_POST['nome'],'link'=>$page,'form'=>$id_reg,'tabela'=>$_POST['tabela'],'order_by'=>$ordem_sub));
		endif;
	
	else:
		$q->update("admin_submenu",array('item'=>$_POST['nome'],'link'=>$page,'form'=>$id_reg,'tabela'=>$_POST['tabela']),"form = '".$url[1]."'");
		$d = $q->read("admin_submenu","form = '".$url[1]."'");
		$id_sub = $d[0]['id'];
	endif;
	
	
/*vincula o menu ao submenu*/
if(!empty($id_menu)):	
	$conf_menu = $q->read("admin_menu_submenu", "menu = '".$id_menu."' AND submenu = '".$id_sub."'");
	
	if(empty($conf_menu)):
	
		$q->delete("admin_menu_submenu","submenu = '".$id_sub."'");
		$q->insert("admin_menu_submenu",array('menu'=>$id_menu,'submenu'=>$id_sub));
	else:

		$q->update("admin_submenu",array('link'=>$page,'form'=>$id_reg,'tabela'=>$_POST['tabela']),"id = '".$id_sub."'");
		
	endif;
endif;

if($id_sub != '' && $_POST['menu'] == ''){
	$q->delete("admin_menu_submenu","submenu = '".$id_sub."'");
}
/*------*/


	
	
	@header("location:".ROOT."system-form");
	

endif;





/*-----------------------*/
if(!empty($url[1])):
	$d = $q->read("system_form", "id = '".$url[1]."'");
endif;



?>

<script>
$(function(){
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
	
	$("#adicionar_novo_join").click(function(){
		conteudo = $("#bt_join_n_n").html();
		$("#conteudo_join_n_n").append(conteudo);	
	
	});
	
	
});
</script>


<div class="opc_avancada" id="opcAvancada" title="Opções Avançadas"><img src="<?php echo ROOT?>system/img/opc_avancadas_ico.png" /></div>
<form method="post">
	<?php if($url[2]=='copiar'):?>
    <input type="hidden" name="copiar" value="1" />
    <?php endif;?>
    <table width="900" border="0" align="center">
      <tr>
        <td width="200px"><label>Item de Menu</label></td>
        <td><input type="text" name="menu" size="25" value="<?php echo $d[0]['menu']?>" /><?php
        
         $m = $q->read("admin_menu","id = '".$d[0]['item_menu']."'");
         echo $m[0]['item'];
         
         ?></td>
      </tr>
      <tr>
        <td><label>Nome</label></td>
        <td><input type="text" name="nome" size="50" value="<?php echo $d[0]['nome']?>" /></td>
      </tr>
      <tr>
        <td><label>Legenda</label></td>
        <td><input type="text" name="legenda" size="50" value="<?php echo $d[0]['legenda']?>" /></td>
      </tr>
      <tr>
        <td><label>Tabela</label></td>
        <td>

          <?php
          $a = $q->listaTabela();
          ?>

            <select name="tabela">
            	<option value=""></option>
                <?php
                foreach($a as $l){
                    //list($v,$b) = each($l);
                    $b = current($l);
                    
                    if($d[0]['tabela'] == $b)
                    $sele = 'selected="selected"';
                    else
                    $sele = ''; 
                    
                    echo '<option '.$sele.' value="'.$b.'">'.$b.'</option>';	
                }
                ?>        
            </select>
        </td>
      </tr>
      
    
      <tr>
        <td><label>Permitir Inserir</label></td>
        <td>
            <select name="permicao_inserir">
                <option <?php if($d[0]['inserir'] == '0'){ echo 'selected="selected"';}?> value="0">Sim</option>
                <option <?php if($d[0]['inserir'] == '1'){ echo 'selected="selected"';}?> value="1">Não</option>
            </select>    
        </td>
      </tr>
      
      <tr>
        <td><label>Permitir Editar</label></td>
        <td>
            <select name="permicao_editar">
                <option <?php if($d[0]['editar'] == '0'){ echo 'selected="selected"';}?> value="0">Sim</option>
                <option <?php if($d[0]['editar'] == '1'){ echo 'selected="selected"';}?> value="1">Não</option>
            </select>    
        </td>
      </tr>
      
      <tr>
        <td><label>Permitir Deletar</label></td>
        <td>
            <select name="permicao_deletar">
                <option <?php if($d[0]['deletar'] == '0'){ echo 'selected="selected"';}?> value="0">Sim</option>
                <option <?php if($d[0]['deletar'] == '1'){ echo 'selected="selected"';}?> value="1">Não</option>
            </select>    
        </td>
      </tr>
      
      <tr>
        <td><label>Permitir Visualizar</label></td>
        <td>
            <select name="permicao_visualizar">
                <option <?php if($d[0]['visualizar'] == '0'){ echo 'selected="selected"';}?> value="0">Sim</option>
                <option <?php if($d[0]['visualizar'] == '1'){ echo 'selected="selected"';}?> value="1">Não</option>
            </select>    
        </td>
      </tr>
      
    
      <tr>
        <td><label>Exportar para PDF</label></td>
        <td>
            <select name="pdf">
                <option <?php if($d[0]['pdf'] == '0'){ echo 'selected="selected"';}?> value="0">Não</option>
                <option <?php if($d[0]['pdf'] == '1'){ echo 'selected="selected"';}?> value="1">Sim</option>
            </select>    
        </td>
      </tr>
      
      <tr style="background:#e4e4e4;"  class="optionAdvanced">
        <td><label>Botões Personalizados <br /><a id="adicionar_novo_bt" style="color:#09F; cursor:pointer;">( Adicionar novo )</a></label>
        <p style="margin-left:5px; margin-right:5px;">
    
        <span class="legenda">Insira o nome da função que irá gerar o botão sem parênteses.<br /> A função irá receber o "id" de cada item o nome da tabela sendo listada. ($id,$tabela).</span>
        </p>
        </td>
        <td id="content_bt_adicional">
        
        <?php
        $bts = unserialize($d[0]['botoes_adicionais']);
        if(is_array($bts))
        foreach($bts as $k=>$v):
            if(!empty($v)){
        ?>
            <input type="text" name="bt_dicional[]" style="width:350px;" value="<?php echo $v;?>" />
        <?php
            }
        endforeach;
        ?>
        
        <div id="bt_personalozado">
            <input type="text" name="bt_dicional[]" style="width:350px;" value="" />
        </div> 
        
        </td>
      </tr>



      <tr style="background:#e4e4e4;"  class="optionAdvanced">
        <td><label>Join tabela N x N<br /><a id="adicionar_novo_join" style="color:#09F; cursor:pointer;">( Adicionar novo )</a></label>
        <p style="margin-left:5px; margin-right:5px;">
    
        <span class="legenda"></span>
        </p>
        </td>
        <td id="conteudo_join_n_n">
        
        <?php
        $bts = unserialize($d[0]['join_n_n']);


		
		
		
        if(is_array($bts))
        for($i=0;$i<count($bts['nome_bt']);$i++):
            if($bts['nome_bt'][$i]!=''){
        ?>
            <div style="border:solid 2px #09F; margin:10px; padding:10px">
               <input type="text" name="bt_join_n_n[nome_bt][]" style="width:350px;" value="<?php echo $bts['nome_bt'][$i]?>" /> Nome do Botão
                <br />
                <input type="text" name="bt_join_n_n[chave_primaria][]" style="width:350px;" value="<?php echo $bts['chave_primaria'][$i]?>" />Chave Primária da tabela atual
                <br />
                
				<select name="bt_join_n_n[tabela_join][]">
                <option value=""></option>
                <?php
                $forms = $q->read("system_form");
				foreach($forms as $listf):
				
				
				echo '<option '.($bts['tabela_join'][$i] == $listf['id']?'selected="selected"':'').' value="'.$listf['id'].'">'.$listf['nome'].'</option>';
				
				endforeach;
				?>      
                </select>   Tabela Join       
                
                <br />
                <input type="text" name="bt_join_n_n[chave_estrangeira][]" style="width:350px;" value="<?php echo $bts['chave_estrangeira'][$i]?>" />Chave Extrangeira da tabela Join
            </div> 
        <?php
            }
        endfor;
        ?>
        
        <div id="bt_join_n_n">
            <div style="border:solid 2px #09F; margin:10px; padding:10px">
               <input type="text" name="bt_join_n_n[nome_bt][]" style="width:350px;" value="" /> Nome do Botão
                <br />
                <input type="text" name="bt_join_n_n[chave_primaria][]" style="width:350px;" value="" />Chave Primária da tabela atual
                <br />
				<select name="bt_join_n_n[tabela_join][]">
                <option value=""></option>
                <?php
                $forms = $q->read("system_form");
				foreach($forms as $listf):
				
					echo '<option  value="'.$listf['id'].'">'.$listf['nome'].'</option>';
				
				endforeach;
				?>      
                </select>   Tabela Join       
                <br />
                <input type="text" name="bt_join_n_n[chave_estrangeira][]" style="width:350px;" value="" />Chave Extrangeira da tabela Join
            </div> 
        </div>
        </td>
      </tr>




      <tr class="optionAdvanced">
        <td colspan="2"><hr></td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>SQL Adicional</label>
        <p style="margin-left:5px; margin-right:5px;">
        <span class="legenda">Apenas código php.<br />Caso seja um simples sql(echo 'código SQL') </span>
        </p>
        </td>
        <td><textarea name="sql_adicional" style=" width:350px; height:50px;"><?php echo base64_decode_checa($d[0]['sql_adicional'])?></textarea></td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>SQL Para Ordenação</label>
        
        <p style="margin-left:5px; margin-right:5px;">
        <span class="legenda">Apenas código php.<br />Caso seja um simples sql(echo 'código SQL'). Não escrever "ORDER BY".</span>
        </p>
        </td>
        <td><textarea name="sql_ordem" style=" width:350px; height:50px;"><?php echo base64_decode_checa($d[0]['sql_ordem'])?></textarea></td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Identificador</label></td>
        <td><input type="text" name="id_form"  value="<?php echo $d[0]['id_form']?>"  size="50" /></td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Classes</label></td>
        <td><input type="text" name="class" size="50"  value="<?php echo $d[0]['class']?>"  /></td>
      </tr>
    
      <tr  class="optionAdvanced">
        <td><label>Method</label></td>
        <td>
            <select name="method">
                <option <?php if($d[0]['method'] == 'post'){ echo 'selected="selected"';}?> value="post">POST</option>
                <option <?php if($d[0]['method'] == 'get'){ echo 'selected="selected"';}?> value="get">GET</option>
            </select>    
        </td>
      </tr>
      
    

      <tr class="optionAdvanced">
        <td><label>Action <span class="legenda">(Opcional)</span></label></td>
        <td><input type="text" name="action" size="50"  value="<?php echo $d[0]['action']?>" /></td>
      </tr>
      <tr  class="optionAdvanced">
        <td><label>Página de retorno <span class="legenda">(Opcional)</span></label></td>
        <td><input type="text" name="url_retorno" size="50"  value="<?php echo $d[0]['url_retorno']?>" /></td>
      </tr>
      <tr  class="optionAdvanced">
        <td><label>Listar página <span class="legenda">(substitui o formulário por uma página)</span></label></td>
        <td><input type="text" name="listar_pagina" size="50"  value="<?php echo $d[0]['listar_pagina']?>" /></td>
      </tr>
      <tr class="optionAdvanced">
        <td colspan="2"><hr></td>
      </tr>
    
      <tr class="optionAdvanced">
        <td><label>Pre Insert</label></td>
        <td><input type="text" name="preinsert" size="50"  value="<?php echo $d[0]['preinsert']?>" /><br />
            <span style="font-size:10px">Função chamada antes uma inserção no banco (Pode configurar o post do formulário)</span>
        </td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Pre Update</label></td>
        <td><input type="text" name="preupdate" size="50"  value="<?php echo $d[0]['preupdate']?>" /><br />
            <span style="font-size:10px">Função chamada antes de uma atualização. (Recebe o id do formulario como parametro. Pode configurar o post do formulário)</span>
        </td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Pre Delete</label></td>
        <td><input type="text" name="predelete" size="50"  value="<?php echo $d[0]['predelete']?>" /><br />
            <span style="font-size:10px">Função chamada antes de uma exclusão do banco.</span>
        </td>
      </tr>
      
      <tr class="optionAdvanced">
        <td colspan="2"><hr></td>
      </tr>
      
      <tr class="optionAdvanced">
        <td><label>Pos Insert</label></td>
        <td><input type="text" name="posinsert" size="50"  value="<?php echo $d[0]['posinsert']?>" /><br />
            <span style="font-size:10px">Função chamada após uma inserção no banco</span>
        </td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Pos Update</label></td>
        <td><input type="text" name="posupdate" size="50"  value="<?php echo $d[0]['posupdate']?>" /><br />
            <span style="font-size:10px">Função chamada após uma atualização no banco</span>
        </td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Pos Delete</label></td>
        <td><input type="text" name="posdelete" size="50"  value="<?php echo $d[0]['posdelete']?>" /><br />
            <span style="font-size:10px">Função chamada após uma exclusão no banco</span>    
        </td>
      </tr>
       <tr  class="optionAdvanced">
        <td colspan="2"><hr></td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Habilitar Checkbox</label></td>
        <td>
        
        <select name="checkbox">
            <option value="0" <?=selected(0,$d[0]['checkbox'])?>>Não</option>
            <option value="1" <?=selected(1,$d[0]['checkbox'])?>>Sim</option>
        </select>
        <br />
            <span style="font-size:10px">Habilita um checkbox para cada item da lista</span>    
        </td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Condição de exibição do checkbox</label></td>
        <td><input type="text" name="condicao_checkbox" size="50"  value="<?php echo $d[0]['condicao_checkbox']?>" /><br />
            <span style="font-size:10px">Função que determina se o checkbox será exibido ou não</span>    
        </td>
      </tr>
      
      
      <tr class="optionAdvanced">
        <td><label>Pré Listagem</label></td>
        <td><input type="text" name="pre_listagem" size="50"  value="<?php echo $d[0]['pre_listagem']?>" /><br />
            <span style="font-size:10px">Função chamada da listagem do formulário</span>    
        </td>
      </tr>
      <tr class="optionAdvanced">
        <td><label>Pós Listagem</label></td>
        <td><input type="text" name="pos_listagem" size="50"  value="<?php echo $d[0]['pos_listagem']?>" /><br />
            <span style="font-size:10px">Função chamada após a listagem do formulário</span>    
        </td>
      </tr>
       <tr >
        <td colspan="2"><hr></td>
      </tr>
    
      <tr>
        <td></td>
        <td><input type="submit"  <?php if(!empty($url[1])):echo ($url[2]=='copiar'? 'value="Copiar"': 'value="Alterar"');else: echo 'value="Criar"';endif; ?>  style="width:80px;" /></td>
      </tr>
    </table>

</form>




