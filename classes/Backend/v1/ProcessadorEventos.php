<?php
declare(strict_types=1);

namespace Backend\v1;

final class ProcessadorEventos
{
	/** @var array<string, int> */
	private array $tagsCache = [];

	public function __construct(private \PDO $db)
	{
	}

	/**
	 * @param array<int, array<string, mixed>> $eventos
	 * @return array{eventos_inseridos:int,eventos_atualizados:int,tags_inseridas:int,ligacoes_inseridas:int,eventos_ignorados:int}
	 */
	public function processar(array $eventos, string $urlOrigem, int $usuarioId, ?int $estabelecimentoId = null): array
	{
		$contadores = [
			'eventos_inseridos' => 0,
			'eventos_atualizados' => 0,
			'tags_inseridas' => 0,
			'ligacoes_inseridas' => 0,
			'eventos_ignorados' => 0,
		];

		$usuarioId = $usuarioId > 0 ? $usuarioId : 1;
		$estabelecimentoId = $estabelecimentoId !== null && $estabelecimentoId > 0
			? $estabelecimentoId
			: null;
		$this->db->beginTransaction();

		try {
			$eventoStmt = $this->prepararUpsertEvento();
			$tagStmt = $this->prepararUpsertTag();
			$ligacaoStmt = $this->db->prepare(
				'INSERT IGNORE INTO evento_tags (evento, tag) VALUES (:evento, :tag)'
			);

			foreach ($eventos as $evento) {
				$titulo = trim((string) ($evento['titulo'] ?? ''));
				$dataEvento = $this->parseDateTime($evento['data'] ?? null, $evento['hora'] ?? null);
				if ($titulo === '' || $dataEvento === null) {
					$contadores['eventos_ignorados']++;
					continue;
				}

				$txtid = $this->criarTxtid($urlOrigem, $titulo, $dataEvento);
				$eventoStmt->execute([
					'txtid' => $txtid,
					'nome' => mb_substr($titulo, 0, 255, 'UTF-8'),
					'descricao' => $this->textoOuNull($evento['descricao'] ?? null),
					'imagem' => $this->textoOuNull($evento['imagem'] ?? null, 500),
					'data_evento' => $dataEvento,
					'link' => $this->textoOuNull($evento['link'] ?? null, 500),
					'valor' => $this->normalizarValor($evento['valor'] ?? null),
					'faixa_etaria' => $this->textoOuNull($evento['faixa_etaria'] ?? null, 100),
					'estabelecimento' => $estabelecimentoId,
					'created_by' => $usuarioId,
					'last_edit_by' => $usuarioId,
				]);

				$eventoId = (int) $this->db->lastInsertId();
				if ($eventoStmt->rowCount() === 1) {
					$contadores['eventos_inseridos']++;
				} else {
					$contadores['eventos_atualizados']++;
				}

				$tags = is_array($evento['tags'] ?? null) ? $evento['tags'] : [];
				foreach ($tags as $tagBruta) {
					$tag = $this->normalizarTag($tagBruta);
					if ($tag === null) {
						continue;
					}

					if (isset($this->tagsCache[$tag])) {
						$tagId = $this->tagsCache[$tag];
					} else {
						$tagStmt->execute(['nome' => $tag]);
						$tagId = (int) $this->db->lastInsertId();
						$this->tagsCache[$tag] = $tagId;
						if ($tagStmt->rowCount() === 1) {
							$contadores['tags_inseridas']++;
						}
					}

					$ligacaoStmt->execute(['evento' => $eventoId, 'tag' => $tagId]);
					$contadores['ligacoes_inseridas'] += $ligacaoStmt->rowCount();
				}
			}

			$this->db->commit();
			return $contadores;
		} catch (\Throwable $e) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			throw $e;
		}
	}

	private function prepararUpsertEvento(): \PDOStatement
	{
		return $this->db->prepare(
			'INSERT INTO eventos (
				txtid, nome, descricao, imagem, data_evento, link, valor,
				faixa_etaria, estabelecimento, created_by, last_edit_by
			) VALUES (
				:txtid, :nome, :descricao, :imagem, :data_evento, :link, :valor,
				:faixa_etaria, :estabelecimento, :created_by, :last_edit_by
			)
			ON DUPLICATE KEY UPDATE
				id = LAST_INSERT_ID(id),
				nome = VALUES(nome),
				descricao = VALUES(descricao),
				imagem = VALUES(imagem),
				data_evento = VALUES(data_evento),
				link = VALUES(link),
				valor = VALUES(valor),
				faixa_etaria = VALUES(faixa_etaria),
				estabelecimento = COALESCE(VALUES(estabelecimento), estabelecimento),
				last_edit_by = VALUES(last_edit_by)'
		);
	}

	private function prepararUpsertTag(): \PDOStatement
	{
		return $this->db->prepare(
			'INSERT INTO tags (nome) VALUES (:nome)
			 ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)'
		);
	}

	private function criarTxtid(string $url, string $titulo, string $dataEvento): string
	{
		$slug = $this->slug($titulo) ?: 'evento';
		$hash = substr(sha1($url . '|' . $titulo . '|' . $dataEvento), 0, 8);
		$sufixo = date('YmdHi', strtotime($dataEvento)) . '-' . $hash;
		$maxSlug = max(1, 100 - strlen($sufixo) - 1);

		return mb_substr($slug, 0, $maxSlug, 'UTF-8') . '-' . $sufixo;
	}

	private function slug(string $texto): string
	{
		$texto = preg_replace('/[^\p{L}\p{N}\s-]+/u', ' ', trim($texto)) ?? $texto;
		$texto = mb_strtolower($texto, 'UTF-8');
		$texto = preg_replace('/\s+/u', '-', $texto) ?? $texto;
		$texto = preg_replace('/-+/u', '-', $texto) ?? $texto;
		return trim($texto, '-');
	}

	private function parseDateTime(mixed $data, mixed $hora): ?string
	{
		$data = trim(is_scalar($data) ? (string) $data : '');
		$hora = trim(is_scalar($hora) ? (string) $hora : '');
		if ($data === '') {
			return null;
		}

		if (preg_match('/^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}(:\d{2})?$/', $data)) {
			try {
				return (new \DateTime($data))->format('Y-m-d H:i:s');
			} catch (\Throwable) {
				return null;
			}
		}

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
			try {
				$data = (new \DateTime($data))->format('Y-m-d');
			} catch (\Throwable) {
				return null;
			}
		}

		if (!preg_match('/^\d{2}:\d{2}$/', $hora)) {
			$hora = preg_match('/\b(\d{2}:\d{2})\b/', $hora, $match) ? $match[1] : '00:00';
		}

		try {
			return (new \DateTime("{$data} {$hora}:00"))->format('Y-m-d H:i:s');
		} catch (\Throwable) {
			return null;
		}
	}

	private function textoOuNull(mixed $valor, ?int $limite = null): ?string
	{
		if (!is_scalar($valor)) {
			return null;
		}

		$texto = trim((string) $valor);
		if ($texto === '') {
			return null;
		}

		return $limite === null ? $texto : mb_substr($texto, 0, $limite, 'UTF-8');
	}

	private function normalizarValor(mixed $valor): ?string
	{
		$texto = $this->textoOuNull($valor);
		if ($texto === null) {
			return null;
		}

		$texto = html_entity_decode(strip_tags($texto), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$texto = preg_replace('/\s+/u', ' ', trim($texto)) ?? $texto;
		if (preg_match('/^(gratuito|grátis|gratis|free)$/iu', $texto)) {
			return 'Gratuito';
		}

		return mb_substr($texto, 0, 255, 'UTF-8');
	}

	private function normalizarTag(mixed $tag): ?string
	{
		if (!is_scalar($tag)) {
			return null;
		}

		$tag = mb_strtolower(ltrim(trim((string) $tag), '#'), 'UTF-8');
		$tag = trim(preg_replace('/\s+/u', ' ', $tag) ?? $tag);
		return $tag === '' ? null : mb_substr($tag, 0, 50, 'UTF-8');
	}
}
