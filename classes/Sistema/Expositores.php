<?php
namespace Sistema;
use \DAO;

/**
 * Expositores do 2º Encontro de Ceramistas.
 */
class Expositores
{
	public const GRUPO_ARTESAO = 'artesao';
	public const GRUPO_ALIMENTACAO = 'alimentacao';

	/**
	 * Lista expositores ativos com fotos.
	 * @param bool $apenasAtivos
	 * @param string|null $grupo Filtrar por grupo (artesao|alimentacao)
	 * @return array
	 */
	static function listar($apenasAtivos = true, $grupo = null)
	{
		$lista = [];
		$dao = DAO::expositores();
		if ($apenasAtivos) {
			$dao->_ativo(1);
		}
		if ($grupo !== null && $grupo !== '') {
			$dao->_grupo($grupo);
		}
		$dao->_loadAll('ordem ASC, nome ASC');

		if (!$dao->size()) {
			return $lista;
		}

		do {
			$lista[] = self::formatar($dao);
		} while ($dao->next());

		return $lista;
	}

	/**
	 * Artesãos / cerâmica / decoração.
	 * @return array
	 */
	static function listarArtesaos()
	{
		return self::listar(true, self::GRUPO_ARTESAO);
	}

	/**
	 * Alimentação e cerveja artesanal.
	 * @return array
	 */
	static function listarAlimentacao()
	{
		return self::listar(true, self::GRUPO_ALIMENTACAO);
	}

	/**
	 * Busca um expositor por slug público.
	 * @param string $slug
	 * @return array|null
	 */
	static function getPorSlug($slug)
	{
		$slug = trim((string) $slug);
		if ($slug === '' || !preg_match('/^[a-z0-9_\-]{2,100}$/i', $slug)) {
			return null;
		}

		$dao = DAO::expositores()->_slug($slug)->_ativo(1)->_loadAll();
		if (!$dao->size()) {
			return null;
		}

		return self::formatar($dao, true);
	}

	/**
	 * @param object $dao
	 * @param bool $comDescricaoCompleta
	 * @return array
	 */
	private static function formatar($dao, $comDescricaoCompleta = true)
	{
		return [
			'id' => (int) $dao->id,
			'txtid' => $dao->txtid,
			'nome' => $dao->nome,
			'slug' => $dao->slug,
			'resumo' => $dao->resumo,
			'descricao' => $comDescricaoCompleta ? $dao->descricao : null,
			'categoria' => $dao->categoria,
			'grupo' => $dao->grupo ?: self::GRUPO_ARTESAO,
			'logo' => self::urlImagem($dao->logo),
			'foto_destaque' => self::urlImagem($dao->foto_destaque ?: $dao->logo),
			'instagram' => $dao->instagram,
			'instagram_url' => $dao->instagram
				? 'https://instagram.com/' . ltrim($dao->instagram, '@')
				: null,
			'whatsapp' => $dao->whatsapp,
			'whatsapp_url' => $dao->whatsapp
				? 'https://wa.me/55' . preg_replace('/\D+/', '', $dao->whatsapp)
				: null,
			'ordem' => (int) $dao->ordem,
			'fotos' => self::fotosDoExpositor((int) $dao->id),
		];
	}

	/**
	 * @param int $expositorId
	 * @return array
	 */
	private static function fotosDoExpositor($expositorId)
	{
		$fotos = [];
		$dao = DAO::expositores_fotos()
			->_expositor_id((int) $expositorId)
			->_loadAll('ordem ASC, id ASC');

		if (!$dao->size()) {
			return $fotos;
		}

		do {
			$fotos[] = [
				'id' => (int) $dao->id,
				'url' => self::urlImagem($dao->arquivo),
				'legenda' => $dao->legenda,
				'destaque' => (int) $dao->destaque === 1,
			];
		} while ($dao->next());

		return $fotos;
	}

	/**
	 * @param string|null $caminho
	 * @return string|null
	 */
	private static function urlImagem($caminho)
	{
		$caminho = trim((string) $caminho);
		if ($caminho === '') {
			return null;
		}
		if (preg_match('#^https?://#i', $caminho)) {
			return $caminho;
		}
		return rtrim(ROOT, '/') . '/images/upload/' . ltrim($caminho, '/');
	}
}
