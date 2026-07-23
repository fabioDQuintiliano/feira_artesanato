<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);

if (!defined('HOST_BD')) {
	$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
	require_once $projectRoot . '/config.php';
}

require_once $projectRoot . '/autoload.php';
require_once $projectRoot . '/vendor/autoload.php';
require_once $projectRoot . '/functions/auto_db.php';

date_default_timezone_set('America/Sao_Paulo');

if (PHP_SAPI !== 'cli') {
	header('Content-Type: application/json; charset=utf-8');

	$cronToken = (defined('CRON_TOKEN') ? constant('CRON_TOKEN') : null) ?: getenv('CRON_TOKEN');
	if ($cronToken) {
		$tokenRecebido = $_GET['token'] ?? '';
		if (!hash_equals((string) $cronToken, (string) $tokenRecebido)) {
			http_response_code(403);
			echo json_encode(['ok' => false, 'erro' => 'token inválido'], JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}
