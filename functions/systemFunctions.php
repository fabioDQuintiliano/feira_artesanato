<?php

function myHeader($info){

	header($info);

}

/*gera o botão de pdf*/



function PDF($nome='Relatório'){

	return '<a href="'.ROOT.'pdf.php?nome='.$nome.'" target="_new"><div class="btRelGera"><img src="'.ROOT.'images/admin/pdf_ico.png" /></div></a>';

}

function base64_encode_checa($str=null){
	return $str && $str != ''?base64_encode($str):'';
}

function base64_decode_checa($str=null){
	return $str && $str != ''?base64_decode($str):'';
}


function loadCss($css){

	echo  '<link href="'.ROOT.$css.'" rel="stylesheet" type="text/css" />';

}

/*

--- Verica o acesso ao sistema de administração

*/

function checa_acesso_system(){

		if(isset($_SESSION['system_admin']) && isset($_SESSION['system_pass'])):

			$q = new Model;

			$adm = $q->read("system_admin", "login = '".$_SESSION['system_admin']."' AND senha = '".$_SESSION['system_pass']."'");

			if(empty($adm)):

				unset($_SESSION['user_id']);

				unset($_SESSION['system_pass']);

				unset($_SESSION['system_admin']);

				//session_destroy();

				return false;

			else:

				return 1;

			endif;

			

		

		endif;	

	

}

function location($url){

	echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=".$url."'>";

	exit;	

}



function arquivo($file,$tipo=0){

	$arq = explode("\n",$file);

	if($tipo == 1){

		return $arq[0];	

	}else{

		return $arq[1];	

	}

		

}

/*

--- pega a url atual

*/

function urlAtual(){

	$server = $_SERVER['SERVER_NAME']; 

	$endereco = $_SERVER ['REQUEST_URI'];

	

	return "http://" . $server . $endereco;	

}

/*

--- pega os dados do item atual "$_GET['item']" 

*/

function getInfoItem($item){

	

	$configTableList = new \stdClass();

	if($confgArrayDef == null){

		require('tables/_admin_def_tables.php');

	}

	

	if($confgArrayDef[$item] != ''):

		

		require('tables/def_'.$confgArrayDef[$item].'.php');

		

		foreach($TABLE_DEF as $kTable=>$vTable):	

			@$configTableList->{$kTable} = $vTable;

		endforeach;

		$configTableList->TABLE_DEF = $TABLE_DEF;

		$configTableList->TABLE_DEF_INPUT = $TABLE_DEF_INPUT;

		return $configTableList;

	endif;

	return $configTableList;

	

}



/*

verifica o perfil em que o usuario esta logado

*/

function logado_no_perfil($perfil){

	$aux = DB::read("system_admin");

	$aux->id = $_SESSION['user_id'];

	$aux->load();

	

	if($perfil == $aux->perfil){

		return true;	

	}else{

		return false;	

	}

	

}



/*

--- Efetua o login à area administrativa do sistema

*/

function loginSystem($user, $senha){



	if(isset($_SESSION[PROJETO_NOME]['tryLogin']) && $_SESSION[PROJETO_NOME]['tryLogin'] >=15):

		$info = new UserInfo;

		$ip = $info->getIp();

		

		$dataBlock = addDia(date('Y-m-d'),1).' '.date('H:i:s');

		

		$aux = DB::read('system_block');

		$aux->ip = $ip;

		$aux->data = $dataBlock;

		$aux->save();

		

		$_SESSION[PROJETO_NOME]['temp_block'] = $dataBlock;

		

		

	else:

		$info = new UserInfo;

		$ip = $info->getIp();

		

		//exit;

		

		$verfLogin = DB::read('system_block');

		$verfLogin->ip = $ip;

		$verfLogin->load('',"data > NOW()");

		

		if($verfLogin->size() == 0 && $ip != null && ($_SESSION[PROJETO_NOME]['temp_block'] >= date('Y-m-d H:i:s') || $_SESSION[PROJETO_NOME]['temp_block'] == '')):	

			

			

			$q = new Model;

			$login = $q->read("system_admin", "login = '".addslashes($user)."' AND senha = '".encriptPassSystem(addslashes($senha))."' AND login <> '' AND senha <> ''");

			

			

			if(!empty($login)):

				$_SESSION['user_id'] = addslashes($login[0]['id']);

				$_SESSION['system_admin'] = addslashes($user);

				$_SESSION['system_pass'] = encriptPassSystem(addslashes($senha));

				unset($_SESSION[PROJETO_NOME]['tryLogin']);

				unset($_SESSION[PROJETO_NOME]['temp_block']);

				return $login[0];

			else:

				$_SESSION[PROJETO_NOME]['tryLogin'] ++;

				unset($_SESSION['user-id']);

				unset($_SESSION['system_pass']);

				unset($_SESSION['system_admin']);

				//session_destroy();

				return false;

			endif;

		else:

			$_SESSION[PROJETO_NOME]['tryLogin'] ++;

			unset($_SESSION['user-id']);

			unset($_SESSION['system_pass']);

			unset($_SESSION['system_admin']);

			return false;

		endif;

		

	endif;

		

}

/*

 --- verifica ad permissoes do usuario logado

*/

function perfilUser(){

	$q = new Model;

	$dado = $q->read("system_perfil","id IN(SELECT perfil FROM system_admin WHERE id = '".$_SESSION['user_id']."')");

	//var_dump($dado,$_SESSION['user_id']);

	

	return unserialize($dado[0]['permissoes']);

	

}





/*

--- essa funcão é utilizada para edicao em selects, verifica se os valores são iguais e retorna o item selecionado 

*/

function selected($p1, $p2){

	

	if($p1 == $p2):

		return ' selected="selected" ';

	else:

		return null;

	endif;



}







/*

--- Busca o preencimento automatico do formulario  de cadastro de campos.

*/

function inputForm($val = null, $val2 = null, $nome = null){

	

	if(!empty($nome)):

		$name = $nome;

	else:

		$name = 'chave_extrangeira';

	endif;

?>

        	<select name="<?php echo $name;?>" id="<?php echo $name;?>">

            	<option value=""></option>

				<?php

				if(!empty($val)):

					$p1 = $val;

				else:

					$p1 = $_POST['p1'];

				endif;

				if(!empty($val2)):

					$p2 = $val2;

				else:

					$p2 = $_POST['p2'];

				endif;

				

				$q = new Model;

                $inputs = $q->listaCampos($p1);

				foreach($inputs as $list):

				

					echo '<option '.selected($list['Field'], $p2).' value="'.$list['Field'].'">'.$list['Field'].'</option>';

				

				endforeach;

				?>                

            </select>

<?php

	

}

/*

--- concatena se o valor da variavel se não for vazio

*/

function linha($p1,$variavel,$p2 = null){

	

	if($variavel != ''):

		return $p1.$variavel.$p2;

	else:

		return null;

	endif;

	

}



function carrega_lista(){

	$q = new Model;

	

	

	$tabela = $_POST['p0'];

	$campo = $_POST['p1'];

	$item_busca = $_POST['p2'];

	

	

	

	$dados_busca = $q->read("system_inputs","campo_tabela = '".$campo."' AND system_form IN (SELECT id FROM system_form WHERE tabela = '".$tabela."')");

	

	if(strlen($item_busca)>0):

	//return $item_busca;

		$dados = $q->read($dados_busca[0]['join_tabela'],"".$dados_busca[0]['join_campo_exibido']." LIKE '%".$item_busca."%' OR ".$dados_busca[0]['join_chave_extrangeira']." = '".$item_busca."'",'15');

		

		

		

		ob_start();

		

		echo '<div class="list_container_auto">';

			$linha = 1;

			foreach($dados as $l):

			

				$lin = $linha%2;

				

				if($lin == 0):

					echo '<div class="odd select_auto" valor="'.$l[$dados_busca[0]['join_chave_extrangeira']].'" nome="'.$l[$dados_busca[0]['join_campo_exibido']].'" title="'.$l[$dados_busca[0]['join_campo_exibido']].'">'.substr($l[$dados_busca[0]['join_campo_exibido']],0,39).'</div>';

				else:

					echo '<div class="even select_auto" valor="'.$l[$dados_busca[0]['join_chave_extrangeira']].'" nome="'.$l[$dados_busca[0]['join_campo_exibido']].'">'.substr($l[$dados_busca[0]['join_campo_exibido']],0,39).'</div>';

				endif;

				$linha ++;

			

			endforeach;

		echo '</div>';

		

		$CO = ob_get_contents();

		ob_end_clean();

		

		return $CO;

		

	endif;

			

}



/*

 --- EXIBE O NOME DA PÁGINA ATUAL

*/

function nomePage($page){

	//return $page;

	//return false;

	require('tables/def_'.$page.'.php');

	if($TABLE_DEF["nome"]!=''):

	$retorno = '<div class="nome_page_atual">';

	$retorno .=  $TABLE_DEF["nome"];

	$retorno .= '</div>';

	endif;

	return	$retorno;

	

}





/*

 --- converte o formato de valores

*/				

function dinheiroToFloat($val){

	$valor = str_replace(array('R$',' ','.',','),array('','','','.'),$val);

	return floatval($valor);

	

}



function floatToDinheiro($val){

	$valor = str_replace(array('.'),array(','),$val);

	$v = explode(',',$valor);

	$ret = $v[0].','.str_pad($v[1], 2, "0");

	return $valor;

	

}



function floatToDinheiro2($val,$prefx=null){

	$valor = str_replace(array('.'),array(','),$val);

	$v = explode(',',$valor);

	$ret = $v[0].','.str_pad($v[1], 2, "0");

	

	if($prefx != null){

		$ret = $prefx.$ret;	

	}

	return $ret;

	

}



function floatToDinheiro2_rs($val){

	$valor = str_replace(array('.'),array(','),$val);

	$v = explode(',',$valor);

	$ret = $v[0].','.substr(str_pad($v[1], 2, "0"),0,2);

	return 'R$ '.$ret;

	

}





/*

-------------------------

*/

function limita_texto($txt,$limit){

	$txt = strip_tags($txt);

	$txt = explode(' ',substr(trim(strip_tags($txt)),0,$limit));

	array_pop($txt);

	$txt=implode(' ',$txt);

	return $txt.' ...';

	

	

}

function texto($id,$tipo=null){

	$aux = DB::read('textos');

	$aux->id = $id;

	$aux->load();

	if($tipo != ''){

		return $aux->$tipo;

	}else{

		return $aux;	

	}



}



/*

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

*/



function busca_dados($tabela,$campo,$busca,$retorno=null){

	

	$q = new Model;

	$dados = $q->read($tabela, $campo." = '".$busca."'");

	

	if($retorno!=null){

		return $dados[0][$retorno];	

	}else{

		return $dados[0];	

	}

}



/*

--- Criptografia das senha utilizadas para acesso ao sistema.

*/

function encriptPassSystem($senha){

	return sha1($senha);

}

/*

--- Busca cidades a partir do Estado

*/



function buscaCidadesEst($id,$sele=null){

	if($id != ''){

		$aux = DB::read("cidade");

		$aux->estado = $id;

		$aux->load("nome");

		$ret = '<option value="">Selecione</option>';

		if($aux->size()>0){do{

			$ret .= '<option value="'.$aux->id.'" '.($aux->id==$sele?'selected="selected"':'').'>'.($aux->nome).'</option>';

		}while($aux->next());}

		return $ret;

	}

}



/*

 --- RETORNA AS MARGENS PARA ALINHAMENTO DA IMAGEM

*/

function margemImagem($img, $larg, $alt){

	$info_imagem = GetImageSize(str_replace(ROOT,'',$img));

	

	$largura = $info_imagem[0];

	$altura = $info_imagem[1];

	// altera os dados se a imagem for mensor que as dimensoes especificadas.

	if($largura < $larg):

		$add_larg = ($larg - $largura)/2;

		$larg = $largura;

	endif;

	if($altura < $alt):

		$add_alt = ($alt - $altura)/2;

		$alt = $altura;

	endif;

	

	//caucula as margens para centralizar a imagem

	if($largura > $altura):

		$m_topo = ($alt - (($altura * $larg)/$largura)) / 2;

		return 'width="'.$larg.'px" style="margin-top:'.($m_topo + $add_alt).'px; margin-left:'.$add_larg.'px;"';

	elseif($altura > $largura):

		$m_left = ($larg - (($largura * $alt)/$altura)) / 2;

		return 'height="'.$alt.'px" style="margin-left:'.($m_left + $add_larg).'px; margin-top:'.$add_alt.'px; "';

	elseif($largura == $altura):

		

		return 'width="'.$larg.'px" ';

	else:

		return null;

	endif;		

}


function url_amigavel($txt){
	$txt = removeCaracteres($txt);
	$txt = str_replace("_", " ", $txt);
	$txt = preg_replace("/[^a-z0-9\s]/i", "", $txt);

	//var_dump($txt);

	

	$txt = str_replace("  ", " ", $txt);

	return removeCaracteres($txt);

}

/*

 --- Remove caracteres

*/

function removeCaracteres($txt){
	//

	$caracteres = array('á','à','ã','â','é', 'è', 'ê','í','ì','ó','ò','ô','õ','ú','ù','ü','ç','Á','À','Ã','Â','É','È','Ê','Í','Ì','Ó','Ò','Ô','Õ','Ú','Ù','Ü','Ç','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',' ');

	$substitui  = array('a','a','a','a','e', 'e', 'e','i','i','o','o','o','o','u','u','u','c','a','a','a','a','e','e','e','i','i','o','o','o','o','u','u','u','c','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','_');

	
	if($txt){
		return str_replace($caracteres, $substitui, $txt);
	}else{
		return $txt;
	}

}





/*

-- rotorna mes

*/

function retornaMes($num,$completo=false){

	$num = str_pad($num, 2, "0", STR_PAD_LEFT);

	switch($num){

		case '01';

		$ret = $completo==true?'Janeiro':'Jan';	

		break;

		

		case '02';

		$ret = $completo==true?'Fevereiro':'Fev';	

		break;

		

		case '03';

		$ret = $completo==true?'Março':'Mar';	

		break;

		

		case '04';

		$ret = $completo==true?'Abril':'Abr';	

		break;

		

		case '05';

		$ret = $completo==true?'Maio':'Mai';	

		break;

		

		case '06';

		$ret = $completo==true?'Junho':'Jun';	

		break;

		

		case '07';

		$ret = $completo==true?'Julho':'Jul';	

		break;

		

		case '08';

		$ret = $completo==true?'Agosto':'Ago';	

		break;

		

		case '09';

		$ret = $completo==true?'Setembro':'Set';	

		break;

		

		case '10';

		$ret = $completo==true?'Outubro':'Out';	

		break;

		

		case '11';

		$ret = $completo==true?'Novembro':'Nov';	

		break;

		

		case '12';

		$ret = $completo==true?'Dezembro':'Dez';	

		break;

		

	}

	return $ret;

	

}



/*

 --- RETORNA A DIFERENÇa ENTRE DUAS DATAS

*/



function difDate($d1,$d2){

	

	// Usa a função strtotime() e pega o timestamp das duas datas:

	$time_inicial = strtotime($d1);

	$time_final = strtotime($d2);

	

	// Calcula a diferença de segundos entre as duas datas:

	$diferenca = $time_final - $time_inicial; // 19522800 segundos

	

	// Calcula a diferença de dias

	$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

	

	return $dias;

}



/*

-----------------------

funçoes para converter os formatos das datas.

-----------------------

*/

function date2banco($data){

	$d1 = explode(' ',$data);

	

	return substr($d1[0],6,4).'-'.substr($d1[0],3,2).'-'.substr($d1[0],0,2).((!empty($d1[1]))?' '.$d1[1]:'');

}

function banco2date($data,$tipo = null){

	

	$d1 = explode(' ',$data);

	

	if($tipo == 'dt'){

		return substr($d1[0],8,2).'/'.substr($d1[0],5,2).'/'.substr($d1[0],0,4);

	}else{

		return substr($d1[0],8,2).'/'.substr($d1[0],5,2).'/'.substr($d1[0],0,4).((!empty($d1[1]))?' às '.substr($d1[1],0,5):'');

	}

}

/*

-----------------------

-----------------------

*/



/*

 --- GERA O BOTÀO DE DAR BAIXA NA FATURA

*/

function dar_baixa_fatura($id,$tabela){

	global $url;

	$q = new Model;

	$dado = $q->read("fatura","id = '".$id."'");

	

	if($dado[0]['pago'] == 1){

		return '';	

	}else{

		return '<div style="width:60px;"><a href="'.ROOT.'adm-'.$url[0].'_info/'.$url[1].'/'.$url[2].'/'.$id.'" title="Dar baixa nesta fatura">Dar Baixa</a></div>';

	}

	

}

/*

 --- Retorna o status da fatura

*/

function status_faturas($id,$valor){

	

	$q = new Model;

	$dado = $q->read("fatura","id = '".$id."'");

	

	if($dado[0]['pago'] == 1){

		return "Pago em ".banco2date($dado[0]['data_pagamento']);	

	}

	

}



function exibe_checkbox_faturas($id){

	$q = new Model;

	$dado = $q->read("fatura","id = '".$id."'");

	if($dado[0]['pago'] == 1){

		return false;

	}else{

		return true;	

	}

	

}



function busca_info_cliente($num=null,$tipo='rg'){

	$q = new Model;

	$dados = $q->read('cliente', $_POST['p2']." = '".$_POST['p1']."'");



	return json_encode($dados[0]);



}

/*

--- RETORNA O DIA DA SEMANA

*/



function diasemana($data) {

	$ano =  substr("$data", 0, 4);

	$mes =  substr("$data", 5, -3);

	$dia =  substr("$data", 8, 9);



	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );



	switch($diasemana) {

		case"0": $diasemana = "Domingo";       break;

		case"1": $diasemana = "Segunda"; break;

		case"2": $diasemana = "Terça";   break;

		case"3": $diasemana = "Quarta";  break;

		case"4": $diasemana = "Quinta";  break;

		case"5": $diasemana = "Sexta";   break;

		case"6": $diasemana = "Sábado";        break;

	}



	return $diasemana;

}









/*

--- ajusta a imagem;

*/



function ajusta_imagem($imagem, $largura, $altura){

	

	$info_imagem = GetImageSize(str_replace(ROOT,'',$imagem));

	

	$largura_real = $info_imagem[0];

	$altura_real = $info_imagem[1];

	

	if($largura_real>$altura_real):

	

		return ' height="'. $altura.'px"';

	else:

		return ' width="'. $largura.'px"';

	endif;

	

}





/*

---

*/



function cropImagem(){

	$targ_w = $_POST['w'];

	$targ_h = $_POST['h'];

	$jpeg_quality = 100;

	

	$src = 'images/upload/'.$_POST['imagem'];

	$tipo = (getimagesize($src));

	

	

	uploadnoagua('images/upload/'.$_POST['imagem'], $_POST['imagem'], null, 'images/upload/',$tipo['mime'],$targ_w,$targ_h,$_POST['x'],$_POST['y']);

	uploadnoagua('images/upload/'.$_POST['imagem'], 'view_'.$_POST['imagem'], ($_POST['view']!=''?$_POST['view']:($targ_w<200?$targ_w:200)), 'images/upload/',$tipo['mime']);

	

	return;

	exit;



}





/*

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

*/



function addMes($data,$meses){

	

	return date('Y-m-d',mktime(0,0,0,(substr($data,5,2) + $meses),substr($data,8,2),substr($data,0,4)));

	

}

function addDia($data,$dias){

	

	return date('Y-m-d',mktime(0,0,0,substr($data,5,2),(substr($data,8,2)+$dias),substr($data,0,4)));

	

}

function addMinuto($data,$minutos){
	
	$hora = substr($data, 11,2);
	$min  = (substr($data, 14,2)*1) + $minutos;

	return date('Y-m-d H:i:s',mktime($hora,$min,0,substr($data,5,2),substr($data,8,2),substr($data,0,4)));

}


/*

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

*/





/*

 --- FAZ O UPLOD E REDIMENSIONA A IMAGEM

*/

function upload($tmp, $nome, $largura,$alturaMax, $pasta,$tipo=null){



//'image/jpg','image/jpeg','image/JPG','image/pjpeg','image/png','image/gif'

if($tipo == 'image/gif'):

		

	$img = imagecreatefromgif($tmp);

	//$logo = imagecreatefromgif("images/logo.gif"); 

	

	$x   = imagesx($img);

	$y   = imagesy($img);

	$altura = ($largura*$y) / $x;

	if($altura>$alturaMax){

		$largura = ($alturaMax*$x)/$y;

		$altura = $alturaMax;

	}

	

	

	$nova = imagecreatetruecolor($largura, $altura);

	

	//$posx = ($largura - imagesx($logo))/2;

	//$posy = ($altura - imagesy($logo))/2;

	

	imagealphablending( $nova, false );

	imagesavealpha( $nova, true );

	

	imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);

	//imagecopymerge( $nova, $logo, $posx, $posy, 0, 0, imagesx($logo), imagesy($logo), 50);



	$nome = $nome.'.gif';

	imagepng($nova, "$pasta/$nome");

	imagedestroy($nova);

	imagedestroy($img);

	return($nome);

	

elseif($tipo == 'image/png'):

	$nome = $nome.'.png';

	$img = imagecreatefrompng($tmp);

	//$logo = imagecreatefromgif("images/logo.gif"); 

	

	

	$x   = imagesx($img);

	$y   = imagesy($img);

	$altura = ($largura*$y) / $x;

	if($altura>$alturaMax){

		$largura = ($alturaMax*$x)/$y;

		$altura = $alturaMax;

	}

	

	$nova = imagecreatetruecolor($largura, $altura);

	

	//$posx = ($largura - imagesx($logo))/2;

	//$posy = ($altura - imagesy($logo))/2;

	

	imagealphablending( $nova, false );

	imagesavealpha( $nova, true );

	

	imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);

	//imagecopymerge( $nova, $logo, $posx, $posy, 0, 0, imagesx($logo), imagesy($logo), 50);

	

	imagepng($nova, "$pasta/$nome");

	imagedestroy($nova);

	imagedestroy($img);

	return($nome);

	

elseif($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/JPG' || $tipo == 'image/pjpeg'):

	$nome = $nome.'.jpg';	

	$img = imagecreatefromjpeg($tmp);

	

	$x   = imagesx($img);

	$y   = imagesy($img);

	$altura = ($largura*$y) / $x;

	//return $alturaMax;

	if($altura>$alturaMax){

		$largura = ($alturaMax*$x)/$y;

		$altura = $alturaMax;

	}

	$nova = imagecreatetruecolor($largura, $altura);

	

	//$posx = ($largura - imagesx($logo))/2;

	//$posy = ($altura - imagesy($logo))/2;

	

	imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);

	

	imagejpeg($nova, "$pasta/$nome",100);

	imagedestroy($nova);

	imagedestroy($img);

	

	return($nome);

	

endif;

}

function uploadnoagua($tmp, $nome, $largura, $pasta,$tipo=null,$Cw=null,$Ch=null,$Cx=0,$Cy=0){



//'image/jpg','image/jpeg','image/JPG','image/pjpeg','image/png','image/gif'

if($tipo == 'image/gif'):

		

	$img = imagecreatefromgif($tmp);

	//$logo = imagecreatefromgif("images/logo.gif"); 

	

	$x   = imagesx($img);

	$y   = imagesy($img);

	$altura = ($largura*$y) / $x;

	$nova = imagecreatetruecolor($largura, $altura);

	

	$posx = ($largura - imagesx($logo))/2;

	$posy = ($altura - imagesy($logo))/2;

	

	imagealphablending( $nova, false );

	imagesavealpha( $nova, true );

	

	imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);

	//imagecopymerge( $nova, $logo, $posx, $posy, 0, 0, imagesx($logo), imagesy($logo), 50);



	

	imagepng($nova, "$pasta/$nome");

	imagedestroy($nova);

	imagedestroy($img);

	return($nome);

	

elseif($tipo == 'image/png'):

		

	$img = imagecreatefrompng($tmp);

	//$logo = imagecreatefromgif("images/logo.gif"); 

	

	

	

	if($Cw!=''&&$Ch!=''){

		$largura = $Cw;

		$altura = $Ch;

		$x = $Cw;

		$y = $Ch;

	}else{

		

		$x   = imagesx($img);

		$y   = imagesy($img);

		$altura = ($largura*$y) / $x;



	}

	$nova = imagecreatetruecolor($largura, $altura);

	/*

	$posx = ($largura - imagesx($logo))/2;

	$posy = ($altura - imagesy($logo))/2;

	*/

	

	

	





	

	imagealphablending( $nova, false );

	imagesavealpha( $nova, true );

	

	imagecopyresampled($nova, $img, 0, 0, $Cx, $Cy, $largura, $altura, $x, $y);

	//imagecopymerge( $nova, $logo, $posx, $posy, 0, 0, imagesx($logo), imagesy($logo), 50);

	

	imagepng($nova, "$pasta/$nome");

	imagedestroy($nova);

	imagedestroy($img);

	return($nome);

	

elseif($tipo == 'image/jpg' || $tipo == 'image/jpeg' || $tipo == 'image/JPG' || $tipo == 'image/pjpeg'):

		

	$img = imagecreatefromjpeg($tmp);

	//$logo = imagecreatefromgif("images/logo_ag.gif"); 

	

	

	if($Cw!=''&&$Ch!=''){

		$largura = $Cw;

		$altura = $Ch;

		$x = $Cw;

		$y = $Ch;

	}else{

		

		$x   = imagesx($img);

		$y   = imagesy($img);

		$altura = ($largura*$y) / $x;



	}

	

	

	$nova = imagecreatetruecolor($largura, $altura);

	

	//$posx = ($largura - imagesx($logo))/2;

	//$posy = ($altura - imagesy($logo))/2;

	

	imagecopyresampled($nova, $img, 0, 0, $Cx, $Cy, $largura, $altura, $x, $y);

	//imagecopymerge( $nova, $logo, $posx, $posy, 0, 0, imagesx($logo), imagesy($logo), 50);

	

	

	

	imagejpeg($nova, "$pasta/$nome",100);

	imagedestroy($nova);

	imagedestroy($img);

	return($nome);

	

endif;

}

/*

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

*/







/*------------*/



function alteraOrdemLinhaList($dados,$tabela){



	$dados = explode(',',$dados);

	for($i = 0;$i<=count($dados);$i++){

		if($dados[$i] !='' && $dados[$i] != '__,'){

			$aux = explode('__',$dados[$i]);

			$id = $aux[0];

			$ordem = $aux[1];

			if($id != '' && $ordem != ''){

				$row = DB::read($tabela);

				$row->id = $id;	

				$row->load();

				$row->order_by = $ordem;

				$row->update();

				

			}

			

				

		}

		

	}

		

}





function verificaCampoTabela($tabela,$campo){

	$q = new Model;

	$campos_tabela = $q->listaCampos($tabela);

	

	

	for($i = 0;$i<=count($campos_tabela);$i++){

		if($campos_tabela[$i]['Field'] == $campo){

			return true;	

		}

	}

	

	return false;

	

}

/* ------------------------------- */

function loadObj($obj,$param=null){

	$parametros = array();

	if(is_array($param)){

		foreach($param as $k=>$v){

			$_GET[$k] = $v;

		}

		

	}

	$url = ('pages/'.$obj.'.php');

	ob_start();

		if(is_file('pages/'.$obj.'/'.$obj.'.php')){

			if(is_file('pages/'.$obj.'/'.$obj.'.css.php')){
				require('pages/'.$obj.'/'.$obj.'.css.php');
			}
			
			require('pages/'.$obj.'/'.$obj.'.php');

			if(is_file('pages/'.$obj.'/'.$obj.'.vue.php')){
				require('pages/'.$obj.'/'.$obj.'.vue.php');
			}

		}else{
			require($url);
		}

	$ret = ob_get_clean();

	echo $ret;

	return;

}



/* ------------------------------- */

function getConfig($id){

	return getInfoItem($id);

	//$aux = DB::read('system_form');

	//$aux->id = $id;

	//$aux->load();

	//return $aux;

		

}



/* ------------------------------- */

function USE_JS($url){

	global $MAP;	

	$MAP['HEAD_INCLUDES_ADMIN'] .= '<script type="text/javascript" src="'.ROOT.$url.'"></script>'."\r\n";	

}

function USE_CSS($url){

	global $MAP;	

	$MAP['HEAD_INCLUDES_ADMIN'] .= '<link href="'.ROOT.$url.'" rel="stylesheet" type="text/css" />'."\r\n";	

}

function ADD_HEAD($content){

	global $MAP;	

	$MAP['HEAD_INCLUDES_ADMIN'] .= $content."\r\n";	

}



/*formata a página pra exibir os resultados ----------------------------------------------------------------------------------------------------------------------------*/

function formataFullPageRet($txt,$headIncludes,$type=null){

	global $MAP;	

	$headIncludes = $headIncludes.$MAP['HEAD_INCLUDES_ADMIN'];

	

	if($type == 'page'):

		$arrayBusca = array('ROOT/','(-((--HEAD_INCLUDES--))-)');

		$arrayTroca = array(ROOT,$headIncludes);

	else:

		$arrayBusca = array('ROOT/','(-((--HEAD_INCLUDES--))-)','/adm-');

		$arrayTroca = array(ROOT,$headIncludes,'/'.PREFIX_PAGE.'-');

	endif;

	

	$ret = str_replace($arrayBusca,$arrayTroca,$txt);



	return $ret;

}
function confLogin($login,$edit=''){

	$aux = DB::read('system_admin');
	$aux->login = $login;
	$aux->load("",($edit != ''?"id <> '".$edit."'":''));

	if($aux->size()>0){

		return 'Este endereço de e-mail já esta em uso';	

	}else{

		return '';

	}

}

function thumb($img_path,$w='',$h=''){

	$path = array_reverse(explode('/',$img_path));

	$img = $path[0];

	$caminho = str_replace($img,'',$img_path);

	

	$novo_nome_imagem = 'thumb_'.($w!=''?'_'.$w:'').($h!=''?'_'.$h:'').$img;

	if(!is_file($caminho.$novo_nome_imagem)){

		resizeImage($img_path,$w,$h,$caminho.$novo_nome_imagem,true);	

	}

	return $caminho.$novo_nome_imagem;	

}




/**/

function cidade($cid,$est=false){

	

	$cidade = DB::read("cidade");

	$cidade->id = $cid;

	$cidade->load();	

	



	return $cidade->nome.($est==true?' - '.$cidade->uf:'');

}



function getCoordenadas($address){

		 

	$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

	 

	$output= json_decode($geocode);

	 

	$lat = $output->results[0]->geometry->location->lat;

	$long = $output->results[0]->geometry->location->lng;	

	

	if($lat == '' || $long == ''){

		return false;	

	}

	$ret['lat'] = $lat;

	$ret['lng'] = $long;

	return $ret;

}




function config($chave){
	$aux = DB_Class::make("system_config")->_loadAll();
	return $aux->$chave;
}


function somaDataAMD($date, $delta_years = 0, $delta_months = 0, $delta_days = 0, $delta_hora =0, $delta_min=0, $delta_seg = 0)
{
	$dateOrg=$date;
  //usar data no formado AAAA-MM-DD
 
  // delta_years adjustment:
  // Use this to adjust for next and previous years.
  // Add the $delta_years to the current year and make the new date.
 
  if ($delta_years != 0) {
	// Split the date into its components.
	list($year, $month, $day) = explode("-", $date);
	// Careful to check for leap year effects!
	if ($month == 2 && $day == 29) {
		// Check the number of days in the month/year, with the day set to 1.
		$tmp_date = date("Y-m", @mktime(1, 0, 0, $month, 1, $year + $delta_years));
		list($new_year, $new_month) = explode("-", $tmp_date);
		$days_in_month = numeroDeDiasNoMes($new_year, $new_month);
		// Lower the day value if it exceeds the number of days in the new month/year.
		if ($days_in_month < $day) { $day = $days_in_month; }
		$date = $new_year . '-' . $month . '-' . $day;
    } else {
		$new_year = $year + $delta_years;
		$date = sprintf("%04d-%02d-%02d", $new_year, $month, $day);
	}
  }
 
  // delta_months adjustment:
  // Use this to adjust for next and previous months.
  // Note: This DOES NOT subtract 30 days! 
  // Use $delta_days for that type of calculation.
  // Add the $delta_months to the current month and make the new date.
 
  if ($delta_months != 0) {
	// Split the date into its components.
	list($year, $month, $day) = explode("-", $date);
	// Calculate New Month and Year
	$new_year = $year;
	$new_month = $month + $delta_months;
	if ($delta_months < -840 || $delta_months > 840) { $new_month = $month; } // Bad Delta
	if ($delta_months > 0) { // Adding Months
		while ($new_month > 12) { // Adjust so $new_month is between 1 and 12.
			$new_year++;
			$new_month -= 12;
		}
	} elseif ($delta_months < 0) { // Subtracting Months
		while ($new_month < 1) { // Adjust so $new_month is between 1 and 12.
			$new_year--;
			$new_month += 12;
		}
	}
	// Careful to check for number of days in the new month!
	$days_in_month = numeroDeDiasNoMes($new_year, $new_month);
	// Lower the day value if it exceeds the number of days in the new month/year.
	if ($days_in_month < $day) { $day = $days_in_month; }
	$date = sprintf("%04d-%02d-%02d", $new_year, $new_month, $day);
  }
 
  // delta_days adjustment:
  // Use this to adjust for next and previous days.
  // Add the $delta_days to the current day and make the new date.
 
 
  list($hor, $min, $seg) = explode(":", substr($dateOrg,11));
	 
  if ($delta_days != 0) {
	// Split the date into its components.
	list($year, $month, $day) = explode("-", substr($date,0,10));

	// Create New Date
	$date = date("Y-m-d", @mktime(1, 0, 0, $month, $day, $year) + $delta_days*24*60*60);
  }
 
  // Check Valid Date, Use for TroubleShooting
  //list($year, $month, $day) = explode("-", $date);
  //$valid = checkdate($month, $day, $year);

  //if (!$valid)  return "Error, function somaDataAMD: Could not process valid date!";
 
  
  $deltatempo = $delta_seg + $delta_min*60 + $delta_hora*60*60;

  if($deltatempo != 0 || $hor>0 || $min>0 || $seg>0){
  	
		list($year, $month, $day) = explode("-", substr($date,0,10));
	
  		$date = date("Y-m-d H:i:s", @mktime($hor, $min, $seg, $month, $day, $year) + $deltatempo);

  }
 
 
  return $date;
}
 
//-----------------------------------------------------------------------------------------
 
function numeroDeDiasNoMes($year, $month)
{
  $days_in_the_month = array (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  if ($month != 2) return $days_in_the_month[$month - 1];
  return (checkdate($month, 29, $year)) ? 29 : 28;
}
 

 function isDebug(){

 	if(is_dir('system')){
 		return true;
 	}

 	return false;
 }

 function gen_uuid() {
 $uuid = array(
  'time_low'  => 0,
  'time_mid'  => 0,
  'time_hi'  => 0,
  'clock_seq_hi' => 0,
  'clock_seq_low' => 0,
  'node'   => array()
 );
 
 $uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
 $uuid['time_mid'] = mt_rand(0, 0xffff);
 $uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
 $uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
 $uuid['clock_seq_low'] = mt_rand(0, 255);
 
 for ($i = 0; $i < 6; $i++) {
  $uuid['node'][$i] = mt_rand(0, 255);
 }
 
 $uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
  $uuid['time_low'],
  $uuid['time_mid'],
  $uuid['time_hi'],
  $uuid['clock_seq_hi'],
  $uuid['clock_seq_low'],
  $uuid['node'][0],
  $uuid['node'][1],
  $uuid['node'][2],
  $uuid['node'][3],
  $uuid['node'][4],
  $uuid['node'][5]
 );
 
 return $uuid;
}
