<?php
/*
$TB = DB::read('system_form');
$TB->load('nome');

if($TB->size()>0)do{
	$nomeArquivo = trim(removeCaracteres($DAT_FORM['tabela']));
	$dadosArquivo = "\n".'class '.$nomeArquivo.'{'."\n";
	//define as variaveis da tabela
	$dadosArquivo .= 'static $nome          = '.$TB->nome."; \n"; 
	$dadosArquivo .= 'static $tabela        = '.$TB->tabela."; \n"; 
	$dadosArquivo .= 'static $legenda       = '.$TB->legenda."; \n"; 
	$dadosArquivo .= 'static $identificador = '.$TB->id_form."; \n"; 
	$dadosArquivo .= 'static $classe        = '.$TB->class."; \n"; 
	$dadosArquivo .= 'static $action        = '.$TB->action."; \n"; 
	$dadosArquivo .= 'static $url_retorno   = '.$TB->url_retorno."; \n"; 
	$dadosArquivo .= 'static $arquivo_def   = '.$TB->arquivo_def."; \n"; 
	$dadosArquivo .= 'static $preinsert     = '.$TB->preinsert."; \n"; 
	$dadosArquivo .= 'static $preupdate     = '.$TB->preupdate."; \n"; 
	$dadosArquivo .= 'static $predelete     = '.$TB->predelete."; \n"; 
	$dadosArquivo .= 'static $posinsert     = '.$TB->posinsert."; \n"; 
	$dadosArquivo .= 'static $posupdate     = '.$TB->posupdate."; \n"; 
	$dadosArquivo .= 'static $posdelete     = '.$TB->posdelete."; \n"; 
	$dadosArquivo .= 'static $inserir       = '.$TB->inserir."; \n"; 
	$dadosArquivo .= 'static $deletar       = '.$TB->deletar."; \n"; 
	$dadosArquivo .= 'static $visualizar    = '.$TB->visualizar."; \n"; 
	$dadosArquivo .= 'static $pdf           = '.$TB->pdf."; \n"; 
	$dadosArquivo .= 'static $sql_adicional = '.$TB->sql_adicional."; \n"; 
	$dadosArquivo .= 'static $sql_ordem     = '.$TB->sql_ordem."; \n"; 
	$dadosArquivo .= 'static $pre_listagem  = '.$TB->pre_listagem."; \n"; 
	$dadosArquivo .= 'static $pos_listagem  = '.$TB->pos_listagem."; \n"; 
	$dadosArquivo .= 'static $checkbox      = '.$TB->checkbox."; \n"; 
	$dadosArquivo .= 'static $condicao_checkbox = '.$TB->condicao_checkbox."; \n"; 
	$dadosArquivo .= 'static $listar_pagina     = '.$TB->listar_pagina."; \n"; 
	
	//define as variavei dos campos das tabelas
		$CAMPOS = DB::read('system_inputs');
		$CAMPOS->system_form = $TB->id;
		$CAMPOS->load('nome');
		
		if($CAMPOS->size()>0){do{

			$nomeFuncitonInput = trim(removeCaracteres($CAMPOS['campo_tabela']));
			$dadosArquivo .= 'public function (){'." \n";
			
			$dadosArquivo .= '}'." \n";
			
		}while($AMPOS->next());}
	
	
	$dadosArquivo .= '}."\n"';
}while($TB->next());


*/

require_once __DIR__ . '/codegen_helpers.php';

$q = new Model;
$DAT_FORMS = $q->read("system_form");
$lTabelas = 0;
$CONTENT_PERMISSOES = '';
$CONTENT_DEF_ARRAY = '';
if(count($DAT_FORMS)>0):
	foreach($DAT_FORMS as $DAT_FORM):
		$CONTENT_DB = '<?php '."\r\n";
		$TABLE_DEF = array();
	
		/*FORMATA O ARRAY COM OS DADOS DOS BOTOES ADICIONAIS*/
		$RET_DAT_BT_ADICIONAL = '';
		$l = 0;
		$conta_bts = 0;
		
		$CONTENT_DEF_ARRAY .= '$confgArrayDef['.$DAT_FORM['id'].'] = "'.$DAT_FORM['arquivo_def'].'";'."\r\n";

		
		$CONTENT_PERMISSOES .= '$CONTENT_PERMISSOES['.$lTabelas.']["nome"] = "'.$DAT_FORM['nome'].'";'."\r\n"; 
		$CONTENT_PERMISSOES .= '$CONTENT_PERMISSOES['.$lTabelas.']["valor"] = "'.trim(removeCaracteres($DAT_FORM['nome'])).'";'."\r\n"; 
		
		$botoes_adicionais = unserialize($DAT_FORM['botoes_adicionais']);
		if(!empty($botoes_adicionais)):
		foreach($botoes_adicionais as $k=>$v):
			if(!empty($v)):
				
				if($l == 0 ):
					$RET_DAT_BT_ADICIONAL .= '"'.$v.'"';
					$l++;
				else:
					$RET_DAT_BT_ADICIONAL .= ',"'.$v.'"';
					$l++;
				endif;
				
				$CONTENT_PERMISSOES .= '$CONTENT_PERMISSOES['.$lTabelas.']["botoes"]['.$conta_bts.']["nome"] = "'.$v.'";'."\r\n"; 
				$CONTENT_PERMISSOES .= '$CONTENT_PERMISSOES['.$lTabelas.']["botoes"]['.$conta_bts.']["valor"] = "'.trim(removeCaracteres($DAT_FORM['nome']).$v).'";'."\r\n"; 
				$conta_bts++;
				//$CONTENT_PERMISSOES[removeCaracteres($DAT_FORM['nome'])][] = $v;
			endif;
			
		endforeach;
		endif;
	
	
	//echo $RET_DAT_BT_ADICIONAL;
	//exit;
		//$b = array("nome_bt"=>array(),"chave_primaria"=>array(),"tabela_join"=>array(),"chave_estrangeira"=>array());
		
        $bts = unserialize($DAT_FORM['join_n_n']);
		
		if(is_array($bts)):
		foreach( $bts as $k=>$v){
			
			foreach( $bts[$k] as $l=>$m){
				
				 if($m!=''){
				 	$bts[$k][$l] = "'{$m}'";
				
				 	
					if($k=='nome_bt'){
					$CONTENT_PERMISSOES .= '$CONTENT_PERMISSOES['.$lTabelas.']["botoes"]['.$conta_bts.']["nome"] = "'.str_replace('\'','',$bts[$k][$l]).'";'."\r\n"; 
					$CONTENT_PERMISSOES .= '$CONTENT_PERMISSOES['.$lTabelas.']["botoes"]['.$conta_bts.']["valor"] = "'.removeCaracteres($DAT_FORM['nome']).removeCaracteres(str_replace('\'','',$bts[$k][$l])).'";'."\r\n"; 
					$conta_bts++;
					}
				 }else{
					unset($bts[$k][$l]); 
				 }
			}
				
		}	
		
		
		$nome_bt = implode(',',$bts['nome_bt']);
		$chave_primaria = implode(',',$bts['chave_primaria']);
		$tabela_join = implode(',',$bts['tabela_join']);
		$chave_estrangeira = implode(',',$bts['chave_estrangeira']);
		
		endif;

		$fileName = removeCaracteres($DAT_FORM['nome']);

			
	
		//ADICIONA OS DADOS DA TABELA
		$CONTENT_DB .= '$TABLE_DEF["id"] 				= "'.$DAT_FORM['id'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["link"] 				= "'.$fileName.'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["menu"] 				= "'.$DAT_FORM['menu'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["arquivo_def"] 				= "'.$DAT_FORM['arquivo_def'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["nome"] 				= "'.$DAT_FORM['nome'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["legenda"] 			= "'.$DAT_FORM['legenda'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["id_form"] 			= "'.$DAT_FORM['id_form'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["class"] 			= "'.$DAT_FORM['class'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["method"] 			= "'.$DAT_FORM['method'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["action"] 			= "'.$DAT_FORM['action'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["url_retorno"] 		= "'.$DAT_FORM['url_retorno'].'";'."\r\n";  
		$CONTENT_DB .= '$TABLE_DEF["tabela"] 			= "'.$DAT_FORM['tabela'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["preinsert"] 		= "'.$DAT_FORM['preinsert'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["preupdate"] 		= "'.$DAT_FORM['preupdate'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["predelete"] 		= "'.$DAT_FORM['predelete'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["posinsert"] 		= "'.$DAT_FORM['posinsert'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["posupdate"] 		= "'.$DAT_FORM['posupdate'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["posdelete"] 		= "'.$DAT_FORM['posdelete'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["item_menu"] 		= "'.$DAT_FORM['item_menu'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["botoes_adicionais"] = '."array(".$RET_DAT_BT_ADICIONAL .")".';'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["join_n_n"] = array("nome_bt"=>array('.$nome_bt.'),"chave_primaria"=>array('.$chave_primaria.'),"tabela_join"=>array('.$tabela_join.'),"chave_estrangeira"=>array('.$chave_estrangeira.'));'."\r\n"; 

		$CONTENT_DB .= '$TABLE_DEF["inserir"] 			= "'.$DAT_FORM['inserir'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["editar"] 			= "'.$DAT_FORM['editar'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["deletar"] 			= "'.$DAT_FORM['deletar'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["visualizar"] 		= "'.$DAT_FORM['visualizar'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["pdf"] 			    = "'.$DAT_FORM['pdf'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["listar_pagina"] 	= "'.$DAT_FORM['listar_pagina'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["sql_adicional"] 	= "'.str_replace('"','\'',$DAT_FORM['sql_adicional']).'";'."\r\n";  
		$CONTENT_DB .= '$TABLE_DEF["sql_ordem"] 	    = "'.str_replace('"','\'',$DAT_FORM['sql_ordem']).'";'."\r\n";  
		$CONTENT_DB .= '$TABLE_DEF["pre_listagem"] 		= "'.$DAT_FORM['pre_listagem'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["pos_listagem"]		= "'.$DAT_FORM['pos_listagem'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["checkbox"] 			= "'.$DAT_FORM['checkbox'].'";'."\r\n"; 
		$CONTENT_DB .= '$TABLE_DEF["condicao_checkbox"] = "'.$DAT_FORM['condicao_checkbox'].'";'."\r\n";
		
		
		
		$diretorio = dir("admin/filtro/");
		$RET_DAT_FILTRO_ADICIONAL = '';
		$l=0;
		while($arquivo = $diretorio->read()){
			
			$nome_arqui = explode("__",$arquivo);
		  	if(is_file("admin/filtro/".$arquivo) && $nome_arqui[0]==removeCaracteres($DAT_FORM['nome'])){
				
				if($l==0){
					$RET_DAT_FILTRO_ADICIONAL .= '"'.$arquivo.'"';
				}else{
					$RET_DAT_FILTRO_ADICIONAL .= ',"'.$arquivo.'"';
				}
				$l++;
				
			}
		}
		$diretorio->close();
		
		$CONTENT_DB .= '$TABLE_DEF["filtro_adicional"] = '."array(".$RET_DAT_FILTRO_ADICIONAL .")".';'."\r\n"; 
		
		
		
		
		if($DAT_FORM['sql_ordem']!=''):
		$CONTENT_DB .= '
						if(!function_exists("sql_ordem_'.$DAT_FORM['id'].'")){
						function sql_ordem_'.$DAT_FORM['id'].'(){
								'.base64_decode($DAT_FORM['sql_ordem']).'
						}
						}
		'."\r\n";  
		endif;
	
	
		if($DAT_FORM['sql_adicional']!=''):
		$CONTENT_DB .= '
						if(!function_exists("sql_adicional_'.$DAT_FORM['id'].'")){
						function sql_adicional_'.$DAT_FORM['id'].'(){
								'.base64_decode($DAT_FORM['sql_adicional']).'
						}
						}
		'."\r\n";  
		endif;
	
	
	
		$CONTENT_DB .= chr(13).chr(13).chr(13)."/* ==============INPUTS DOS FORMULÁRIO============== */".chr(13).chr(13);
		// BUSCA OS DADOS  DOS INPUTS DO FORMULÁRIO
		$DAT_INPUTS = $q->read("system_inputs","system_form = '".$DAT_FORM['id']."'","","","ordem, id");
		$l=0;
		$ABAS_FORM = array('');
		$ARRAY_ALL_INPUTS = array();
		if(count($DAT_INPUTS)>0):
			foreach($DAT_INPUTS as $DAT_INPUT):
				
				
				$l_item = (($DAT_INPUT['campo_tabela']!='' && !in_array($DAT_INPUT['campo_tabela'],$ARRAY_ALL_INPUTS))?'"'.$DAT_INPUT['campo_tabela'].'"':$l);
				
				$ARRAY_ALL_INPUTS[] = $DAT_INPUT['campo_tabela'];
				
				$ABAS_FORM[] = $DAT_INPUT['aba'];
				
				$CONTENT_DB .= chr(13).chr(13)."/* ==============".$DAT_INPUT['campo_tabela']."============== */".chr(13).chr(13);
				
				
				
						
				if($DAT_INPUT['sql_adicional']!=''):
				$CONTENT_DB .= '
								if(!function_exists("input_sql_adicional_'.$DAT_INPUT['id'].'")){
								function input_sql_adicional_'.$DAT_INPUT['id'].'(){
										'.base64_decode($DAT_INPUT['sql_adicional']).'
								}
								}
				'."\r\n";  
				endif;
				
				
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["id"]			   	        = "'.$DAT_INPUT['id'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["system_form"]			= "'.$DAT_INPUT['system_form'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["nome"]			   		= "'.$DAT_INPUT['nome'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["id_input"]			   	= "'.$DAT_INPUT['id_input'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["class"]				   	= "'.$DAT_INPUT['class'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["campo_tabela"]		   	= "'.$DAT_INPUT['campo_tabela'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["type"]				   	= "'.$DAT_INPUT['type'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["caracteristica"]		   	= "'.$DAT_INPUT['caracteristica'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["valor"]				   	= "'.$DAT_INPUT['valor'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["join_tabela"] 		   	= "'.$DAT_INPUT['join_tabela'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["join_chave_extrangeira"] = "'.$DAT_INPUT['join_chave_extrangeira'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["join_campo_exibido"] 	= "'.$DAT_INPUT['join_campo_exibido'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["mascara"] 			   	= "'.$DAT_INPUT['mascara'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["mascara_personalizada"]  = "'.$DAT_INPUT['mascara_personalizada'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["exb_cadastro"] 		   	= "'.$DAT_INPUT['exb_cadastro'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["exb_edicao"] 			= "'.$DAT_INPUT['exb_edicao'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["exb_listagem"]		   	= "'.$DAT_INPUT['exb_listagem'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["exb_filtro"]			   	= "'.$DAT_INPUT['exb_filtro'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["exb_view"]			   	= "'.$DAT_INPUT['exb_view'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["edicao_restrita"]	 	= "'.$DAT_INPUT['edicao_restrita'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["validacao"]			   	= "'.$DAT_INPUT['validacao'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["aba"]  				   	= "'.$DAT_INPUT['aba'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["mapear_componente"] 	   	= "'.$DAT_INPUT['mapear_componente'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["parametros_componente"]  = "'.$DAT_INPUT['parametros_componente'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["funcao_exibicao"] 	   	= "'.$DAT_INPUT['funcao_exibicao'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["linha_separadora"]	   	= "'.$DAT_INPUT['linha_separadora'].'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["secao"]	   	            = "'.addslashes($DAT_INPUT['secao']?$DAT_INPUT['secao']:'').'";'."\r\n";  
				$CONTENT_DB .= '$TABLE_DEF_INPUT['.$l_item.']["ordem"] 				   	= "'.$DAT_INPUT['ordem'].'";'."\r\n";  
				
				
				
				$l++;
			endforeach;
		endif;
		// DEFINE AS ABAS DO FORMULÁRIO
		if(count($ABAS_FORM)>0):
			$ABAS_FORM = array_unique($ABAS_FORM);
			$LIST_ABA = '';
			if(!empty($ABAS_FORM)):
				$l=0;
				foreach($ABAS_FORM as $k=>$v):
					if($l == 0 && !empty($v)):
						$LIST_ABA .= '"'.$v.'"';
						$l++;
					elseif(!empty($v)):
						$LIST_ABA .= ',"'.$v.'"';
						$l++;
					endif;
				endforeach;
			endif;
		endif;
		
		$CONTENT_DB .= chr(13).chr(13)."/* ============== ABAS DO FORMULARIO ============== */".chr(13).chr(13);
		$CONTENT_DB .= '$TABLE_DEF["system_form_abas_list"] = '."array(".$LIST_ABA .")".';'."\r\n"; 
	
		$CONTENT_DB .= ' ?>';
	
		$caminho_definicao = __DIR__ . '/../tables/def_' . $DAT_FORM['arquivo_def'] . '.php';
		system_atomic_write($caminho_definicao, $CONTENT_DB);
		
		$lTabelas++;
	endforeach;
endif;


$INFO_MENU = array();
$CONTENT_INFO_MENU = '';

$menu = DB::read('admin_menu');
$menu->load("order_by","id IN (SELECT menu FROM admin_menu_submenu WHERE submenu IN(SELECT id FROM admin_submenu))");
if($menu->size()){
	do{	
	
	
		$CONTENT_INFO_MENU .= '$INFO_MENU["'.trim($menu->item).'"]["order_by"]="'.$menu->order_by.'";'."\r\n";
		
		$submenu = DB::read('admin_submenu');
		$submenu->load("order_by","id IN (SELECT submenu FROM admin_menu_submenu WHERE menu = '".$menu->id."')");
		
		if($submenu->size())do{
			
			$CONTENT_INFO_MENU .= '$INFO_MENU["'.trim($menu->item).'"]["itens"]["'.$submenu->link.'"]["item"]="'.$submenu->item.'";'."\r\n";
			$CONTENT_INFO_MENU .= '$INFO_MENU["'.trim($menu->item).'"]["itens"]["'.$submenu->link.'"]["link"]="'.$submenu->link.'";'."\r\n";
			$CONTENT_INFO_MENU .= '$INFO_MENU["'.trim($menu->item).'"]["itens"]["'.$submenu->link.'"]["form"]="'.$submenu->form.'";'."\r\n";
			$CONTENT_INFO_MENU .= '$INFO_MENU["'.trim($menu->item).'"]["itens"]["'.$submenu->link.'"]["tabela"]="'.$submenu->tabela.'";'."\r\n";
			$CONTENT_INFO_MENU .= '$INFO_MENU["'.trim($menu->item).'"]["itens"]["'.$submenu->link.'"]["order_by"]="'.$submenu->order_by.'";'."\r\n";
			
			//$CONTENT_INFO_MENU .= '$INFO_MENU_FORM["'.$submenu->form.'"]["itens"]["'.$submenu->link.'"]["order_by"]="'.$submenu->order_by.'";'."\r\n";
			
			
			
		}while($submenu->next());
		
	}while($menu->next());
}

	$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["order_by"]="999";'."\r\n";
	$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["ico"]="geral.png";'."\r\n";
	
	$submenu = DB::read('admin_submenu');
	$submenu->load("order_by","id NOT IN (SELECT submenu FROM admin_menu_submenu)");
	
	if($submenu->size())do{
		
		$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["itens"]["'.$submenu->link.'"]["item"]="'.$submenu->item.'";'."\r\n";
		$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["itens"]["'.$submenu->link.'"]["link"]="'.$submenu->link.'";'."\r\n";
		$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["itens"]["'.$submenu->link.'"]["form"]="'.$submenu->form.'";'."\r\n";
		$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["itens"]["'.$submenu->link.'"]["tabela"]="'.$submenu->tabela.'";'."\r\n";
		$CONTENT_INFO_MENU .= '$INFO_MENU["Geral"]["itens"]["'.$submenu->link.'"]["order_by"]="'.$submenu->order_by.'";'."\r\n";
		
	}while($submenu->next());



require_once __DIR__ . '/codegen_helpers.php';

system_atomic_write(__DIR__ . '/../tables/_admin_menu.php', '<?php '.($CONTENT_INFO_MENU).' ?>');
system_atomic_write(__DIR__ . '/../tables/_admin_permissoes.php', '<?php '.($CONTENT_PERMISSOES).' ?>');
system_atomic_write(__DIR__ . '/../tables/_admin_def_tables.php', '<?php '.($CONTENT_DEF_ARRAY).' ?>');



?>