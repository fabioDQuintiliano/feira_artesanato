<?php
namespace Sistema\Admin;

/**
 * Instalador idempotente de módulos CRUD do painel (system_form / inputs / menu).
 */
class Modulo
{
	public static function listarDefinicoes()
	{
		$dir = self::diretorioDefinicoes();
		$lista = array();
		if (!is_dir($dir)) {
			return $lista;
		}
		$itens = scandir($dir);
		foreach ($itens as $item) {
			if (substr($item, -4) !== '.php' || $item[0] === '.') {
				continue;
			}
			$lista[] = substr($item, 0, -4);
		}
		sort($lista);
		return $lista;
	}

	public static function instalarArquivo($caminho)
	{
		if (!is_file($caminho)) {
			throw new \RuntimeException('Definição não encontrada: '.$caminho);
		}
		$def = include $caminho;
		if (!is_array($def)) {
			throw new \RuntimeException('A definição deve retornar um array: '.$caminho);
		}
		return self::instalar($def);
	}

	public static function instalarPorId($id)
	{
		$id = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $id);
		$caminho = self::diretorioDefinicoes().DIRECTORY_SEPARATOR.$id.'.php';
		return self::instalarArquivo($caminho);
	}

	public static function instalar(array $def)
	{
		$q = new \Model;
		$resultado = array(
			'id' => isset($def['id']) ? $def['id'] : '',
			'forms' => array(),
			'menus' => array(),
		);

		if (!empty($def['sql']) && is_array($def['sql'])) {
			foreach ($def['sql'] as $sql) {
				self::executarSql($sql);
			}
		}

		if (!empty($def['schema_sql']) && is_file($def['schema_sql'])) {
			self::executarArquivoSql($def['schema_sql']);
		}

		$forms = isset($def['forms']) && is_array($def['forms']) ? $def['forms'] : array();
		foreach ($forms as $form) {
			$id = self::upsertForm($q, $form, $resultado['forms']);
			$arquivo = $form['arquivo_def'];
			$resultado['forms'][$arquivo] = $id;
			if (!empty($form['campos']) && is_array($form['campos'])) {
				foreach ($form['campos'] as $campo) {
					self::upsertInput($q, $id, $campo);
				}
			}
		}

		foreach ($forms as $form) {
			$id = (int) $resultado['forms'][$form['arquivo_def']];
			self::atualizarRelacionados($q, $id, $form, $resultado['forms']);
			if (!empty($form['menu'])) {
				$menu = self::ensureMenu($q, $form);
				$resultado['menus'][$form['menu']] = $menu;
			}
			self::grantForm($form);
		}

		if (empty($def['sem_codegen'])) {
			require_once dirname(__DIR__, 3).'/system/codegen_helpers.php';
			system_run_codegen();
			self::corrigirPermissoesGeradas();
		}

		return $resultado;
	}

	public static function diretorioDefinicoes()
	{
		return dirname(__DIR__, 3).'/system/modulos';
	}

	public static function joinVazio()
	{
		return serialize(array(
			'nome_bt' => array(''),
			'chave_primaria' => array(''),
			'tabela_join' => array(''),
			'chave_estrangeira' => array(''),
		));
	}

	public static function botoesVazio()
	{
		return serialize(array(''));
	}

	public static function corrigirPermissoesGeradas()
	{
		$root = dirname(__DIR__, 3);
		$paths = array(
			$root.'/functions/__list_functions.php',
			$root.'/tables',
			$root.'/admin/exe_system',
			$root.'/containers/exe_system',
		);

		$www = function_exists('posix_getpwnam') ? posix_getpwnam('www-data') : false;
		$uid = $www ? (int) $www['uid'] : null;
		$gid = $www ? (int) $www['gid'] : null;

		$fix = function ($path) use ($uid, $gid, &$fix) {
			if (!file_exists($path)) {
				return;
			}
			if ($uid !== null) {
				@chown($path, $uid);
			}
			if ($gid !== null) {
				@chgrp($path, $gid);
			}
			if (is_dir($path)) {
				@chmod($path, 0775);
				$itens = @scandir($path);
				if (is_array($itens)) {
					foreach ($itens as $item) {
						if ($item === '.' || $item === '..') {
							continue;
						}
						$fix($path.DIRECTORY_SEPARATOR.$item);
					}
				}
				return;
			}
			$modo = (basename($path) === '__list_functions.php') ? 0666 : 0664;
			@chmod($path, $modo);
		};

		foreach ($paths as $path) {
			$fix($path);
		}
	}

	private static function executarArquivoSql($caminho)
	{
		$schema = file_get_contents($caminho);
		if ($schema === false) {
			throw new \RuntimeException('Não foi possível ler SQL: '.$caminho);
		}
		$statements = preg_split('/;\s*[\r\n]+/', $schema);
		foreach ($statements as $stmt) {
			$clean = preg_replace('/^\s*--.*$/m', '', $stmt);
			$clean = trim($clean);
			if ($clean === '') {
				continue;
			}
			self::executarSql($clean);
		}
	}

	private static function executarSql($sql)
	{
		\DAO::doQuery($sql);
	}

	private static function formIdPorDef($arquivoDef)
	{
		$q = new \Model;
		$rows = $q->read('system_form', "arquivo_def = '".addslashes($arquivoDef)."'");
		if (!empty($rows[0]['id'])) {
			return (int) $rows[0]['id'];
		}
		return 0;
	}

	private static function upsertForm(\Model $q, array $form, array $idsConhecidos)
	{
		if (empty($form['arquivo_def']) || empty($form['tabela']) || empty($form['nome'])) {
			throw new \RuntimeException('Form exige arquivo_def, tabela e nome.');
		}

		$dados = array(
			'menu' => isset($form['menu']) ? $form['menu'] : '',
			'nome' => $form['nome'],
			'legenda' => isset($form['legenda']) ? $form['legenda'] : '',
			'id_form' => '',
			'class' => '',
			'method' => 'post',
			'action' => '',
			'url_retorno' => '',
			'tabela' => $form['tabela'],
			'arquivo_def' => $form['arquivo_def'],
			'preinsert' => isset($form['preinsert']) ? $form['preinsert'] : '',
			'preupdate' => isset($form['preupdate']) ? $form['preupdate'] : '',
			'predelete' => isset($form['predelete']) ? $form['predelete'] : '',
			'posinsert' => isset($form['posinsert']) ? $form['posinsert'] : '',
			'posupdate' => isset($form['posupdate']) ? $form['posupdate'] : '',
			'posdelete' => isset($form['posdelete']) ? $form['posdelete'] : '',
			'item_menu' => 0,
			'botoes_adicionais' => self::botoesVazio(),
			'join_n_n' => self::montarJoin($form, $idsConhecidos),
			'inserir' => 0,
			'editar' => 0,
			'deletar' => 0,
			'visualizar' => 0,
			'pdf' => 0,
			'sql_adicional' => '',
			'sql_ordem' => !empty($form['sql_ordem'])
				? base64_encode_checa($form['sql_ordem'])
				: '',
			'pre_listagem' => '',
			'pos_listagem' => '',
			'checkbox' => 0,
			'condicao_checkbox' => '',
			'listar_pagina' => '',
		);

		if (array_key_exists('inserir', $form)) {
			$dados['inserir'] = (int) $form['inserir'];
		}
		if (array_key_exists('editar', $form)) {
			$dados['editar'] = (int) $form['editar'];
		}
		if (array_key_exists('deletar', $form)) {
			$dados['deletar'] = (int) $form['deletar'];
		}
		if (array_key_exists('visualizar', $form)) {
			$dados['visualizar'] = (int) $form['visualizar'];
		}
		if (!empty($form['pre_listagem'])) {
			$dados['pre_listagem'] = $form['pre_listagem'];
		}

		$id = self::formIdPorDef($form['arquivo_def']);
		if ($id <= 0) {
			return (int) $q->insert('system_form', $dados);
		}
		$q->update('system_form', $dados, "id = '".$id."'");
		return $id;
	}

	private static function montarJoin(array $form, array $idsConhecidos)
	{
		if (empty($form['relacionados']) || !is_array($form['relacionados'])) {
			return self::joinVazio();
		}

		$nomeBt = array();
		$chavePri = array();
		$tabelaJoin = array();
		$chaveExt = array();

		foreach ($form['relacionados'] as $rel) {
			$alvo = isset($rel['form']) ? $rel['form'] : '';
			$idAlvo = isset($idsConhecidos[$alvo]) ? (int) $idsConhecidos[$alvo] : self::formIdPorDef($alvo);
			if ($idAlvo <= 0) {
				continue;
			}
			$nomeBt[] = isset($rel['botao']) ? $rel['botao'] : 'Itens';
			$chavePri[] = isset($rel['chave_primaria']) ? $rel['chave_primaria'] : 'id';
			$tabelaJoin[] = (string) $idAlvo;
			$chaveExt[] = isset($rel['chave_estrangeira']) ? $rel['chave_estrangeira'] : '';
		}

		if (count($nomeBt) === 0) {
			return self::joinVazio();
		}

		return serialize(array(
			'nome_bt' => $nomeBt,
			'chave_primaria' => $chavePri,
			'tabela_join' => $tabelaJoin,
			'chave_estrangeira' => $chaveExt,
		));
	}

	private static function atualizarRelacionados(\Model $q, $formId, array $form, array $idsConhecidos)
	{
		if (empty($form['relacionados'])) {
			return;
		}
		$q->update(
			'system_form',
			array('join_n_n' => self::montarJoin($form, $idsConhecidos)),
			"id = '".((int) $formId)."'"
		);
	}

	private static function upsertInput(\Model $q, $formId, array $campo)
	{
		if (empty($campo['campo_tabela'])) {
			throw new \RuntimeException('Campo exige campo_tabela.');
		}

		$existentes = $q->read(
			'system_inputs',
			"system_form = '".((int) $formId)."' AND campo_tabela = '".addslashes($campo['campo_tabela'])."'"
		);

		$defaults = array(
			'system_form' => (int) $formId,
			'nome' => '',
			'id_input' => '',
			'class' => ' ',
			'campo_tabela' => '',
			'type' => 'text',
			'caracteristica' => 1,
			'valor' => '',
			'join_tabela' => '',
			'join_chave_extrangeira' => '',
			'join_campo_exibido' => '',
			'sql_adicional' => '',
			'mascara' => '',
			'mascara_personalizada' => '',
			'exb_cadastro' => 1,
			'exb_edicao' => 1,
			'exb_listagem' => 0,
			'exb_filtro' => 0,
			'exb_view' => 1,
			'edicao_restrita' => 0,
			'validacao' => '',
			'aba' => '',
			'mapear_componente' => '',
			'parametros_componente' => '',
			'funcao_exibicao' => '',
			'linha_separadora' => 0,
			'secao' => '',
			'ordem' => 0,
		);

		$dados = array_merge($defaults, $campo);
		$dados['system_form'] = (int) $formId;

		if (!empty($existentes[0]['id'])) {
			$id = (int) $existentes[0]['id'];
			$q->update('system_inputs', $dados, "id = '".$id."'");
			return $id;
		}

		return (int) $q->insert('system_inputs', $dados);
	}

	private static function ensureMenu(\Model $q, array $form)
	{
		$secao = $form['menu'];
		$link = !empty($form['link'])
			? $form['link']
			: removeCaracteres($form['nome']);
		$formId = self::formIdPorDef($form['arquivo_def']);

		$menu = $q->read('admin_menu', "item = '".addslashes($secao)."'");
		if (!empty($menu[0]['id'])) {
			$idMenu = (int) $menu[0]['id'];
		} else {
			$todos = $q->read('admin_menu');
			$ordemMenu = is_array($todos) ? count($todos) + 1 : 1;
			$idMenu = (int) $q->insert('admin_menu', array(
				'item' => $secao,
				'cor' => '',
				'order_by' => $ordemMenu,
			));
		}

		$sub = $q->read('admin_submenu', "form = '".$formId."'");
		if (!empty($sub[0]['id'])) {
			$idSub = (int) $sub[0]['id'];
			$q->update('admin_submenu', array(
				'item' => $form['nome'],
				'link' => $link,
				'tabela' => $form['tabela'],
			), "id = '".$idSub."'");
		} else {
			$subs = $q->read('admin_submenu');
			$ordemSub = is_array($subs) ? count($subs) + 1 : 1;
			$idSub = (int) $q->insert('admin_submenu', array(
				'item' => $form['nome'],
				'link' => $link,
				'form' => $formId,
				'tabela' => $form['tabela'],
				'order_by' => $ordemSub,
			));
		}

		$vinculo = $q->read('admin_menu_submenu', "menu = '".$idMenu."' AND submenu = '".$idSub."'");
		if (empty($vinculo)) {
			$q->delete('admin_menu_submenu', "submenu = '".$idSub."'");
			$q->insert('admin_menu_submenu', array(
				'menu' => $idMenu,
				'submenu' => $idSub,
			));
		}

		return array('menu' => $idMenu, 'submenu' => $idSub, 'link' => $link);
	}

	private static function grantForm(array $form)
	{
		$chaveNome = removeCaracteres($form['nome']);
		$link = !empty($form['link']) ? $form['link'] : $chaveNome;
		$comMenu = !empty($form['menu']);
		if (array_key_exists('grant_menu', $form)) {
			$comMenu = (bool) $form['grant_menu'];
		}

		$botao = null;
		if (!empty($form['bt_adicional'])) {
			$botao = is_array($form['bt_adicional'])
				? $form['bt_adicional'][0]
				: $form['bt_adicional'];
		}

		$omitir = array();
		if (!empty($form['omitir_permissoes']) && is_array($form['omitir_permissoes'])) {
			$omitir = $form['omitir_permissoes'];
		}

		self::grant($link, $comMenu, $botao, $omitir);
		if ($chaveNome !== '' && $chaveNome !== $link) {
			self::grant($chaveNome, $comMenu, $botao, $omitir);
		}

		if (!empty($form['bt_adicional']) && is_array($form['bt_adicional']) && count($form['bt_adicional']) > 1) {
			for ($i = 1; $i < count($form['bt_adicional']); $i++) {
				self::grant($link, $comMenu, $form['bt_adicional'][$i]);
			}
		}
	}

	public static function grant($link, $comMenu = true, $botaoExtra = null, array $omitir = array())
	{
		$q = new \Model;
		$perfis = $q->read('system_perfil');
		if (empty($perfis)) {
			return;
		}

		foreach ($perfis as $perfil) {
			$perm = @unserialize($perfil['permissoes']);
			if (!is_array($perm)) {
				continue;
			}

			$temAdmin = !empty($perm['menu']) && (
				in_array('pessoas', $perm['menu'], true)
				|| in_array('estabelecimentos', $perm['menu'], true)
			);
			if (!$temAdmin && (int) $perfil['id'] !== 1) {
				continue;
			}

			$chaves = array('add', 'edit', 'del', 'view', 'list');
			if ($comMenu) {
				array_unshift($chaves, 'menu');
			}

			foreach ($chaves as $chave) {
				if (in_array($chave, $omitir, true)) {
					continue;
				}
				if (!isset($perm[$chave]) || !is_array($perm[$chave])) {
					$perm[$chave] = array();
				}
				if (!in_array($link, $perm[$chave], true)) {
					$perm[$chave][] = $link;
				}
			}

			if ($botaoExtra) {
				if (!isset($perm['bt_adicional']) || !is_array($perm['bt_adicional'])) {
					$perm['bt_adicional'] = array();
				}
				if (!in_array($botaoExtra, $perm['bt_adicional'], true)) {
					$perm['bt_adicional'][] = $botaoExtra;
				}
			}

			$q->update(
				'system_perfil',
				array('permissoes' => serialize($perm)),
				"id = '".((int) $perfil['id'])."'"
			);
		}
	}
}
