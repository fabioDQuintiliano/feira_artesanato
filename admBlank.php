<?php
$verifica_acesso_system = checa_acesso_system();// verifica o acesso ao sistema interno de administracao

if($verifica_acesso_system):
	
	global $MAP;
	
		
	$configTableList = getInfoItem($_GET[':item']);
	
	if($configTableList->size()>0):
	
		$MAP['id_form_list'] = $configTableList->id;
		$MAP['nome_base'] = removeCaracteres($configTableList->nome);
		require_once("tables/def_".$configTableList->arquivo_def.".php");
		$dados_form_listado = $TABLE_DEF;		
		
		global $MAP;
		if($dados_form_listado != '')
		foreach($dados_form_listado as $k=>$v):
			$MAP[$k] = $v;
			
		endforeach;
		$MAP['page_title'] = $configTableList->nome;
		
	endif;
	

	
	
	if(!empty($pagina_solicitada)):
				
		/*inclui a página de listagem de registros*/
		if(is_file('admin/exe_system/'.$pagina_solicitada.'.php')):
			$path_page_incluida = 'admin/exe_system/'.$pagina_solicitada.'.php';
		else:
			$path_page_incluida = 'page/exe_system/404.php';
		endif;
		
		ob_start();
		
			require_once('admin/container-admin.php');
			$CONTAINER = ob_get_contents();
			
		ob_clean();	
		
			require_once($path_page_incluida);
			$PAGINA_MAIN_CONTENT = ob_get_contents();
			
		ob_end_clean();
		
        $CONTAINER = preg_replace("|<!\-\-BLANCKOFF\-INICIO\-\->(.*?)<!\-\-BLANCKOFF\-FIM\-\->|s",'',$CONTAINER);
		$PAGE = explode('[CONTENT-PLACE]',$CONTAINER);
		
		/*
		-- Insere um javascript especifico para a página solicitada.
		*/
		if(is_file('script/byform/'.$MAP['nome_base'].'.js')):
			$head_include .= '<script type="text/javascript" src="'.ROOT.'script/byform/'.$MAP['nome_base'].'.js"></script>';
		endif;
		
		/*
		-- Insere o CSS e os javascripts espefificados anteriormente dentro da página container.
		*/
		
		
		ob_start();
		
			echo $PAGE[0];
			/*
			-- inclui uma folha especifica de funcoes.
			*/
			
			if(is_file('script/byform/'.$MAP['nome_base'].'.php')):
				include 'script/byform/'.$MAP['nome_base'].'.php';
			endif;
		
			
			echo $PAGINA_MAIN_CONTENT;				
			echo $PAGE[1];
			
		$FULL_PAGE_CONTENT = ob_get_clean();
		
		$FULL_PAGE_GERADA  = formataFullPageRet($FULL_PAGE_CONTENT,$head_include);
		echo $FULL_PAGE_GERADA;
		
	endif;
else:
	include 'admin/head_login.php';
	include 'admin/loginSystem.php';
	include 'admin/footer_login.php';
endif;



?>