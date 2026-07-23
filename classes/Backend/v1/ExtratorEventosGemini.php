<?php
declare(strict_types=1);

namespace Backend\v1;

use GeminiAPI\Client as GeminiClient;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Extrai eventos a partir de uma URL usando:
 * - JSON-LD (schema.org/Event), quando existir
 * - fallback via Gemini (LLM) para páginas sem marcação estruturada
 *
 * Retorno: lista de eventos com campos:
 * - titulo, imagem, data, hora, descricao, valor, faixa_etaria, tags, link
 */
class ExtratorEventosGemini
{
	private HttpClientInterface $http;
	private string $geminiApiKey;
	private string $modelName;
	private ?string $resolvedModelName = null;
	private ?string $resolvedApiVersion = null; // 'v1' | 'v1beta'

	public function __construct(?string $geminiApiKey = null, ?HttpClientInterface $http = null, ?string $modelName = null)
	{
		$this->http = $http ?: HttpClient::create([
			'headers' => [
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
				'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
				'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
			],
			'max_redirects' => 10,
			'timeout' => 20,
			'max_duration' => 30,
		]);

		$this->geminiApiKey = (string) ($geminiApiKey
			?: (defined('GEMINI_API_KEY') ? constant('GEMINI_API_KEY') : null)
			?: getenv('GEMINI_API_KEY'));

		if (trim($this->geminiApiKey) === '') {
			throw new \RuntimeException('GEMINI_API_KEY não configurada (defina a constante GEMINI_API_KEY ou a variável de ambiente GEMINI_API_KEY).');
		}

		// Modelo padrão: use *-latest para evitar 404 por versão indisponível
		$this->modelName = $modelName ?: ModelName::GEMINI_1_5_FLASH_LATEST;
	}

	/**
	 * @return array<int, array{
	 *   titulo: string,
	 *   imagem: ?string,
	 *   data: ?string,
	 *   hora: ?string,
	 *   descricao: ?string,
	 *   valor: ?string,
	 *   faixa_etaria: ?string,
	 *   tags: array<int, string>,
	 *   link: ?string
	 * }>
	 */
	public function extrair(string $url): array
	{
		$url = trim($url);
		if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException('URL inválida.');
		}

		$html = $this->baixarHtml($url);

		$eventosLd = $this->extrairEventosJsonLd($html, $url);
		if (count($eventosLd) > 0) {
			return $eventosLd;
		}

		return $this->extrairEventosViaGemini($html, $url);
	}

	private function baixarHtml(string $url): string
	{
		$limiteBytes = 3 * 1024 * 1024;
		$response = $this->http->request('GET', $url, [
			'on_progress' => static function (int $baixados) use ($limiteBytes): void {
				if ($baixados > $limiteBytes) {
					throw new \RuntimeException('A página excede o limite de 3 MB.');
				}
			},
		]);
		$status = $response->getStatusCode();
		$content = (string) $response->getContent(false);

		if ($status < 200 || $status >= 400 || trim($content) === '') {
			throw new \RuntimeException("Falha ao baixar URL ({$status}).");
		}
		return $content;
	}

	/**
	 * Tenta extrair eventos a partir de JSON-LD (schema.org/Event).
	 * @return array<int, array<string, mixed>>
	 */
	private function extrairEventosJsonLd(string $html, string $baseUrl): array
	{
		$eventos = [];

		// Alguns servidores não têm a extensão DOM (php-xml) habilitada.
		// Nesse caso, usamos fallback por regex/simple_html_dom.
		$jsonBlocks = $this->extrairBlocosJsonLd($html);
		if (count($jsonBlocks) === 0) {
			return [];
		}

		foreach ($jsonBlocks as $json) {
			$json = trim($json);
			if ($json === '') {
				continue;
			}
			$data = json_decode($json, true);
			if (!is_array($data)) {
				continue;
			}

			$itens = $this->achatarJsonLd($data);
			foreach ($itens as $item) {
				if (!$this->jsonLdEhEvento($item)) {
					continue;
				}
				$eventos[] = $this->normalizarEvento($this->mapearEventoJsonLd($item, $baseUrl));
			}
		}

		$eventos = array_values(array_filter($eventos, fn($e) => trim((string) ($e['titulo'] ?? '')) !== ''));
		return $eventos;
	}

	/**
	 * Extrai conteúdos de <script type="application/ld+json">...</script>
	 * sem depender da extensão DOM.
	 *
	 * @return array<int, string>
	 */
	private function extrairBlocosJsonLd(string $html): array
	{
		// Caminho preferencial: DOM + XPath (mais confiável)
		if (class_exists(\DOMDocument::class)) {
			$dom = new \DOMDocument();
			$prev = libxml_use_internal_errors(true);
			@$dom->loadHTML($html);
			libxml_clear_errors();
			libxml_use_internal_errors($prev);

			$xpath = new \DOMXPath($dom);
			$scripts = $xpath->query('//script[@type="application/ld+json"]');
			if (!$scripts) {
				return [];
			}

			$out = [];
			foreach ($scripts as $script) {
				/** @var \DOMElement $script */
				$out[] = (string) $script->nodeValue;
			}
			return $out;
		}

		// Se o projeto tiver simple_html_dom carregado, também dá para usar.
		if (function_exists('str_get_html')) {
			$dom = @str_get_html($html);
			if ($dom) {
				$out = [];
				foreach ($dom->find('script[type=application/ld+json]') as $node) {
					$out[] = (string) $node->innertext;
				}
				$dom->clear();
				return $out;
			}
		}

		// Fallback final: regex
		$out = [];
		if (preg_match_all('#<script\b[^>]*type=["\']application/ld\+json["\'][^>]*>(.*?)</script>#is', $html, $m)) {
			foreach ($m[1] as $block) {
				$out[] = html_entity_decode((string) $block, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			}
		}
		return $out;
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	private function achatarJsonLd(array $data): array
	{
		$out = [];

		// Pode vir como array de objetos, @graph, ou objeto único
		if (array_is_list($data)) {
			foreach ($data as $v) {
				if (is_array($v)) {
					$out = array_merge($out, $this->achatarJsonLd($v));
				}
			}
			return $out;
		}

		if (isset($data['@graph']) && is_array($data['@graph'])) {
			foreach ($data['@graph'] as $v) {
				if (is_array($v)) {
					$out[] = $v;
				}
			}
			return $out;
		}

		$out[] = $data;
		return $out;
	}

	private function jsonLdEhEvento(array $item): bool
	{
		$type = $item['@type'] ?? null;
		if (is_string($type)) {
			return strtolower($type) === 'event';
		}
		if (is_array($type)) {
			foreach ($type as $t) {
				if (is_string($t) && strtolower($t) === 'event') {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @return array{titulo:string, imagem:?string, data:?string, hora:?string, descricao:?string, valor:?string, faixa_etaria:?string, tags:array<int,string>, link:?string}
	 */
	private function mapearEventoJsonLd(array $item, string $baseUrl): array
	{
		$titulo = $this->asString($item['name'] ?? null);
		$descricao = $this->asString($item['description'] ?? null);

		$start = $this->asString($item['startDate'] ?? null);
		[$data, $hora] = $this->splitDateTime($start);

		$link = $this->asString($item['url'] ?? null) ?: $baseUrl;
		$imagem = $this->extrairImagemJsonLd($item['image'] ?? null);
		$keywords = $item['keywords'] ?? null;
		$tags = $this->normalizarTags($keywords);
		$valor = $this->extrairValorJsonLd($item);

		return [
			'titulo' => $titulo,
			'imagem' => $imagem,
			'data' => $data,
			'hora' => $hora,
			'descricao' => $descricao,
			'valor' => $valor,
			'faixa_etaria' => $this->asString($item['typicalAgeRange'] ?? null) ?: null,
			'tags' => $tags,
			'link' => $link,
		];
	}

	private function extrairValorJsonLd(array $item): ?string
	{
		if ($this->valorBooleanoVerdadeiro($item['isAccessibleForFree'] ?? null)) {
			return 'Gratuito';
		}

		$offers = $item['offers'] ?? null;
		if (!is_array($offers)) {
			return null;
		}

		$listaOfertas = array_is_list($offers) ? $offers : [$offers];
		foreach ($listaOfertas as $oferta) {
			if (!is_array($oferta)) {
				continue;
			}

			if ($this->valorBooleanoVerdadeiro($oferta['isAccessibleForFree'] ?? null)) {
				return 'Gratuito';
			}

			$especificacao = is_array($oferta['priceSpecification'] ?? null)
				? $oferta['priceSpecification']
				: [];

			$moeda = $this->asString(
				$oferta['priceCurrency']
					?? $especificacao['priceCurrency']
					?? null
			);

			$precoMinimo = $oferta['lowPrice']
				?? $especificacao['minPrice']
				?? null;
			$precoMaximo = $oferta['highPrice']
				?? $especificacao['maxPrice']
				?? null;

			if ($precoMinimo !== null) {
				$minimo = $this->formatarValorOferta($precoMinimo, $moeda);
				$maximo = $precoMaximo !== null
					? $this->formatarValorOferta($precoMaximo, $moeda)
					: null;

				if ($minimo === 'Gratuito') {
					return $minimo;
				}
				if ($minimo && $maximo && $minimo !== $maximo) {
					return "{$minimo} a {$maximo}";
				}
				if ($minimo) {
					return "A partir de {$minimo}";
				}
			}

			$preco = $oferta['price']
				?? $especificacao['price']
				?? null;
			$valor = $this->formatarValorOferta($preco, $moeda);
			if ($valor !== null) {
				return $valor;
			}

			$textoOferta = $this->asString($oferta['name'] ?? $oferta['description'] ?? null);
			if ($textoOferta !== '' && preg_match('/\b(gratuito|grátis|gratis|free)\b/iu', $textoOferta)) {
				return 'Gratuito';
			}
		}

		return null;
	}

	private function formatarValorOferta(mixed $preco, string $moeda = ''): ?string
	{
		$valor = $this->asString($preco);
		if ($valor === '') {
			return null;
		}

		if (preg_match('/^(gratuito|grátis|gratis|free)$/iu', $valor)) {
			return 'Gratuito';
		}

		$numeroNormalizado = str_replace(',', '.', preg_replace('/[^\d,.-]/', '', $valor) ?? '');
		if ($numeroNormalizado !== '' && is_numeric($numeroNormalizado)) {
			$numero = (float) $numeroNormalizado;
			if ($numero <= 0) {
				return 'Gratuito';
			}

			$valor = number_format($numero, 2, ',', '.');
		}

		$prefixos = [
			'BRL' => 'R$',
			'USD' => 'US$',
			'EUR' => '€',
		];
		$codigoMoeda = strtoupper(trim($moeda));
		$prefixo = $prefixos[$codigoMoeda] ?? $codigoMoeda;

		if ($prefixo !== '' && !preg_match('/(?:R\$|US\$|€|\b[A-Z]{3}\b)/u', $valor)) {
			$valor = "{$prefixo} {$valor}";
		}

		return trim($valor) ?: null;
	}

	private function valorBooleanoVerdadeiro(mixed $valor): bool
	{
		return $valor === true
			|| $valor === 1
			|| (is_string($valor) && in_array(strtolower(trim($valor)), ['true', '1', 'yes', 'sim'], true));
	}

	private function extrairImagemJsonLd(mixed $image): ?string
	{
		if (is_string($image)) {
			return trim($image) ?: null;
		}
		if (is_array($image)) {
			// pode ser lista de urls ou objeto {url: ...}
			if (array_is_list($image)) {
				foreach ($image as $v) {
					if (is_string($v) && trim($v) !== '') {
						return trim($v);
					}
					if (is_array($v) && isset($v['url']) && is_string($v['url']) && trim($v['url']) !== '') {
						return trim($v['url']);
					}
				}
				return null;
			}
			if (isset($image['url']) && is_string($image['url'])) {
				return trim($image['url']) ?: null;
			}
		}
		return null;
	}

	/**
	 * Fallback com Gemini: envia HTML “limpo” e pede JSON estrito.
	 * @return array<int, array<string, mixed>>
	 */
	private function extrairEventosViaGemini(string $html, string $url): array
	{
		$conteudo = $this->limparHtmlParaIA($html);

		$prompt = $this->montarPromptExtracao($url, $conteudo);

		$text = $this->gerarTextoGeminiComFallback($prompt);
		$json = $this->extrairJsonDoTexto($text);
		$dados = json_decode($json, true);
		if (!is_array($dados)) {
			throw new \RuntimeException('Gemini retornou JSON inválido.');
		}

		$eventos = $dados['eventos'] ?? $dados;
		if (!is_array($eventos)) {
			throw new \RuntimeException('Gemini retornou formato inesperado (sem lista de eventos).');
		}

		$out = [];
		foreach ($eventos as $ev) {
			if (!is_array($ev)) {
				continue;
			}
			$out[] = $this->normalizarEvento($ev, $url);
		}

		$out = array_values(array_filter($out, fn($e) => trim((string) ($e['titulo'] ?? '')) !== ''));
		return $out;
	}

	/**
	 * Chama o Gemini usando API v1 (padrão da lib) e tenta fallback de modelo em caso de 404/NOT_FOUND.
	 */
	public function gerarTextoGeminiComFallback(string $prompt): string
	{
		$prompt = $this->garantirUtf8($prompt);
		if (trim($prompt) === '') {
			throw new \InvalidArgumentException('Prompt vazio para o Gemini.');
		}

		// 1) Tentativa rápida com o que foi configurado (sem listar modelos)
		foreach (['v1', 'v1beta'] as $version) {
			$client = $this->criarClientGemini($version);
			try {
				$response = $client->generativeModel($this->modelName)->generateContent(new TextPart($prompt));
				return trim((string) $response->text());
			} catch (\Throwable $e) {
				if ($this->ehErroModeloNaoDisponivel($e)) {
					// vai para o resolve dinâmico abaixo
					break;
				}
				// erro não relacionado a modelo (quota, chave inválida, etc.)
				throw $e;
			}
		}

		// 2) Resolve dinâmico com ListModels e tenta os candidatos
		[$version, $candidatos] = $this->resolverModelosSuportados();
		$client = $this->criarClientGemini($version);

		$ultimoErro = null;
		foreach ($candidatos as $model) {
			try {
				$response = $client->generativeModel($model)->generateContent(new TextPart($prompt));
				$this->resolvedApiVersion = $version;
				$this->resolvedModelName = $model;
				return trim((string) $response->text());
			} catch (\Throwable $e) {
				$ultimoErro = $e;
				if ($this->ehErroModeloNaoDisponivel($e)) {
					continue;
				}
				throw $e;
			}
		}

		if ($ultimoErro) {
			throw $ultimoErro;
		}
		throw new \RuntimeException('Falha inesperada ao chamar o Gemini (sem modelos disponíveis para generateContent).');
	}

	/**
	 * A lib do Gemini faz json_encode do payload; UTF-8 inválido vira corpo vazio
	 * e a API responde "contents is not specified".
	 */
	private function garantirUtf8(string $texto): string
	{
		if ($texto === '' || mb_check_encoding($texto, 'UTF-8')) {
			return $texto;
		}

		$corrigido = @iconv('UTF-8', 'UTF-8//IGNORE', $texto);
		if ($corrigido !== false && $corrigido !== '') {
			return $corrigido;
		}

		$corrigido = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');
		return is_string($corrigido) ? $corrigido : '';
	}

	private function criarClientGemini(string $version): GeminiClient
	{
		// A fila fará nova tentativa depois; não bloqueia o worker por vários minutos.
		$psr18Client = new Psr18Client(HttpClient::create([
			'timeout' => 45,
			'max_duration' => 75,
		]));

		$client = new GeminiClient($this->geminiApiKey, $psr18Client);
		return $version === 'v1beta' ? $client->withV1BetaVersion() : $client;
	}

	private function ehErroModeloNaoDisponivel(\Throwable $e): bool
	{
		$msg = (string) $e->getMessage();
		return str_contains($msg, 'status_code=404')
			|| str_contains($msg, 'NOT_FOUND')
			|| str_contains($msg, 'is not found')
			|| str_contains($msg, 'not supported for generateContent')
			|| str_contains($msg, 'Call ListModels');
	}

	/**
	 * Retorna: [apiVersion, listaDeModelosSemPrefixoModels/]
	 *
	 * @return array{0:'v1'|'v1beta',1:array<int,string>}
	 */
	private function resolverModelosSuportados(): array
	{
		// cache por instância
		if ($this->resolvedApiVersion && $this->resolvedModelName) {
			return [$this->resolvedApiVersion, [$this->resolvedModelName]];
		}

		$tentativas = ['001', 'v1beta'];
		$ultimoErro = null;

		foreach ($tentativas as $version) {
			try {
				$client = $this->criarClientGemini($version);
				$list = $client->listModels();

				$models = [];
				foreach ($list->models as $m) {
					$methods = $m->supportedGenerationMethods ?? [];
					if (is_array($methods) && in_array('generateContent', $methods, true)) {
						$name = $this->stripModelsPrefix($m->name);
						if ($name !== '') {
							$models[] = $name;
						}
					}
				}

				$models = $this->ordenarModelosPreferidos($models);
				if (count($models) === 0) {
					$ultimoErro = new \RuntimeException("Nenhum modelo com generateContent encontrado em {$version}.");
					continue;
				}

				return [$version, $models];
			} catch (\Throwable $e) {
				$ultimoErro = $e;
				continue;
			}
		}

		if ($ultimoErro) {
			throw $ultimoErro;
		}
		throw new \RuntimeException('Falha ao listar modelos do Gemini.');
	}

	private function stripModelsPrefix(string $name): string
	{
		$name = trim($name);
		if (str_starts_with($name, 'models/')) {
			return substr($name, strlen('models/'));
		}
		return $name;
	}

	/**
	 * Prioriza modelos "flash" e depois "pro".
	 * @param array<int,string> $models
	 * @return array<int,string>
	 */
	private function ordenarModelosPreferidos(array $models): array
	{
		$uniq = array_values(array_unique(array_filter($models, fn($m) => trim((string) $m) !== '')));

		usort($uniq, function (string $a, string $b): int {
			return $this->scoreModelo($b) <=> $this->scoreModelo($a);
		});

		// também tenta primeiro o modelo configurado, se ele aparecer na lista
		$cfg = $this->stripModelsPrefix($this->modelName);
		$idx = array_search($cfg, $uniq, true);
		if ($idx !== false) {
			unset($uniq[$idx]);
			array_unshift($uniq, $cfg);
		}

		return array_values($uniq);
	}

	private function scoreModelo(string $name): int
	{
		$n = strtolower($name);
		$score = 0;
		if (str_contains($n, 'flash')) {
			$score += 30;
		}
		if (str_contains($n, 'pro')) {
			$score += 20;
		}
		if (str_contains($n, 'latest')) {
			$score += 5;
		}
		// evita escolher embeddings/aqa por engano (mesmo se por algum motivo aparecerem)
		if (str_contains($n, 'embedding') || str_contains($n, 'aqa')) {
			$score -= 100;
		}
		return $score;
	}

	private function montarPromptExtracao(string $url, string $conteudoHtml): string
	{
		// Pede JSON estrito; caso o modelo “escape” com markdown, o parser abaixo tenta recuperar o JSON.
		return <<<PROMPT
Você é um extrator de eventos. A partir do HTML abaixo (página de listagem ou detalhe), extraia TODOS os eventos disponíveis.

Regras:
- Responda SOMENTE com JSON válido (sem markdown, sem texto extra).
- Se não houver eventos, retorne {"eventos":[]}.
- Gere tags curtas e úteis (máx. 5) a partir do tema do evento (ex.: "rock", "networking", "infantil", "startup", "teatro").
- A descrição deve ser objetiva, com no máximo 800 caracteres, preservando as informações relevantes encontradas.
- Identifique o valor/preço (campo "valor") e a classificação indicativa/faixa etária (campo "faixa_etaria").
- O campo "valor" é textual: retorne "Gratuito" para eventos gratuitos, preserve faixas de preço e use null somente quando o valor não estiver informado.
- Quando data/hora não estiverem explícitas, use null.
- Para links/imagens relativos, converta para absoluto com base na URL de origem.

Formato esperado:
{
  "eventos": [
    {
      "titulo": "string",
      "imagem": "string|null",
      "data": "YYYY-MM-DD|null",
      "hora": "HH:MM|null",
      "descricao": "string|null (máximo de 800 caracteres)",
      "valor": "string|null (Ex: 'Gratuito', 'R$ 50,00', 'A partir de R$ 20,00')",
      "faixa_etaria": "string|null (Ex: 'Livre', '18 anos', '12 anos')",
      "tags": ["string", "..."],
      "link": "string|null"
    }
  ]
}

URL de origem: {$url}

HTML:
{$conteudoHtml}
PROMPT;
	}

	private function limparHtmlParaIA(string $html): string
	{
		$clean = '';

		// Preferencial: DOM para remover tags “ruins”
		if (class_exists(\DOMDocument::class)) {
			$dom = new \DOMDocument();
			$prev = libxml_use_internal_errors(true);
			@$dom->loadHTML($html);
			libxml_clear_errors();
			libxml_use_internal_errors($prev);

			$xpath = new \DOMXPath($dom);
			foreach (['script', 'style', 'noscript', 'svg', 'nav', 'header', 'footer', 'aside', 'form', 'iframe'] as $tag) {
				$nodes = $xpath->query('//' . $tag);
				if ($nodes) {
					for ($i = $nodes->length - 1; $i >= 0; $i--) {
						$n = $nodes->item($i);
						if ($n && $n->parentNode) {
							$n->parentNode->removeChild($n);
						}
					}
				}
			}

			$body = $xpath->query('//body')->item(0);
			if ($body instanceof \DOMElement) {
				$elementos = $body->getElementsByTagName('*');
				for ($i = $elementos->length - 1; $i >= 0; $i--) {
					$elemento = $elementos->item($i);
					if (!$elemento instanceof \DOMElement) {
						continue;
					}
					$manter = ['href', 'src', 'content', 'datetime', 'alt', 'title'];
					for ($j = $elemento->attributes->length - 1; $j >= 0; $j--) {
						$atributo = $elemento->attributes->item($j);
						if ($atributo && !in_array(strtolower($atributo->name), $manter, true)) {
							$elemento->removeAttribute($atributo->name);
						}
					}
				}
			}
			$clean = $body ? (string) $dom->saveHTML($body) : (string) $dom->saveHTML();
		} elseif (function_exists('str_get_html')) {
			// Alternativa: simple_html_dom
			$dom = @str_get_html($html);
			if ($dom) {
				foreach (['script', 'style', 'noscript', 'svg', 'nav', 'header', 'footer', 'aside', 'form', 'iframe'] as $tag) {
					foreach ($dom->find($tag) as $node) {
						$node->outertext = '';
					}
				}
				$clean = (string) $dom->save();
				$dom->clear();
			}
		}

		// Fallback final: regex simples (menos preciso, mas evita quebra)
		if (trim($clean) === '') {
			$clean = preg_replace('#<\s*(script|style|noscript|svg)\b[^>]*>.*?<\s*/\s*\1\s*>#is', ' ', $html) ?? $html;
		}

		$clean = preg_replace('/\s+/', ' ', (string) $clean);
		$clean = trim((string) $clean);

		// Mantém o prompt pequeno para reduzir latência e chance de timeout.
		$max = 60_000;
		if (mb_strlen($clean, 'UTF-8') > $max) {
			$clean = mb_substr($clean, 0, $max, 'UTF-8');
		}
		return $this->garantirUtf8($clean);
	}

	private function extrairJsonDoTexto(string $text): string
	{
		// Remove fences comuns
		$text = preg_replace('/^\s*```(?:json)?/i', '', $text) ?? $text;
		$text = preg_replace('/```\s*$/', '', $text) ?? $text;
		$text = trim($text);

		$firstObj = strpos($text, '{');
		$firstArr = strpos($text, '[');

		$start = null;
		if ($firstObj !== false && $firstArr !== false) {
			$start = min($firstObj, $firstArr);
		} elseif ($firstObj !== false) {
			$start = $firstObj;
		} elseif ($firstArr !== false) {
			$start = $firstArr;
		}

		if ($start === null) {
			return $text;
		}

		$trimmed = substr($text, $start);

		// tenta cortar no último fechamento plausível
		$lastObj = strrpos($trimmed, '}');
		$lastArr = strrpos($trimmed, ']');
		$end = max($lastObj === false ? -1 : $lastObj, $lastArr === false ? -1 : $lastArr);
		if ($end > 0) {
			$trimmed = substr($trimmed, 0, $end + 1);
		}

		return trim($trimmed);
	}

	/**
	 * Normaliza campos e tenta absolutizar URLs.
	 * @param array<string,mixed> $ev
	 * @return array{titulo:string, imagem:?string, data:?string, hora:?string, descricao:?string, valor:?string, faixa_etaria:?string, tags:array<int,string>, link:?string}
	 */
	private function normalizarEvento(array $ev, ?string $baseUrl = null): array
	{
		$titulo = $this->asString($ev['titulo'] ?? $ev['title'] ?? $ev['name'] ?? null);
		$descricao = $this->asString($ev['descricao'] ?? $ev['description'] ?? null);

		$imagem = $this->asString($ev['imagem'] ?? $ev['image'] ?? null);
		$link = $this->asString($ev['link'] ?? $ev['url'] ?? null);

		$data = $this->asString($ev['data'] ?? null);
		$hora = $this->asString($ev['hora'] ?? null);

		// Se veio um datetime único
		if ((!$data || !$hora) && isset($ev['inicio'])) {
			[$d, $h] = $this->splitDateTime($this->asString($ev['inicio']));
			$data = $data ?: $d;
			$hora = $hora ?: $h;
		}
		if ((!$data || !$hora) && isset($ev['startDate'])) {
			[$d, $h] = $this->splitDateTime($this->asString($ev['startDate']));
			$data = $data ?: $d;
			$hora = $hora ?: $h;
		}

		$tags = $this->normalizarTags($ev['tags'] ?? $ev['keywords'] ?? null);

		if ($baseUrl) {
			$imagem = $this->absolutizarUrl($imagem, $baseUrl);
			$link = $this->absolutizarUrl($link, $baseUrl);
		}

		return [
			'titulo' => $titulo,
			'imagem' => $imagem,
			'data' => $this->normalizarData($data),
			'hora' => $this->normalizarHora($hora),
			'descricao' => $descricao,
			'valor' => $this->asString($ev['valor'] ?? $ev['preco'] ?? $ev['price'] ?? null) ?: null,
			'faixa_etaria' => $this->asString(
				$ev['faixa_etaria']
					?? $ev['classificacao']
					?? $ev['typicalAgeRange']
					?? null
			) ?: null,
			'tags' => $tags,
			'link' => $link,
		];
	}

	private function absolutizarUrl(?string $maybeUrl, string $baseUrl): ?string
	{
		$maybeUrl = $this->asString($maybeUrl);
		if ($maybeUrl === '') {
			return null;
		}
		if (preg_match('#^https?://#i', $maybeUrl)) {
			return $maybeUrl;
		}
		if (str_starts_with($maybeUrl, '//')) {
			$scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
			return $scheme . ':' . $maybeUrl;
		}
		if (str_starts_with($maybeUrl, '/')) {
			$parts = parse_url($baseUrl);
			if (!$parts || empty($parts['host'])) {
				return $maybeUrl;
			}
			$scheme = $parts['scheme'] ?? 'https';
			$port = isset($parts['port']) ? ':' . $parts['port'] : '';
			return $scheme . '://' . $parts['host'] . $port . $maybeUrl;
		}

		// relativo simples
		$base = rtrim((string) preg_replace('#/[^/]*$#', '/', $baseUrl), '/');
		return $base . '/' . ltrim($maybeUrl, '/');
	}

	private function normalizarTags(mixed $tags): array
	{
		$out = [];
		if (is_string($tags)) {
			// "a, b, c"
			$parts = preg_split('/[,;\n]+/', $tags) ?: [];
			foreach ($parts as $p) {
				$p = trim($p);
				if ($p !== '') {
					$out[] = $p;
				}
			}
		} elseif (is_array($tags)) {
			foreach ($tags as $t) {
				if (is_string($t)) {
					$t = trim($t);
					if ($t !== '') {
						$out[] = $t;
					}
				}
			}
		}

		$out = array_values(array_unique($out));
		if (count($out) > 10) {
			$out = array_slice($out, 0, 10);
		}
		return $out;
	}

	private function splitDateTime(?string $isoOrText): array
	{
		$isoOrText = $this->asString($isoOrText);
		if ($isoOrText === '') {
			return [null, null];
		}
		// tenta parsear como DateTime
		try {
			$dt = new \DateTime($isoOrText);
			return [$dt->format('Y-m-d'), $dt->format('H:i')];
		} catch (\Throwable $e) {
			// fallback simples: procura YYYY-MM-DD e HH:MM
			$data = null;
			$hora = null;
			if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $isoOrText, $m)) {
				$data = $m[1];
			}
			if (preg_match('/\b(\d{2}:\d{2})\b/', $isoOrText, $m)) {
				$hora = $m[1];
			}
			return [$data, $hora];
		}
	}

	private function normalizarData(?string $data): ?string
	{
		$data = $this->asString($data);
		if ($data === '') {
			return null;
		}
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
			return $data;
		}
		// tenta converter formatos comuns
		try {
			$dt = new \DateTime($data);
			return $dt->format('Y-m-d');
		} catch (\Throwable $e) {
			return null;
		}
	}

	private function normalizarHora(?string $hora): ?string
	{
		$hora = $this->asString($hora);
		if ($hora === '') {
			return null;
		}
		if (preg_match('/^\d{2}:\d{2}$/', $hora)) {
			return $hora;
		}
		if (preg_match('/\b(\d{2}:\d{2})\b/', $hora, $m)) {
			return $m[1];
		}
		return null;
	}

	private function asString(mixed $v): string
	{
		if ($v === null) {
			return '';
		}
		if (is_string($v)) {
			return trim($v);
		}
		if (is_numeric($v)) {
			return (string) $v;
		}
		return '';
	}
}

