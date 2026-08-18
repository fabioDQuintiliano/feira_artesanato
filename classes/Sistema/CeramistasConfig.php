<?php
namespace Sistema;
use \DAO;

/**
 * Configuração geral do 2º Encontro de Ceramistas (data, local, WhatsApp).
 */
class CeramistasConfig
{
	/**
	 * @return array
	 */
	static function get()
	{
		$padrao = self::padrao();
		try {
			$dao = DAO::ceramistas_config()->_loadAll('id ASC LIMIT 1');
		} catch (\Throwable $e) {
			return $padrao;
		}

		if (!$dao || !$dao->size()) {
			return $padrao;
		}

		return self::formatar($dao, $padrao);
	}

	/**
	 * @return array
	 */
	private static function padrao()
	{
		return self::formatar((object) [
			'data_inicio' => '2026-09-05',
			'data_fim' => '2026-09-06',
			'local' => 'Praça da Matriz',
			'local_complemento' => 'Calçadão Pedro Furlan',
			'cidade' => 'Arceburgo',
			'uf' => 'MG',
			'endereco' => 'Entorno do Caramanchão da Praça da Matriz e Calçadão Pedro Furlan · Arceburgo - MG',
			'mapa_query' => '-21.3644363,-46.938418',
			'whatsapp' => '35997010196',
			'mensagem_whatsapp' => 'Olá! Quero saber mais sobre o 2º Encontro de Ceramistas em Arceburgo.',
		], array());
	}

	/**
	 * @param object $dao
	 * @param array $fallback
	 * @return array
	 */
	private static function formatar($dao, array $fallback)
	{
		$inicio = strtotime($dao->data_inicio);
		$fim = strtotime($dao->data_fim ?: $dao->data_inicio);
		if (!$inicio) {
			$inicio = strtotime('2026-09-05');
		}
		if (!$fim) {
			$fim = $inicio;
		}

		$local = trim((string) $dao->local) ?: 'Praça da Matriz';
		$complemento = trim((string) $dao->local_complemento);
		$cidade = trim((string) $dao->cidade) ?: 'Arceburgo';
		$uf = strtoupper(trim((string) $dao->uf)) ?: 'MG';
		$whatsapp = self::whatsappE164($dao->whatsapp);
		$mensagem = trim((string) $dao->mensagem_whatsapp);
		if ($mensagem === '') {
			$mensagem = 'Olá! Quero saber mais sobre o 2º Encontro de Ceramistas em Arceburgo.';
		}
		$mapa = trim((string) $dao->mapa_query);
		if ($mapa === '') {
			$mapa = '-21.3644363,-46.938418';
		}
		$endereco = trim((string) $dao->endereco);
		if ($endereco === '') {
			$partes = array_filter(array($local, $complemento, $cidade.' - '.$uf));
			$endereco = implode(' · ', $partes);
		}

		$quando = self::rotuloQuando($inicio, $fim, false);
		$quandoCurto = self::rotuloQuando($inicio, $fim, true);
		$coords = self::parseCoords($mapa);

		return array_merge($fallback, [
			'data_inicio' => date('Y-m-d', $inicio),
			'data_fim' => date('Y-m-d', $fim),
			'dias_hero' => self::rotuloDiasHero($inicio, $fim),
			'mes_ano' => self::mes($inicio).' '.date('Y', $inicio),
			'quando' => $quando,
			'quando_curto' => $quandoCurto,
			'quando_aria' => $quando,
			'local' => $local,
			'local_complemento' => $complemento,
			'cidade' => $cidade,
			'uf' => $uf,
			'kicker' => $cidade.' · '.$uf,
			'lead' => 'Cerâmica, oficinas, música e sabores em '.$cidade.'.',
			'place_sub' => trim($complemento.($complemento !== '' ? ' · ' : '').$cidade),
			'nota_musica' => self::notaMusica($local, $complemento, $cidade, $uf),
			'endereco' => $endereco,
			'mapa_url' => self::mapaUrl($mapa, $local),
			'mapa_titulo' => 'Mapa de '.$local.' em '.$cidade,
			'geo_lat' => $coords ? $coords['lat'] : null,
			'geo_lng' => $coords ? $coords['lng'] : null,
			'whatsapp' => $whatsapp,
			'whatsapp_rotulo' => self::whatsappRotulo($whatsapp),
			'whatsapp_url' => 'https://wa.me/'.$whatsapp.'?text='.rawurlencode($mensagem),
			'rodape' => $cidade.' · '.$quandoCurto,
			'meta_description' => 'Arte que conecta, tradição que transforma! '.$quando.' na '.$local.', '.$cidade.' - '.$uf.'. Programação, expositores, oficinas, música e espaço kids.',
		]);
	}

	/**
	 * Iframe do Google Maps. Coordenadas (lat,lng) ganham marcador com o nome do local.
	 *
	 * @param string $mapa
	 * @param string $local
	 * @return string
	 */
	private static function mapaUrl($mapa, $local)
	{
		$coords = self::parseCoords($mapa);
		if ($coords) {
			$q = $coords['lat'].','.$coords['lng'];
			if ($local !== '') {
				$q .= ' ('.$local.')';
			}
			return 'https://maps.google.com/maps?q='.rawurlencode($q).'&hl=pt-BR&z=18&ie=UTF8&iwloc=A&output=embed';
		}

		return 'https://maps.google.com/maps?q='.rawurlencode($mapa).'&t=&z=16&ie=UTF8&iwloc=&output=embed';
	}

	/**
	 * Aceita "lat,lng", "lat, lng" ou o trecho do Maps "@lat,lng,406".
	 *
	 * @param string $valor
	 * @return array|null
	 */
	private static function parseCoords($valor)
	{
		$valor = trim($valor);
		if ($valor === '') {
			return null;
		}
		if (preg_match('/(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/', $valor, $m)) {
			$lat = (float) $m[1];
			$lng = (float) $m[2];
			if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
				return array('lat' => $m[1], 'lng' => $m[2]);
			}
		}
		return null;
	}

	/**
	 * @param string $local
	 * @param string $complemento
	 * @param string $cidade
	 * @param string $uf
	 * @return string
	 */
	private static function notaMusica($local, $complemento, $cidade, $uf)
	{
		$partes = array_filter(array(
			$complemento,
			$local,
			$cidade.'-'.$uf,
			'Entrada gratuita',
		));
		return implode(' · ', $partes);
	}

	/**
	 * @param int $inicio
	 * @param int $fim
	 * @return string
	 */
	private static function rotuloDiasHero($inicio, $fim)
	{
		$d1 = date('d', $inicio);
		if (date('Y-m-d', $inicio) === date('Y-m-d', $fim)) {
			return $d1;
		}
		if (date('Y-m', $inicio) === date('Y-m', $fim)) {
			return $d1.'–'.date('d', $fim);
		}
		return $d1.'–'.date('d', $fim);
	}

	/**
	 * @param int $inicio
	 * @param int $fim
	 * @param bool $mesMaiusculo
	 * @return string
	 */
	private static function rotuloQuando($inicio, $fim, $mesMaiusculo = false)
	{
		$d1 = (int) date('j', $inicio);
		$d2 = (int) date('j', $fim);
		$m1 = $mesMaiusculo ? self::mesCap($inicio) : self::mes($inicio);
		$m2 = $mesMaiusculo ? self::mesCap($fim) : self::mes($fim);
		$y1 = date('Y', $inicio);
		$y2 = date('Y', $fim);

		if (date('Y-m-d', $inicio) === date('Y-m-d', $fim)) {
			return $d1.' de '.$m1;
		}
		if ($m1 === $m2 && $y1 === $y2) {
			return $d1.' e '.$d2.' de '.$m1;
		}
		if ($y1 === $y2) {
			return $d1.' de '.$m1.' e '.$d2.' de '.$m2;
		}
		return $d1.' de '.$m1.' de '.$y1.' e '.$d2.' de '.$m2.' de '.$y2;
	}

	/**
	 * @param int $ts
	 * @return string
	 */
	private static function mesCap($ts)
	{
		return mb_convert_case(self::mes($ts), MB_CASE_TITLE, 'UTF-8');
	}

	/**
	 * @param int $ts
	 * @return string
	 */
	private static function mes($ts)
	{
		$meses = [
			1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
			5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
			9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro',
		];
		$m = (int) date('n', $ts);
		return isset($meses[$m]) ? $meses[$m] : '';
	}

	/**
	 * @param string $valor
	 * @return string
	 */
	private static function whatsappE164($valor)
	{
		$digitos = preg_replace('/\D+/', '', (string) $valor);
		if ($digitos === '') {
			$digitos = '35997010196';
		}
		if (strlen($digitos) === 11) {
			$digitos = '55'.$digitos;
		} elseif (strlen($digitos) === 10) {
			$digitos = '55'.$digitos;
		}
		return $digitos;
	}

	/**
	 * @param string $e164
	 * @return string
	 */
	private static function whatsappRotulo($e164)
	{
		$n = preg_replace('/\D+/', '', $e164);
		if (strlen($n) === 13 && substr($n, 0, 2) === '55') {
			$ddd = substr($n, 2, 2);
			$num = substr($n, 4);
			if (strlen($num) === 9) {
				return '('.$ddd.') '.substr($num, 0, 5).'-'.substr($num, 5);
			}
			if (strlen($num) === 8) {
				return '('.$ddd.') '.substr($num, 0, 4).'-'.substr($num, 4);
			}
		}
		return $n;
	}
}
