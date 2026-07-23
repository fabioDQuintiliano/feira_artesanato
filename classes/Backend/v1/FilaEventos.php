<?php
declare(strict_types=1);

namespace Backend\v1;

final class FilaEventos
{
	private const MAX_TENTATIVAS = 3;
	private const TRAVA_EXPIRA_MINUTOS = 10;

	public function __construct(private \PDO $db)
	{
	}

	public function enfileirarLinksDevidos(): int
	{
		$sql = <<<'SQL'
			INSERT IGNORE INTO eventos_fila (link_id, status, disponivel_em)
			SELECT links.id, 'pendente', NOW()
			FROM links_config AS links
			WHERE (links.ultima_atualizacao IS NULL OR links.ultima_atualizacao <= DATE_SUB(NOW(), INTERVAL 1 DAY))
			  AND NOT EXISTS (
				SELECT 1
				FROM eventos_fila AS fila
				WHERE fila.link_id = links.id
				  AND fila.status IN ('pendente', 'processando')
			  )
			SQL;

		return $this->db->exec($sql);
	}

	/**
	 * @return array<string, mixed>|null
	 */
	public function reservarProximo(): ?array
	{
		$this->recuperarTravasExpiradas();
		$this->db->beginTransaction();

		try {
			$sql = <<<'SQL'
				SELECT fila.*, links.link, links.created_by, links.estabelecimento
				FROM eventos_fila AS fila
				INNER JOIN links_config AS links ON links.id = fila.link_id
				WHERE fila.status = 'pendente'
				  AND fila.disponivel_em <= NOW()
				  AND fila.tentativas < :max_tentativas
				ORDER BY fila.disponivel_em ASC, fila.id ASC
				LIMIT 1
				FOR UPDATE
				SQL;
			$stmt = $this->db->prepare($sql);
			$stmt->execute(['max_tentativas' => self::MAX_TENTATIVAS]);
			$job = $stmt->fetch(\PDO::FETCH_ASSOC);

			if (!$job) {
				$this->db->commit();
				return null;
			}

			$update = $this->db->prepare(
				"UPDATE eventos_fila
				 SET status = 'processando',
				     tentativas = tentativas + 1,
				     travado_em = NOW(),
				     iniciado_em = NOW(),
				     finalizado_em = NULL,
				     ultimo_erro = NULL
				 WHERE id = :id AND status = 'pendente'"
			);
			$update->execute(['id' => $job['id']]);

			if ($update->rowCount() !== 1) {
				$this->db->rollBack();
				return null;
			}

			$job['tentativas'] = (int) $job['tentativas'] + 1;
			$this->db->commit();
			return $job;
		} catch (\Throwable $e) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @param array<string, int> $contadores
	 */
	public function concluir(int $jobId, int $linkId, array $contadores): void
	{
		$this->db->beginTransaction();

		try {
			$stmt = $this->db->prepare(
				"UPDATE eventos_fila
				 SET status = 'concluido',
				     travado_em = NULL,
				     finalizado_em = NOW(),
				     eventos_inseridos = :eventos_inseridos,
				     eventos_atualizados = :eventos_atualizados,
				     tags_inseridas = :tags_inseridas,
				     ligacoes_inseridas = :ligacoes_inseridas
				 WHERE id = :id AND status = 'processando'"
			);
			$stmt->execute([
				'id' => $jobId,
				'eventos_inseridos' => $contadores['eventos_inseridos'] ?? 0,
				'eventos_atualizados' => $contadores['eventos_atualizados'] ?? 0,
				'tags_inseridas' => $contadores['tags_inseridas'] ?? 0,
				'ligacoes_inseridas' => $contadores['ligacoes_inseridas'] ?? 0,
			]);

			$link = $this->db->prepare(
				'UPDATE links_config SET ultima_atualizacao = NOW() WHERE id = :id'
			);
			$link->execute(['id' => $linkId]);
			$this->db->commit();
		} catch (\Throwable $e) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @return array{status:string,proxima_tentativa:?string}
	 */
	public function falhar(int $jobId, int $tentativas, string $erro): array
	{
		$erro = mb_substr($erro, 0, 65000, 'UTF-8');

		if ($tentativas < self::MAX_TENTATIVAS) {
			$esperaMinutos = 5 * (2 ** max(0, $tentativas - 1));
			$stmt = $this->db->prepare(
				"UPDATE eventos_fila
				 SET status = 'pendente',
				     disponivel_em = DATE_ADD(NOW(), INTERVAL :espera MINUTE),
				     travado_em = NULL,
				     finalizado_em = NOW(),
				     ultimo_erro = :erro
				 WHERE id = :id AND status = 'processando'"
			);
			$stmt->bindValue(':espera', $esperaMinutos, \PDO::PARAM_INT);
			$stmt->bindValue(':erro', $erro);
			$stmt->bindValue(':id', $jobId, \PDO::PARAM_INT);
			$stmt->execute();

			return [
				'status' => 'pendente',
				'proxima_tentativa' => date('Y-m-d H:i:s', time() + ($esperaMinutos * 60)),
			];
		}

		$stmt = $this->db->prepare(
			"UPDATE eventos_fila
			 SET status = 'erro',
			     travado_em = NULL,
			     finalizado_em = NOW(),
			     ultimo_erro = :erro
			 WHERE id = :id AND status = 'processando'"
		);
		$stmt->execute(['erro' => $erro, 'id' => $jobId]);

		return ['status' => 'erro', 'proxima_tentativa' => null];
	}

	private function recuperarTravasExpiradas(): void
	{
		$intervalo = self::TRAVA_EXPIRA_MINUTOS;
		$this->db->exec(
			"UPDATE eventos_fila
			 SET status = IF(tentativas < " . self::MAX_TENTATIVAS . ", 'pendente', 'erro'),
			     disponivel_em = NOW(),
			     travado_em = NULL,
			     finalizado_em = IF(tentativas < " . self::MAX_TENTATIVAS . ", NULL, NOW()),
			     ultimo_erro = CONCAT_WS('\n', ultimo_erro, 'Processamento anterior excedeu {$intervalo} minutos.')
			 WHERE status = 'processando'
			   AND travado_em < DATE_SUB(NOW(), INTERVAL {$intervalo} MINUTE)"
		);
	}
}
