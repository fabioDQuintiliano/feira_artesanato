<?php
/**
 * Atalho: instala o módulo admin de expositores.
 * Prefira: docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_expositores
 */
require_once __DIR__ . '/front_includes.php';

try {
	echo "Instalando módulo ceramistas_expositores...\n";
	$ret = \Sistema\Admin\Modulo::instalarPorId('ceramistas_expositores');
	echo "Módulo admin de expositores pronto.\n";
	if (!empty($ret['forms'])) {
		foreach ($ret['forms'] as $def => $id) {
			echo "  form {$def} id={$id}\n";
		}
	}
} catch (Exception $e) {
	echo 'Erro: '.$e->getMessage()."\n";
	exit(1);
}
