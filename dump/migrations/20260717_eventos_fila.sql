CREATE TABLE IF NOT EXISTS `eventos_fila` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int NOT NULL,
  `status` enum('pendente','processando','concluido','erro') NOT NULL DEFAULT 'pendente',
  `link_ativo` int GENERATED ALWAYS AS (
    CASE WHEN `status` IN ('pendente','processando') THEN `link_id` ELSE NULL END
  ) STORED,
  `tentativas` tinyint unsigned NOT NULL DEFAULT 0,
  `disponivel_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `travado_em` datetime DEFAULT NULL,
  `iniciado_em` datetime DEFAULT NULL,
  `finalizado_em` datetime DEFAULT NULL,
  `ultimo_erro` text,
  `eventos_inseridos` int unsigned NOT NULL DEFAULT 0,
  `eventos_atualizados` int unsigned NOT NULL DEFAULT 0,
  `tags_inseridas` int unsigned NOT NULL DEFAULT 0,
  `ligacoes_inseridas` int unsigned NOT NULL DEFAULT 0,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_eventos_fila_link_ativo` (`link_ativo`),
  KEY `idx_eventos_fila_proximo` (`status`,`disponivel_em`,`id`),
  KEY `idx_eventos_fila_link_status` (`link_id`,`status`),
  CONSTRAINT `fk_eventos_fila_link`
    FOREIGN KEY (`link_id`) REFERENCES `links_config` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
