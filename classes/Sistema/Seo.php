<?php
namespace Sistema;

/**
 * Meta tags de SEO, Open Graph (Facebook/WhatsApp) e JSON-LD.
 */
class Seo
{
	/**
	 * Monta o bloco de tags do &lt;head&gt;.
	 *
	 * @param array $meta
	 * @return string
	 */
	static function head(array $meta)
	{
		$title = self::texto(isset($meta['title']) ? $meta['title'] : '', PROJETO_NOME);
		$desc = self::texto(isset($meta['description']) ? $meta['description'] : '', '');
		$keywords = self::texto(isset($meta['keywords']) ? $meta['keywords'] : '', '');
		$image = self::absoluta(isset($meta['image']) ? $meta['image'] : '');
		$imageAlt = self::texto(isset($meta['image_alt']) ? $meta['image_alt'] : '', $title);
		$type = self::texto(isset($meta['type']) ? $meta['type'] : '', 'website');
		$locale = self::texto(isset($meta['locale']) ? $meta['locale'] : '', 'pt_BR');
		$site = self::texto(isset($meta['site_name']) ? $meta['site_name'] : '', PROJETO_NOME);
		$canonical = isset($meta['canonical']) && $meta['canonical'] !== ''
			? self::absoluta($meta['canonical'])
			: self::urlCanonico();
		$robots = self::texto(
			isset($meta['robots']) ? $meta['robots'] : '',
			'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'
		);
		$author = self::texto(isset($meta['author']) ? $meta['author'] : '', '');
		$theme = self::texto(isset($meta['theme_color']) ? $meta['theme_color'] : '', '');
		$twitterCard = self::texto(isset($meta['twitter_card']) ? $meta['twitter_card'] : '', 'summary_large_image');
		$twitterSite = self::texto(isset($meta['twitter_site']) ? $meta['twitter_site'] : '', '');
		$extra = isset($meta['extra']) ? (string) $meta['extra'] : '';
		$jsonLd = isset($meta['json_ld']) ? $meta['json_ld'] : null;

		$imgInfo = $image !== '' ? self::infoImagem($image) : null;
		$imageW = isset($meta['image_width']) ? (int) $meta['image_width'] : 0;
		$imageH = isset($meta['image_height']) ? (int) $meta['image_height'] : 0;
		if ($imgInfo) {
			if ($imageW <= 0) {
				$imageW = $imgInfo['width'];
			}
			if ($imageH <= 0) {
				$imageH = $imgInfo['height'];
			}
		}
		$imageMime = $imgInfo && !empty($imgInfo['mime'])
			? $imgInfo['mime']
			: self::mimeDaUrl($image);

		$t = self::attr($title);
		$d = self::attr($desc);
		$c = self::attr($canonical);
		$img = self::attr($image);
		$siteE = self::attr($site);
		$alt = self::attr($imageAlt);

		$out = [];
		$out[] = '<title>'.$t.'</title>';
		if ($desc !== '') {
			$out[] = '<meta name="description" content="'.$d.'">';
		}
		if ($keywords !== '') {
			$out[] = '<meta name="keywords" content="'.self::attr($keywords).'">';
		}
		$out[] = '<meta name="robots" content="'.self::attr($robots).'">';
		$out[] = '<meta name="googlebot" content="'.self::attr($robots).'">';
		if ($author !== '') {
			$out[] = '<meta name="author" content="'.self::attr($author).'">';
		}
		$out[] = '<link rel="canonical" href="'.$c.'">';
		$out[] = '<link rel="alternate" hreflang="pt-BR" href="'.$c.'">';
		$out[] = '<link rel="alternate" hreflang="x-default" href="'.$c.'">';
		if ($theme !== '') {
			$out[] = '<meta name="theme-color" content="'.self::attr($theme).'">';
			$out[] = '<meta name="msapplication-TileColor" content="'.self::attr($theme).'">';
		}
		if (!empty($meta['favicon'])) {
			$fav = self::attr(self::absoluta($meta['favicon']));
			$out[] = '<link rel="icon" href="'.$fav.'" type="image/png">';
			$out[] = '<link rel="apple-touch-icon" href="'.$fav.'">';
		}

		$out[] = '<meta property="og:type" content="'.self::attr($type).'">';
		$out[] = '<meta property="og:locale" content="'.self::attr($locale).'">';
		$out[] = '<meta property="og:site_name" content="'.$siteE.'">';
		$out[] = '<meta property="og:title" content="'.$t.'">';
		if ($desc !== '') {
			$out[] = '<meta property="og:description" content="'.$d.'">';
		}
		$out[] = '<meta property="og:url" content="'.$c.'">';
		if ($image !== '') {
			$out[] = '<meta property="og:image" content="'.$img.'">';
			$out[] = '<meta property="og:image:url" content="'.$img.'">';
			$out[] = '<meta property="og:image:secure_url" content="'.$img.'">';
			$out[] = '<meta property="og:image:alt" content="'.$alt.'">';
			if ($imageMime !== '') {
				$out[] = '<meta property="og:image:type" content="'.self::attr($imageMime).'">';
			}
			if ($imageW > 0) {
				$out[] = '<meta property="og:image:width" content="'.$imageW.'">';
			}
			if ($imageH > 0) {
				$out[] = '<meta property="og:image:height" content="'.$imageH.'">';
			}
			$out[] = '<meta itemprop="image" content="'.$img.'">';
		}

		$out[] = '<meta name="twitter:card" content="'.self::attr($twitterCard).'">';
		$out[] = '<meta name="twitter:title" content="'.$t.'">';
		if ($desc !== '') {
			$out[] = '<meta name="twitter:description" content="'.$d.'">';
		}
		if ($image !== '') {
			$out[] = '<meta name="twitter:image" content="'.$img.'">';
			$out[] = '<meta name="twitter:image:alt" content="'.$alt.'">';
		}
		if ($twitterSite !== '') {
			$out[] = '<meta name="twitter:site" content="'.self::attr($twitterSite).'">';
		}

		$out[] = '<meta itemprop="name" content="'.$t.'">';
		if ($desc !== '') {
			$out[] = '<meta itemprop="description" content="'.$d.'">';
		}

		if (!empty($meta['geo_placename'])) {
			$out[] = '<meta name="geo.placename" content="'.self::attr($meta['geo_placename']).'">';
		}
		if (!empty($meta['geo_region'])) {
			$out[] = '<meta name="geo.region" content="'.self::attr($meta['geo_region']).'">';
		}
		if (!empty($meta['geo_position'])) {
			$out[] = '<meta name="geo.position" content="'.self::attr($meta['geo_position']).'">';
			$out[] = '<meta name="ICBM" content="'.self::attr($meta['geo_position']).'">';
		}

		if ($jsonLd) {
			$out[] = self::scriptJsonLd($jsonLd);
		}
		if ($extra !== '') {
			$out[] = rtrim($extra);
		}

		return implode("\n", $out)."\n";
	}

	/**
	 * JSON-LD do evento (página /ceramistas).
	 *
	 * @param array $config
	 * @param string $imagem
	 * @param array $atracoes
	 * @return array
	 */
	static function jsonLdEventoCeramistas(array $config, $imagem, array $atracoes = [])
	{
		$url = rtrim(ROOT, '/').'/ceramistas';
		$nome = '2º Encontro de Ceramistas, Aprendizes e Artesãos';
		$location = [
			'@type' => 'Place',
			'name' => $config['local'],
			'address' => [
				'@type' => 'PostalAddress',
				'streetAddress' => $config['endereco'],
				'addressLocality' => $config['cidade'],
				'addressRegion' => $config['uf'],
				'addressCountry' => 'BR',
			],
		];
		if (!empty($config['geo_lat']) && !empty($config['geo_lng'])) {
			$location['geo'] = [
				'@type' => 'GeoCoordinates',
				'latitude' => $config['geo_lat'],
				'longitude' => $config['geo_lng'],
			];
		}

		$performers = [];
		foreach ($atracoes as $show) {
			$item = [
				'@type' => 'MusicGroup',
				'name' => $show['nome'],
			];
			$same = array_values(array_filter([
				isset($show['instagram_url']) ? $show['instagram_url'] : null,
				isset($show['site']) ? $show['site'] : null,
			]));
			if ($same) {
				$item['sameAs'] = $same;
			}
			$performers[] = $item;
		}

		$event = [
			'@type' => 'Event',
			'@id' => $url.'#event',
			'name' => $nome,
			'description' => $config['meta_description'],
			'url' => $url,
			'image' => array_values(array_filter([$imagem, rtrim(ROOT, '/').'/images/logo.png'])),
			'inLanguage' => 'pt-BR',
			'startDate' => $config['data_inicio'],
			'endDate' => $config['data_fim'],
			'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
			'eventStatus' => 'https://schema.org/EventScheduled',
			'isAccessibleForFree' => true,
			'location' => $location,
			'organizer' => [
				'@type' => 'Organization',
				'name' => $nome,
				'url' => $url,
			],
			'offers' => [
				'@type' => 'Offer',
				'price' => '0',
				'priceCurrency' => 'BRL',
				'availability' => 'https://schema.org/InStock',
				'url' => $url,
			],
		];
		if ($performers) {
			$event['performer'] = $performers;
		}

		return [
			'@context' => 'https://schema.org',
			'@graph' => [
				[
					'@type' => 'WebSite',
					'@id' => $url.'#website',
					'url' => $url,
					'name' => $nome,
					'inLanguage' => 'pt-BR',
					'description' => $config['meta_description'],
				],
				$event,
			],
		];
	}

	/**
	 * JSON-LD do perfil do expositor.
	 *
	 * @param array $expositor
	 * @param string $url
	 * @return array
	 */
	static function jsonLdExpositor(array $expositor, $url)
	{
		$tipo = (!empty($expositor['grupo']) && $expositor['grupo'] === Expositores::GRUPO_ALIMENTACAO)
			? 'FoodEstablishment'
			: 'Organization';
		$entity = [
			'@type' => $tipo,
			'name' => $expositor['nome'],
			'description' => $expositor['resumo'] ?: $expositor['nome'],
			'url' => $url,
		];
		if (!empty($expositor['foto_destaque'])) {
			$entity['image'] = $expositor['foto_destaque'];
		}
		if (!empty($expositor['categoria'])) {
			$entity['keywords'] = $expositor['categoria'];
		}
		$same = array_values(array_filter([
			isset($expositor['instagram_url']) ? $expositor['instagram_url'] : null,
			isset($expositor['whatsapp_url']) ? $expositor['whatsapp_url'] : null,
		]));
		if ($same) {
			$entity['sameAs'] = $same;
		}

		return [
			'@context' => 'https://schema.org',
			'@type' => 'ProfilePage',
			'name' => $expositor['nome'],
			'url' => $url,
			'mainEntity' => $entity,
			'isPartOf' => [
				'@type' => 'WebSite',
				'name' => '2º Encontro de Ceramistas, Aprendizes e Artesãos',
				'url' => rtrim(ROOT, '/').'/ceramistas',
			],
		];
	}

	/**
	 * URL canônica da requisição atual (sem query string).
	 *
	 * @return string
	 */
	static function urlCanonico()
	{
		$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : parse_url(ROOT, PHP_URL_HOST);
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		$path = parse_url($uri, PHP_URL_PATH);
		if ($path === null || $path === '') {
			$path = '/';
		}
		return self::scheme().'://'.$host.$path;
	}

	/**
	 * @param string $url
	 * @return string
	 */
	static function absoluta($url)
	{
		$url = trim((string) $url);
		if ($url === '') {
			return '';
		}
		if (preg_match('#^https?://#i', $url)) {
			return $url;
		}
		if (strpos($url, '//') === 0) {
			return self::scheme().':'.$url;
		}
		return rtrim(ROOT, '/').'/'.ltrim($url, '/');
	}

	/**
	 * @param mixed $jsonLd
	 * @return string
	 */
	static function scriptJsonLd($jsonLd)
	{
		$json = json_encode(
			$jsonLd,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP
		);
		if ($json === false) {
			return '';
		}
		return '<script type="application/ld+json">'.$json.'</script>';
	}

	/**
	 * @return string
	 */
	private static function scheme()
	{
		if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			$proto = strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
			if ($proto === 'https' || $proto === 'http') {
				return $proto;
			}
		}
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			return 'https';
		}
		$fromRoot = parse_url(ROOT, PHP_URL_SCHEME);
		return $fromRoot ?: 'https';
	}

	/**
	 * @param string $url
	 * @return array|null
	 */
	private static function infoImagem($url)
	{
		$local = self::caminhoLocal($url);
		if (!$local || !is_file($local)) {
			return null;
		}
		$info = @getimagesize($local);
		if (!$info) {
			return null;
		}
		return [
			'width' => (int) $info[0],
			'height' => (int) $info[1],
			'mime' => isset($info['mime']) ? $info['mime'] : '',
		];
	}

	/**
	 * @param string $url
	 * @return string|null
	 */
	private static function caminhoLocal($url)
	{
		$path = parse_url($url, PHP_URL_PATH);
		if (!$path) {
			return null;
		}
		$rootPath = rtrim((string) parse_url(ROOT, PHP_URL_PATH), '/');
		if ($rootPath !== '' && strpos($path, $rootPath) === 0) {
			$path = substr($path, strlen($rootPath));
		}
		$rel = ltrim($path, '/');
		if ($rel === '' || strpos($rel, '..') !== false) {
			return null;
		}
		return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $rel);
	}

	/**
	 * @param string $url
	 * @return string
	 */
	private static function mimeDaUrl($url)
	{
		$ext = strtolower((string) pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
		$map = [
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'webp' => 'image/webp',
			'gif' => 'image/gif',
		];
		return isset($map[$ext]) ? $map[$ext] : '';
	}

	/**
	 * @param mixed $valor
	 * @param string $fallback
	 * @return string
	 */
	private static function texto($valor, $fallback)
	{
		$valor = trim((string) $valor);
		return $valor !== '' ? $valor : $fallback;
	}

	/**
	 * @param string $valor
	 * @return string
	 */
	private static function attr($valor)
	{
		return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
	}
}
