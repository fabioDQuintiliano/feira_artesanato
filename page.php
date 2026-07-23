<?php	
	global $MAP;
	$url = explode('/',$type[0]);
	$url = array_merge($url,$params);
	$pagina_solicitada = $url[0];

		

	/* verifica se é uma página*/

	/* verifica se é uma página*/

	if(is_file('pages/'.$pagina_solicitada.'.php') || is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php')):

		unset($_SESSION['token']);



		$_SESSION['token'] = substr(md5(rand(0,90000)),5,10);	

		$MAP['page'] = $pagina_solicitada;
		

		/*if($pagina_solicitada == 'blog' && $url[1] != '' && !intval($url[1]) > 0){

			$pagina_solicitada = 'postagem';	

		}*/

		
		if(is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php')){
			$path_page_incluida = 'pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php';
		}else{
			$path_page_incluida = 'pages/'.$pagina_solicitada.'.php';
		}

	

	else:	



	
		$pagina_solicitada = '404';
		
		$path_page_incluida = 'pages/'.$pagina_solicitada.'.php';

		//$path_page_incluida = 'pages/404.php';

	endif;

	$page = file_get_contents($path_page_incluida);	

	preg_match('#<!\-\-\[CONTAINER\-(.*?)\]\-\->#',addslashes($page), $matches);



	$CONTAINER = $matches[1];

	



	$PAGE = explode('[CONTENT-PLACE]',$CONTAINER);

	

	/*

	-- Insere um javascript especifico para a página solicitada.

	*/

	if(is_file('script/byform/'.$pagina_solicitada.'.js')):

		$head_include .= '<script type="text/javascript" src="'.ROOT.'script/byform/'.$pagina_solicitada.'.js"></script>';

	endif;





	ob_start();

	

		include 'containers/exe_system/'.$CONTAINER.'_head.php';

		/*

		-- inclui uma folha especifica de funcoes.

		*/

		

		//if(is_file('script/byform/'.$pagina_solicitada.'.php')):

		//	include 'script/byform/'.$pagina_solicitada.'.php';

		//endif;





		if(is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.css.php')){
			include  'pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.css.php';
		}

		include $path_page_incluida;

		if(is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.vue.php')){
			
			include  'pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.vue.php';
		}

			

		if($MASTER_PAGETITLE!=''):

			$head_include .= '<title>'.$MASTER_PAGETITLE.'</title>'."\r\n";
			$head_include .= '<meta property="og:title" content="'.$MASTER_PAGETITLE.'" />'."\r\n";

		else:

			$head_include .= '<title>'.PROJETO_NOME.'</title>'."\r\n";
			$head_include .= '<meta property="og:title" content="'.PROJETO_NOME.'" />'."\r\n";

		endif;





		if($MASTER_DESCRIPTION !=''):

			$head_include .= '<meta name="description" content="'.$MASTER_DESCRIPTION.'">'."\r\n";
			$head_include .= '<meta property="og:description" content="'.$MASTER_DESCRIPTION.'" />'."\r\n";

		endif;

		$head_include .= '<meta property="og:type" content="website" />'."\r\n";
		$head_include .= '<meta property="og:url" content="'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"].'" />'."\r\n";

		if($MASTER_KEYWORDS!=''):

			$head_include .= '<meta name="keywords" content="'.$MASTER_KEYWORDS.'">'."\r\n";

		endif;

		if($MASTER_IMAGE!=''):

			$head_include .= '<meta property="og:image" itemprop="image" content="'.$MASTER_IMAGE.'" />'."\r\n";

		endif;


		if($MASTER_ADD_TO_HEADER!=''):

			$head_include .= $MASTER_ADD_TO_HEADER."\r\n";

		endif;



		

		

		include 'containers/exe_system/'.$CONTAINER.'_foot.php';

		

	$FULL_PAGE_CONTENT = ob_get_clean();

	

	$FULL_PAGE_GERADA  = formataFullPageRet($FULL_PAGE_CONTENT,$head_include);

	echo $FULL_PAGE_GERADA;

	

	

	