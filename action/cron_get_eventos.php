<?php
declare(strict_types=1);

/**
 * Worker da fila de eventos.
 *
 * Cada execução reserva e processa no máximo um link:
 * php action/cron_get_eventos.php
 */

require_once __DIR__ . '/cron_eventos_bootstrap.php';

$report = [
	'ok' => true,
	'inicio' => date('Y-m-d H:i:s'),
	'job' => null,
];

try {
	$db = (new \Database())->instance();
	$fila = new \Backend\v1\FilaEventos($db);
	$job = $fila->reservarProximo();

	if ($job === null) {
		$report['mensagem'] = 'Nenhum link disponível para processamento.';
		$report['fim'] = date('Y-m-d H:i:s');
		echo json_encode($report, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
		exit;
	}

	$report['job'] = [
		'id' => (int) $job['id'],
		'link_id' => (int) $job['link_id'],
		'link' => (string) $job['link'],
		'tentativa' => (int) $job['tentativas'],
	];

	try {
		$extrator = new \Backend\v1\ExtratorEventosGemini();
		$eventos = $extrator->extrair((string) $job['link']);

		$processador = new \Backend\v1\ProcessadorEventos($db);
		$contadores = $processador->processar(
			$eventos,
			(string) $job['link'],
			(int) $job['created_by'],
			isset($job['estabelecimento']) ? (int) $job['estabelecimento'] : null
		);

		$fila->concluir((int) $job['id'], (int) $job['link_id'], $contadores);
		$report['job']['status'] = 'concluido';
		$report['job']['eventos_extraidos'] = count($eventos);
		$report['job'] += $contadores;
	} catch (\Throwable $e) {
		$resultadoFalha = $fila->falhar(
			(int) $job['id'],
			(int) $job['tentativas'],
			$e->getMessage()
		);

		$report['ok'] = false;
		$report['job']['status'] = $resultadoFalha['status'];
		$report['job']['proxima_tentativa'] = $resultadoFalha['proxima_tentativa'];
		$report['job']['erro'] = $e->getMessage();
		if (PHP_SAPI !== 'cli') {
			http_response_code(500);
		}
	}
} catch (\Throwable $e) {
	$report['ok'] = false;
	$report['erro_fatal'] = $e->getMessage();
	if (PHP_SAPI !== 'cli') {
		http_response_code(500);
	}
}

$report['fim'] = date('Y-m-d H:i:s');
echo json_encode($report, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
exit;