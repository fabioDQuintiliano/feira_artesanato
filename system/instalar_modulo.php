<?php
/**
 * Instala um módulo admin a partir de system/modulos/{id}.php
 *
 * Uso:
 *   docker compose exec -u www-data app php system/instalar_modulo.php
 *   docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_expositores
 */
require_once dirname(__DIR__).'/front_includes.php';

$arg = isset($argv[1]) ? trim($argv[1]) : '';

try {
	if ($arg === '' || $arg === 'list' || $arg === '--list') {
		$lista = \Sistema\Admin\Modulo::listarDefinicoes();
		if (count($lista) === 0) {
			echo "Nenhuma definição em system/modulos/\n";
			exit(0);
		}
		echo "Módulos disponíveis:\n";
		foreach ($lista as $id) {
			echo "  - {$id}\n";
		}
		echo "\nInstalar: php system/instalar_modulo.php {id}\n";
		echo "Prefira: docker compose exec -u www-data app php system/instalar_modulo.php {id}\n";
		exit(0);
	}

	echo "Instalando módulo {$arg}...\n";
	$ret = \Sistema\Admin\Modulo::instalarPorId($arg);
	echo "OK.\n";
	if (!empty($ret['forms'])) {
		foreach ($ret['forms'] as $def => $id) {
			echo "  form {$def} id={$id}\n";
		}
	}
} catch (Exception $e) {
	echo 'Erro: '.$e->getMessage()."\n";
	exit(1);
}
