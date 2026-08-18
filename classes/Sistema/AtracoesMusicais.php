<?php
namespace Sistema;
use \DAO;

/**
 * Atrações musicais do 2º Encontro de Ceramistas.
 */
class AtracoesMusicais
{
	/**
	 * @param bool $apenasAtivos
	 * @return array
	 */
	static function listar($apenasAtivos = true)
	{
		$lista = [];
		$dao = DAO::atracoes_musicais();
		if ($apenasAtivos) {
			$dao->_ativo(1);
		}
		$dao->_loadAll('dia ASC, hora ASC, ordem ASC, nome ASC');

		if (!$dao->size()) {
			return $lista;
		}

		do {
			$lista[] = self::formatar($dao);
		} while ($dao->next());

		return $lista;
	}

	/**
	 * @param object $dao
	 * @return array
	 */
	private static function formatar($dao)
	{
		$ts = strtotime($dao->dia.' '.$dao->hora);
		$instagram = trim((string) $dao->instagram);

		return [
			'id' => (int) $dao->id,
			'txtid' => $dao->txtid,
			'nome' => $dao->nome,
			'slug' => $dao->slug,
			'resumo' => $dao->resumo,
			'cartaz' => self::urlImagem($dao->cartaz),
			'cartaz_alt' => $dao->cartaz_alt ?: ('Cartaz: '.$dao->nome),
			'dia_iso' => $dao->dia,
			'dia_rotulo' => self::rotuloDia($ts),
			'semana' => self::rotuloSemana($ts),
			'hora' => substr((string) $dao->hora, 0, 5),
			'hora_rotulo' => self::rotuloHora($dao->hora),
			'quando' => self::rotuloQuando($ts, $dao->hora),
			'local' => $dao->local,
			'instagram' => $instagram,
			'instagram_url' => $instagram !== ''
				? 'https://instagram.com/'.ltrim($instagram, '@')
				: null,
			'site' => $dao->site ?: null,
			'ordem' => (int) $dao->ordem,
		];
	}

	/**
	 * Cartaz em images/ceramistas/ (prefixo ceramistas/) ou images/upload/.
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
		$caminho = ltrim($caminho, '/');
		if (strpos($caminho, 'images/') === 0) {
			return rtrim(ROOT, '/').'/'.$caminho;
		}
		if (strpos($caminho, 'ceramistas/') === 0) {
			return rtrim(ROOT, '/').'/images/'.$caminho;
		}
		return rtrim(ROOT, '/').'/images/upload/'.$caminho;
	}

	/**
	 * @param int $ts
	 * @param string $hora
	 * @return string
	 */
	private static function rotuloQuando($ts, $hora)
	{
		return self::rotuloDia($ts).' · '.mb_strtolower(self::rotuloSemana($ts), 'UTF-8').' · a partir das '.self::rotuloHora($hora);
	}

	/**
	 * @param string $hora
	 * @return string
	 */
	private static function rotuloHora($hora)
	{
		$h = substr((string) $hora, 0, 5);
		if (!preg_match('/^(\d{2}):(\d{2})$/', $h, $m)) {
			return $h;
		}
		$horaNum = (int) $m[1];
		$min = $m[2];
		return $min === '00' ? $horaNum.'h' : $horaNum.'h'.$min;
	}

	/**
	 * @param int $ts
	 * @return string
	 */
	private static function rotuloDia($ts)
	{
		$meses = [
			1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
			5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
			9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro',
		];
		$d = (int) date('j', $ts);
		$m = (int) date('n', $ts);
		return $d.' de '.$meses[$m];
	}

	/**
	 * @param int $ts
	 * @return string
	 */
	private static function rotuloSemana($ts)
	{
		$dias = [
			'Sunday' => 'Domingo',
			'Monday' => 'Segunda-feira',
			'Tuesday' => 'Terça-feira',
			'Wednesday' => 'Quarta-feira',
			'Thursday' => 'Quinta-feira',
			'Friday' => 'Sexta-feira',
			'Saturday' => 'Sábado',
		];
		$en = date('l', $ts);
		return isset($dias[$en]) ? $dias[$en] : $en;
	}
}
