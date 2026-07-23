-- Tipo do estabelecimento (0 = Bares e Restaurantes, 1 = Cinema, 2 = Teatro, 3 = Parques, 4 = Outros)
-- Execute via: php migrate_estabelecimento_tipo.php
-- (idempotente apenas se as colunas ainda não existirem)

ALTER TABLE `estabelecimentos`
  ADD COLUMN `tipo` INT NOT NULL DEFAULT 4
    COMMENT '0=Bares e Restaurantes,1=Cinema,2=Teatro,3=Parques,4=Outros'
    AFTER `estado`;

ALTER TABLE `eventos`
  ADD COLUMN `estabelecimento` INT NULL DEFAULT NULL
    COMMENT 'FK estabelecimentos.id'
    AFTER `local`;

ALTER TABLE `eventos`
  ADD KEY `idx_eventos_estabelecimento` (`estabelecimento`);

UPDATE eventos e
INNER JOIN links_config lc
  ON e.link IS NOT NULL
 AND e.link <> ''
 AND (
   e.link COLLATE utf8mb4_unicode_ci LIKE CONCAT(TRIM(TRAILING '/' FROM SUBSTRING_INDEX(lc.link, '#', 1)), '%')
   OR SUBSTRING_INDEX(SUBSTRING_INDEX(e.link, '/', 3), '//', -1) COLLATE utf8mb4_unicode_ci
      = SUBSTRING_INDEX(SUBSTRING_INDEX(lc.link, '/', 3), '//', -1) COLLATE utf8mb4_unicode_ci
 )
SET e.estabelecimento = lc.estabelecimento
WHERE e.estabelecimento IS NULL;
