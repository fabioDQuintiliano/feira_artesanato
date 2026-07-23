<?php
declare(strict_types=1);

require_once __DIR__ . '/cron_eventos_bootstrap.php';

$report = [
	'ok' => true,
	'inicio' => date('Y-m-d H:i:s'),
	'jobs_criados' => 0,
];

try {
	$db = (new \Database())->instance();
	$fila = new \Backend\v1\FilaEventos($db);
	$report['jobs_criados'] = $fila->enfileirarLinksDevidos();
	$report['fim'] = date('Y-m-d H:i:s');
} catch (\Throwable $e) {
	$report['ok'] = false;
	$report['erro'] = $e->getMessage();
	$report['fim'] = date('Y-m-d H:i:s');
	if (PHP_SAPI !== 'cli') {
		http_response_code(500);
	}
}

echo json_encode($report, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
exit;
