<?php
/**
 * Helpers de codegen do painel (escrita atômica + execução controlada).
 */

if (!function_exists('system_write_with_flock')) {
	/**
	 * Fallback: grava direto no destino com flock (quando o diretório não permite criar .tmp).
	 */
	function system_write_with_flock($path, $content)
	{
		$fp = fopen($path, 'c+b');
		if (!$fp) {
			throw new RuntimeException('Não foi possível abrir: '.$path);
		}

		if (!flock($fp, LOCK_EX)) {
			fclose($fp);
			throw new RuntimeException('Não foi possível bloquear: '.$path);
		}

		ftruncate($fp, 0);
		rewind($fp);
		$bytes = fwrite($fp, $content);
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);

		if ($bytes === false) {
			throw new RuntimeException('Falha ao escrever: '.$path);
		}

		return true;
	}
}

if (!function_exists('system_atomic_write')) {
	/**
	 * Grava conteúdo via arquivo temporário + rename quando possível;
	 * senão, usa flock no arquivo destino (mesmo comportamento do gerador antigo).
	 */
	function system_atomic_write($path, $content)
	{
		$dir = dirname($path);
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
				throw new RuntimeException('Não foi possível criar diretório: '.$dir);
			}
		}

		$realDir = realpath($dir);
		if ($realDir === false) {
			$realDir = $dir;
		}
		$path = $realDir.DIRECTORY_SEPARATOR.basename($path);

		$tmp = $realDir.DIRECTORY_SEPARATOR.basename($path).'.'.getmypid().'.'.str_replace('.', '', uniqid('', true)).'.tmp';
		$fp = @fopen($tmp, 'wb');

		if (!$fp) {
			// Diretório sem permissão de criar arquivos novos (comum no Docker):
			// ainda dá para sobrescrever o destino existente.
			return system_write_with_flock($path, $content);
		}

		if (!flock($fp, LOCK_EX)) {
			fclose($fp);
			@unlink($tmp);
			return system_write_with_flock($path, $content);
		}

		$bytes = fwrite($fp, $content);
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);

		if ($bytes === false) {
			@unlink($tmp);
			return system_write_with_flock($path, $content);
		}

		if (!@rename($tmp, $path)) {
			@unlink($tmp);
			return system_write_with_flock($path, $content);
		}

		return true;
	}
}

if (!function_exists('system_run_codegen')) {
	/**
	 * Executa os geradores de definições, menus, pages e containers.
	 */
	function system_run_codegen()
	{
		include __DIR__.'/gera_definicoes_de_tabelas.php';
		include __DIR__.'/gera_arquivos_de_listagem.php';
	}
}
