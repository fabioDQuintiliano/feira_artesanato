<?php

global $MAP;

$MAP['HEAD_INCLUDES_ADMIN'] = '';



/*

 --- inclui o arquivo de configurações do sistema

*/



/*

 --- Todas as funções são incluidas automaticamente.

 --- Essa parte do código, busca dentro da pasta "functions" os arquivos de funcoes e os inclui na página

 --- Esse arquivos devem estar listados no array de arquivos permitodos para inclusão. Essa é uma medida de segurança para impedir a inclusão de arquivos maliciosos.

*/


include 'front_includes.php';




if(is_dir('system')):

	require_once 'system/codegen_helpers.php';

	// Em production, codegen fica desligado (SYSTEM_CODEGEN=0). Use /system-rebuild.
	if(defined('SYSTEM_CODEGEN') && SYSTEM_CODEGEN):

		try {

			system_run_codegen();

		} catch (Throwable $e) {

			error_log('SYSTEM_CODEGEN: '.$e->getMessage());

		}

	endif;

	define('IS_OCA',1);

endif;



//include("config.php");

//define uma sessao para a url padrao

$_SESSION['ROOT'] = ROOT;



//inclui o arquivo de idiomas do sistema.

if(is_file('lang/'.LANG.'.lang.php')):

	include 'lang/'.LANG.'.lang.php';

endif;



global $listapar;

$listapar = array('reservas----cliente','teste----adm');



/*

 --- ajusta todos os '$_POST' para evitar sql injection

*/

if(isset($_POST)):

	foreach($_POST as $k =>$v):

		if(!is_array($v))

			$_POST[':'.$k] = addslashes($v);

				

	endforeach;

endif;



if(isset($_GET)):

	foreach($_GET as $k =>$v):

		if(!is_array($v))

			$_GET[':'.$k] = addslashes($v);

				

	endforeach;

endif;



if(isset($_REQUEST)):

	foreach($_REQUEST as $k =>$v):

		if(!is_array($v))

			$_REQUEST[':'.$k] = addslashes($v);

				

	endforeach;

endif;



$q = new Model;	





/*

 --- Controla as configurações de URL amigável

 --- Configura as páginas do sistema e a inclusão de ações e o coneudo do sistema

 --- Tudo que vier antes do ifem define uma acão.

	 o `explode` separa o tipo de ação e oq será executado dentro dessa ação. 

	 o array "$acoes_permitidas" define quais tipos de acoes estão disponiveis para serem utilizadas

*/



$acoes_permitidas = array('function', 'action', 'page', 'system', 'fn','adm','admsite','blank','obj');


$pretype = explode('/',$_GET['pg']);
$type = explode('-',$pretype[0]);

$params = $pretype;
unset($params[0]);

//var_dump($params);

//var_dump($_GET['pg'],$type);

$PREFIX_PAGE = $type[0];


define('PREFIX_PAGE',$PREFIX_PAGE);

if(empty($type[1])):



	include 'page.php';

/*

-- este 'else' indica que existe alguma ação definida.

-- agora o sistema vai verificar se essa ação é válida e executar o que foi solicitado

-- para adicionar uma nova ação à esta lista, o identificador da ação deve estar especificado no array "$acoes_permitidas"

*/

elseif(in_array($PREFIX_PAGE, $acoes_permitidas)):


	$url = explode('/',$type[1]);

	$url = array_merge($url,$params);
	//var_dump($url);
	$pagina_solicitada = $url[0];

	

	if($PREFIX_PAGE == 'system'): //inclui funcoes do sistema

		

		$verifica_acesso_system = checa_acesso_system();// verifica o acesso ao sistema interno de administracao

		

		

		if($verifica_acesso_system):

		include 'system/head.php';

		/*

		-- verifica se a pasta "system" existe. Se existir inclui alguns arquivos especificos para a administracao do sistema.

		*/

		if(is_dir('system') && defined('SYSTEM_AUX_BAR') && SYSTEM_AUX_BAR):

			include('system/page_auxiliar_sistema.php');

		endif;



			$systemIdeAllowed = (defined('SYSTEM_IDE_ENABLED') && SYSTEM_IDE_ENABLED)
				|| $pagina_solicitada === 'rebuild';

			if(!$systemIdeAllowed):

				echo '<div style="padding:2rem;font-family:sans-serif">IDE do sistema desabilitada neste ambiente (SYSTEM_IDE_ENABLED=0). Use <a href="'.ROOT.'system-rebuild">system-rebuild</a> para regenerar artefatos.</div>';

			elseif(is_file('system/pages/'.$pagina_solicitada.'.php')):

				include 'system/pages/'.$pagina_solicitada.'.php';

			else:

				include 'system/pages/inicio.php';

			endif;

			

		else:

			include 'admin/head_login.php';

			include 'admin/loginSystem.php';

			include 'admin/footer_login.php';

		endif;

	/*	

	-- retorna o valor de acoes solicitadas pela url, geralmente utilizado pelo "jQuery" para executar ações PHP

	*/

	elseif($PREFIX_PAGE == 'fn'):

		$PARAMS_FUNCTION = array();

		foreach($_POST as $k=>$v):

			if(substr($k,0,1)=='p')

			$PARAMS_FUNCTION[] = $v;

		endforeach;

		//echo $pagina_solicitada();

		echo call_user_func_array( $pagina_solicitada , $PARAMS_FUNCTION ); 

		

	/*

	-- inclui acoes, basecamente utilizadas por formularios

	*/

	elseif($PREFIX_PAGE == 'action'):

		$url = explode('/',$type[1]);
		$url = array_merge($url,$params);

		

		$pagina_solicitada = $url[0];

		if(is_file('action/'.$pagina_solicitada.'.php')):

		

			include 'action/'.$pagina_solicitada.'.php';

			echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=".ROOT.$_POST['sucesso']."'>";

			//header('location:'.ROOT.$_POST['sucesso'].'');

			exit;

			

		else:

			echo utf8_decode('<span style="font-weight:bold; font-size:28px;">Ação não encontrada</span>');

		endif;

		

	/*

	-- area administrativa do systema 

	-- é necessário possuir login para acesar esta parte do sistema.

	*/

	

	elseif($PREFIX_PAGE == 'obj'):

		include "obj.php";	

	elseif($PREFIX_PAGE == 'admsite'):

		include "admsite.php";	

	elseif($PREFIX_PAGE == 'blank'):

		include "admBlank.php";	

	elseif($PREFIX_PAGE == 'adm'):

		include "adm.php";	

		/*

		-- verifica se a pasta "system" existe. Se existir inclui alguns arquivos especificos para a administracao do sistema.

		*/

		// Barra auxiliar só com sessão ativa (login não carrega jQuery)
		if(
			function_exists('checa_acesso_system')
			&& checa_acesso_system()
			&& is_dir('system')
			&& defined('SYSTEM_AUX_BAR')
			&& SYSTEM_AUX_BAR
		):

			include('system/page_auxiliar_sistema.php');

		endif;

	endif;

	

	

	

	

	

else:



	echo 'Erro 404.';



endif;







