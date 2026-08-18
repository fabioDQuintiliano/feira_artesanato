<?php
/**
 * Hooks do CRUD admin da configuração geral do encontro.
 */

function auto_preupdate_ceramistas_config($id = null)
{
	auto_ceramistas_config_normaliza_campos();
	return true;
}

function auto_ceramistas_config_normaliza_campos()
{
	foreach (array('local', 'local_complemento', 'cidade', 'endereco', 'mapa_query', 'mensagem_whatsapp') as $campo) {
		if (isset($_POST[$campo])) {
			$_POST[$campo] = trim((string) $_POST[$campo]);
		}
	}

	if (isset($_POST['uf'])) {
		$uf = strtoupper(preg_replace('/[^a-zA-Z]/', '', (string) $_POST['uf']));
		$_POST['uf'] = substr($uf, 0, 2);
	}

	if (isset($_POST['whatsapp'])) {
		$digitos = preg_replace('/\D+/', '', (string) $_POST['whatsapp']);
		$_POST['whatsapp'] = $digitos !== '' ? $digitos : '';
	}

	if (isset($_POST['mensagem_whatsapp']) && $_POST['mensagem_whatsapp'] === '') {
		$_POST['mensagem_whatsapp'] = 'Olá! Quero saber mais sobre o 2º Encontro de Ceramistas em Arceburgo.';
	}
}

function auto_pre_listagem_ceramistas_config($form = null)
{
	if (!empty($_GET['edit']) || !empty($_GET['view']) || !empty($_GET['add'])) {
		return;
	}
	if (!class_exists('DAO') || !method_exists('DAO', 'ceramistas_config')) {
		return;
	}
	$item = 0;
	if (!empty($_GET[':item'])) {
		$item = (int) $_GET[':item'];
	} elseif (!empty($_GET['item'])) {
		$item = (int) $_GET['item'];
	}
	if ($item <= 0) {
		return;
	}
	$dao = DAO::ceramistas_config()->_loadAll('id ASC LIMIT 1');
	if ($dao && $dao->size()) {
		location(ROOT.'adm-home?item='.$item.'&edit='.(int) $dao->id);
	}
}
