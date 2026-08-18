<?php
header('Content-Type: text/html; charset=utf-8');
//header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
session_start();
date_default_timezone_set('America/Sao_Paulo');
//error_reporting(E_ERROR  | E_PARSE);
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL & ~E_WARNING);

set_time_limit(0);
/*
 --- Arquivo de configurações do sistema.
 --- Esse arquivo define as caracteristicas do sistema, tais como: Nome do site, diretório padrão e qualquer informação necessaria para o site.
*/
define('GLOBAL_CHARSET','UTF-8');

define('PROJETO_NOME','Feira Artesanato');



define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: '');

//define('MAPS_KEY', getenv('MAPS_KEY') ?: '');
//CONEXAO COM O BANCO DE DADOS


$producao = false;
$appUrl = trim((string) getenv('APP_URL'));

if($appUrl !== ''){

	define('ROOT', rtrim($appUrl, '/').'/');
	define('HTTP', (string) (parse_url(ROOT, PHP_URL_SCHEME) ?: 'http').'://');

	define('HOST_BD', getenv('DB_HOST') ?: 'db');
	define('USUARIO_BD', getenv('DB_USERNAME') ?: 'feira');
	define('SENHA_BD', getenv('DB_PASSWORD') ?: 'feira');
	define('BANCO_BD', getenv('DB_DATABASE') ?: 'admin_feira');

}elseif($producao){


	define('ROOT', 'https://'.$_SERVER['HTTP_HOST'].'/');
	define('HTTP', 'https://');

	 define('HOST_BD', getenv('DB_HOST') ?: 'localhost');
	 define('USUARIO_BD', getenv('DB_USERNAME') ?: '');
	 define('SENHA_BD', getenv('DB_PASSWORD') ?: '');
	 define('BANCO_BD', getenv('DB_DATABASE') ?: '');

}else{

	define('ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/feira/');
	define('HTTP', 'http://');

	 define('HOST_BD','localhost');
	// //usuario
	 define('USUARIO_BD','fabio');
	// //senha
	 define('SENHA_BD','123456');
	// //banco de dados
	 define('BANCO_BD','admin_feira');






}





define('PHP_LOGIN_EXPIRES_MINS',60*24*365);


define('MAILJET_API_KEY', getenv('MAILJET_API_KEY') ?: '');
define('MAILJET_SECREDT', getenv('MAILJET_SECRET') ?: '');

/*
 --- Ambiente e flags do gerador do painel (system/).
 --- Em production: codegen e IDE desligados por padrão; use system-rebuild ou SYSTEM_CODEGEN=1.
*/
if (!defined('APP_ENV')) {
	define('APP_ENV', strtolower(trim((string) (getenv('APP_ENV') ?: 'production'))));
}
$__systemIsDev = in_array(APP_ENV, array('development', 'dev', 'local'), true);
if (!function_exists('__env_flag')) {
	function __env_flag($name, $default)
	{
		$v = getenv($name);
		if ($v === false || $v === '') {
			return (bool) $default;
		}
		return (bool) filter_var($v, FILTER_VALIDATE_BOOLEAN);
	}
}
if (!defined('SYSTEM_CODEGEN')) {
	define('SYSTEM_CODEGEN', __env_flag('SYSTEM_CODEGEN', $__systemIsDev));
}
if (!defined('SYSTEM_IDE_ENABLED')) {
	define('SYSTEM_IDE_ENABLED', __env_flag('SYSTEM_IDE_ENABLED', $__systemIsDev));
}
if (!defined('SYSTEM_AUX_BAR')) {
	define('SYSTEM_AUX_BAR', __env_flag('SYSTEM_AUX_BAR', $__systemIsDev));
}
unset($__systemIsDev);

/*
---- define um arquivo de idiomas.
---- para adicionar outro idioma, basta criar o arquivo dentro da pasta "lang" e definir as iniciais do arquivo. O nome do arquivo deve ser: iniciais.lang.php
*/
define('LANG','pt');

