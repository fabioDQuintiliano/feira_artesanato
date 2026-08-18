<?php
/**
 * Hooks do CRUD admin da programação do encontro.
 */

function auto_preinsert_programacao()
{
	auto_programacao_normaliza_campos();
	return true;
}

function auto_preupdate_programacao($id = null)
{
	auto_programacao_normaliza_campos($id);
	return true;
}

function auto_programacao_normaliza_campos($idAtual = null)
{
	if (isset($_POST['titulo'])) {
		$_POST['titulo'] = trim((string) $_POST['titulo']);
	}

	if (isset($_POST['hora_inicio']) && preg_match('/^\d{2}:\d{2}$/', (string) $_POST['hora_inicio'])) {
		$_POST['hora_inicio'] .= ':00';
	}

	if (isset($_POST['hora_fim'])) {
		$fim = trim((string) $_POST['hora_fim']);
		if ($fim === '') {
			$_POST['hora_fim'] = null;
		} elseif (preg_match('/^\d{2}:\d{2}$/', $fim)) {
			$_POST['hora_fim'] = $fim.':00';
		}
	}

	if (!isset($_POST['icone']) || $_POST['icone'] === '' || $_POST['icone'] === '-1') {
		$_POST['icone'] = 'sun';
	}

	if (!isset($_POST['ativo']) || $_POST['ativo'] === '' || $_POST['ativo'] === '-1') {
		$_POST['ativo'] = 1;
	} else {
		$_POST['ativo'] = ((int) $_POST['ativo'] === 1) ? 1 : 0;
	}

	if (!isset($_POST['destaque']) || $_POST['destaque'] === '' || $_POST['destaque'] === '-1') {
		$_POST['destaque'] = 0;
	} else {
		$_POST['destaque'] = ((int) $_POST['destaque'] === 1) ? 1 : 0;
	}

	if (isset($_POST['ordem']) && $_POST['ordem'] === '') {
		$_POST['ordem'] = 0;
	}
}

function auto_exibe_hora($id, $valor, $modo = 'list')
{
	$valor = trim((string) $valor);
	if ($valor === '') {
		return '';
	}
	return substr($valor, 0, 5);
}
