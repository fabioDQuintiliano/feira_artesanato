<?php
/**
 * Hooks do CRUD admin de expositores (2º Encontro de Ceramistas).
 */

function auto_preinsert_expositores()
{
	auto_expositores_normaliza_campos();
	return true;
}

function auto_preupdate_expositores($id = null)
{
	auto_expositores_normaliza_campos($id);
	return true;
}

function auto_posinsert_expositores_fotos($id)
{
	auto_expositores_sync_foto_destaque($id);
}

function auto_posupdate_expositores_fotos($id)
{
	auto_expositores_sync_foto_destaque($id);
}

function auto_expositores_normaliza_campos($idAtual = null)
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
		$_POST['slug'] = auto_expositores_slug_unico($slug, $idAtual);
	}

	if (isset($_POST['instagram'])) {
		$_POST['instagram'] = auto_expositores_instagram($_POST['instagram']);
	}

	if (isset($_POST['whatsapp'])) {
		$whatsapp = preg_replace('/\D+/', '', (string) $_POST['whatsapp']);
		$_POST['whatsapp'] = $whatsapp !== '' ? $whatsapp : null;
	}

	if (!isset($_POST['grupo']) || $_POST['grupo'] === '' || $_POST['grupo'] === '-1') {
		$_POST['grupo'] = 'artesao';
	}

	if (!isset($_POST['ativo']) || $_POST['ativo'] === '' || $_POST['ativo'] === '-1') {
		$_POST['ativo'] = 1;
	} else {
		$_POST['ativo'] = ((int) $_POST['ativo'] === 1) ? 1 : 0;
	}

	if (isset($_POST['ordem']) && $_POST['ordem'] === '') {
		$_POST['ordem'] = 0;
	}

	if (isset($_POST['descricao']) && $_POST['descricao'] === '') {
		$_POST['descricao'] = ' ';
	}
}

function auto_expositores_instagram($valor)
{
	$valor = trim((string) $valor);
	if ($valor === '') {
		return '';
	}
	$valor = preg_replace('#^https?://(www\.)?instagram\.com/#i', '', $valor);
	return trim($valor, '@/ ');
}

function auto_expositores_slug_unico($slug, $idAtual = null)
{
	$slug = preg_replace('/[^a-z0-9_\-]/', '', strtolower((string) $slug));
	if ($slug === '') {
		$slug = 'expositor';
	}

	$base = $slug;
	$n = 2;
	while (auto_expositores_slug_existe($slug, $idAtual)) {
		$slug = $base.'-'.$n;
		$n++;
		if ($n > 50) {
			$slug = $base.'-'.substr(md5(uniqid('', true)), 0, 6);
			break;
		}
	}

	return $slug;
}

function auto_expositores_slug_existe($slug, $idAtual = null)
{
	$dao = DAO::expositores()->_slug($slug)->_loadAll();
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

function auto_expositores_sync_foto_destaque($fotoId)
{
	$fotoId = (int) $fotoId;
	if ($fotoId <= 0) {
		return;
	}

	$foto = DAO::expositores_fotos()->_id($fotoId)->_loadAll();
	if (!$foto || !$foto->size()) {
		return;
	}

	if ((int) $foto->destaque !== 1) {
		return;
	}

	$expositorId = (int) $foto->expositor_id;
	if ($expositorId <= 0 || trim((string) $foto->arquivo) === '') {
		return;
	}

	$exp = DAO::expositores()->_id($expositorId)->_loadAll();
	if (!$exp || !$exp->size()) {
		return;
	}

	$exp->foto_destaque = $foto->arquivo;
	$exp->_update();
}
