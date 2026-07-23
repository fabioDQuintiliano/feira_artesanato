<?php

	$url = explode('/',$type[0]);

	$pagina_solicitada = $url[0];

	

	/* verifica se é uma página*/

	if(is_file('pages/'.$pagina_solicitada.'.php')):

		$path_page_incluida = 'pages/'.$pagina_solicitada.'.php';

	

		ob_start();

		

			include $path_page_incluida;	

	

		$CONTENT_OBJ = ob_end_clean();

	

	

	endif;





?>