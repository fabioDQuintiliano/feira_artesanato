<?php
namespace Sistema;
use \DAO;

/**
 * Programação do 2º Encontro de Ceramistas.
 */
class Programacao
{
	/**
	 * Lista itens ativos agrupados por dia.
	 * @return array{dias: array, itens: array}
	 */
	static function listarAgrupada()
	{
		$itens = self::listar();
		$dias = [];

		foreach ($itens as $item) {
			$chave = $item['dia_iso'];
			if (!isset($dias[$chave])) {
				$dias[$chave] = [
					'dia_iso' => $chave,
					'rotulo' => $item['dia_rotulo'],
					'semana' => $item['semana'],
					'itens' => [],
				];
			}
			$dias[$chave]['itens'][] = $item;
		}

		return [
			'dias' => array_values($dias),
			'itens' => $itens,
		];
	}

	/**
	 * @return array
	 */
	static function listar($apenasAtivos = true)
	{
		$lista = [];
		$dao = DAO::programacao();
		if ($apenasAtivos) {
			$dao->_ativo(1);
		}
		$dao->_loadAll('dia ASC, hora_inicio ASC, ordem ASC');

		if (!$dao->size()) {
			return $lista;
		}

		do {
			$ts = strtotime($dao->dia . ' ' . $dao->hora_inicio);
			$lista[] = [
				'id' => (int) $dao->id,
				'txtid' => $dao->txtid,
				'titulo' => $dao->titulo,
				'descricao' => $dao->descricao,
				'dia_iso' => $dao->dia,
				'dia_rotulo' => self::rotuloDia($ts),
				'semana' => self::rotuloSemana($ts),
				'hora_inicio' => substr($dao->hora_inicio, 0, 5),
				'hora_fim' => $dao->hora_fim ? substr($dao->hora_fim, 0, 5) : null,
				'horario' => self::formatarHorario($dao->hora_inicio, $dao->hora_fim),
				'local' => $dao->local,
				'categoria' => $dao->categoria,
				'icone' => $dao->icone ?: 'sun',
				'destaque' => (int) $dao->destaque === 1,
				'ordem' => (int) $dao->ordem,
			];
		} while ($dao->next());

		return $lista;
	}

	/**
	 * @param string $inicio
	 * @param string|null $fim
	 * @return string
	 */
	private static function formatarHorario($inicio, $fim)
	{
		$h1 = substr((string) $inicio, 0, 5);
		if (!$fim) {
			return $h1;
		}
		return $h1 . ' – ' . substr((string) $fim, 0, 5);
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
		return $d . ' de ' . $meses[$m];
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
