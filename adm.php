<?php

$verifica_acesso_system = checa_acesso_system();// verifica o acesso ao sistema interno de administracao


if($verifica_acesso_system):

	

	global $MAP,$configTableList;

	$configTableList = getInfoItem($_GET[':item']);

	

	$MAP['def_file'] = $configTableList->arquivo_def;

	$MAP['form_id'] = $configTableList->id;

	$MAP['tabela'] = $configTableList->tabela;

	

	if($configTableList!=null):

		//$MAP['id_form_list'] = $configTableList->id;

		$dados_form_listado = $configTableList->TABLE_DEF;		

		

		global $MAP;

		if($dados_form_listado != '')

		foreach($dados_form_listado as $k=>$v):

			$MAP[$k] = $v;

			

		endforeach;

		$MAP['page_title'] = $configTableList->nome;

	endif;

	

			

	/*

	-- Insere um javascript especifico para a página solicitada.

	*/

	if(is_file('script/byform/'.removeCaracteres($configTableList->nome).'.js')):

		$head_include .= '<script type="text/javascript" src="'.ROOT.'script/byform/'.removeCaracteres($configTableList->nome).'.js"></script>';

	endif;

	

	/*

	-- Insere o CSS e os javascripts espefificados anteriormente dentro da página container.

	*/

	ob_start();

	

		require_once('admin/inicio.php');
		
		

	$FULL_PAGE_CONTENT = ob_get_clean();

	

	$FULL_PAGE_GERADA  = formataFullPageRet($FULL_PAGE_CONTENT,$head_include);

	echo $FULL_PAGE_GERADA;

		

else:

	include 'admin/head_login.php';

	include 'admin/loginSystem.php';

	include 'admin/footer_login.php';

endif;







?>