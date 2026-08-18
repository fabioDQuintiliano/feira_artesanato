<?php
/**
 * Hooks do CRUD admin das atrações musicais do encontro.
 */

function auto_preinsert_atracoes_musicais()
{
	auto_atracoes_musicais_normaliza_campos();
	return true;
}

function auto_preupdate_atracoes_musicais($id = null)
{
	auto_atracoes_musicais_normaliza_campos($id);
	return true;
}

function auto_atracoes_musicais_normaliza_campos($idAtual = null)
{
	if (isset($_POST['nome'])) {
		$_POST['nome'] = trim((string) $_POST['nome']);
	}

	$slug = isset($_POST['slug']) ? trim((string) $_POST['slug']) : '';
	if ($slug === '' && !empty($_POST['nome'])) {
		$slug = url_amigavel($_POST['nome']);
	} elseif ($slug !== '') {
		$slug = url_amigavel($slug);
	}
	if ($slug !== '') {
		$_POST['slug'] = auto_atracoes_musicais_slug_unico($slug, $idAtual);
	}

	if (isset($_POST['hora']) && preg_match('/^\d{2}:\d{2}$/', (string) $_POST['hora'])) {
		$_POST['hora'] .= ':00';
	}

	if (isset($_POST['instagram'])) {
		$_POST['instagram'] = auto_atracoes_musicais_instagram($_POST['instagram']);
	}

	if (isset($_POST['site'])) {
		$_POST['site'] = auto_atracoes_musicais_site($_POST['site']);
	}

	if (isset($_POST['cartaz_alt'])) {
		$alt = trim((string) $_POST['cartaz_alt']);
		if ($alt === '' && !empty($_POST['nome'])) {
			$alt = 'Cartaz: '.$_POST['nome'];
		}
		$_POST['cartaz_alt'] = $alt !== '' ? $alt : null;
	}

	if (!isset($_POST['ativo']) || $_POST['ativo'] === '' || $_POST['ativo'] === '-1') {
		$_POST['ativo'] = 1;
	} else {
		$_POST['ativo'] = ((int) $_POST['ativo'] === 1) ? 1 : 0;
	}

	if (isset($_POST['ordem']) && $_POST['ordem'] === '') {
		$_POST['ordem'] = 0;
	}

	if (isset($_POST['local']) && trim((string) $_POST['local']) === '') {
		$_POST['local'] = 'Calçadão Pedro Furlan';
	}
}

function auto_atracoes_musicais_instagram($valor)
{
	$valor = trim((string) $valor);
	if ($valor === '') {
		return '';
	}
	$valor = preg_replace('#^https?://(www\.)?instagram\.com/#i', '', $valor);
	return trim($valor, '@/ ');
}

function auto_atracoes_musicais_site($valor)
{
	$valor = trim((string) $valor);
	if ($valor === '') {
		return null;
	}
	if (!preg_match('#^https?://#i', $valor)) {
		$valor = 'https://'.$valor;
	}
	return $valor;
}

function auto_atracoes_musicais_slug_unico($slug, $idAtual = null)
{
	$slug = preg_replace('/[^a-z0-9_\-]/', '', strtolower((string) $slug));
	if ($slug === '') {
		$slug = 'atracao';
	}

	$base = $slug;
	$n = 2;
	while (auto_atracoes_musicais_slug_existe($slug, $idAtual)) {
		$slug = $base.'-'.$n;
		$n++;
		if ($n > 50) {
			$slug = $base.'-'.substr(md5(uniqid('', true)), 0, 6);
			break;
		}
	}

	return $slug;
}

function auto_atracoes_musicais_slug_existe($slug, $idAtual = null)
{
	if (!class_exists('DAO') || !method_exists('DAO', 'atracoes_musicais')) {
		return false;
	}

	$dao = DAO::atracoes_musicais()->_slug($slug)->_loadAll();
	if (!$dao || !$dao->size()) {
		return false;
	}

	$idAtual = (int) $idAtual;
	do {
		if ($idAtual <= 0 || (int) $dao->id !== $idAtual) {
			return true;
		}
	} while ($dao->next());

	return false;
}
