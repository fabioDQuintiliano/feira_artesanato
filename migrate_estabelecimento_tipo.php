<?php
require_once __DIR__ . '/front_includes.php';

try {
    echo "Migrando vínculo evento ↔ estabelecimento e campo tipo...\n";

    try {
        DAO::doQuery(
            "ALTER TABLE estabelecimentos
             ADD COLUMN tipo INT NOT NULL DEFAULT 4
             COMMENT '0=Bares e Restaurantes,1=Cinema,2=Teatro,3=Parques,4=Outros'
             AFTER estado"
        );
        echo "Coluna estabelecimentos.tipo adicionada.\n";
    } catch (Exception $e) {
        echo "estabelecimentos.tipo já existe ou erro: " . $e->getMessage() . "\n";
    }

    try {
        DAO::doQuery(
            "ALTER TABLE eventos
             ADD COLUMN estabelecimento INT NULL DEFAULT NULL
             COMMENT 'FK estabelecimentos.id'
             AFTER local"
        );
        echo "Coluna eventos.estabelecimento adicionada.\n";
    } catch (Exception $e) {
        echo "eventos.estabelecimento já existe ou erro: " . $e->getMessage() . "\n";
    }

    try {
        DAO::doQuery("ALTER TABLE eventos ADD KEY idx_eventos_estabelecimento (estabelecimento)");
        echo "Índice idx_eventos_estabelecimento adicionado.\n";
    } catch (Exception $e) {
        echo "Índice já existe ou erro: " . $e->getMessage() . "\n";
    }

    try {
        DAO::doQuery(
            "UPDATE eventos e
             INNER JOIN links_config lc
               ON e.link IS NOT NULL
              AND e.link <> ''
              AND (
                e.link LIKE CONCAT(TRIM(TRAILING '/' FROM SUBSTRING_INDEX(lc.link, '#', 1)), '%')
                OR SUBSTRING_INDEX(SUBSTRING_INDEX(e.link, '/', 3), '//', -1)
                   = SUBSTRING_INDEX(SUBSTRING_INDEX(lc.link, '/', 3), '//', -1)
              )
             SET e.estabelecimento = lc.estabelecimento
             WHERE e.estabelecimento IS NULL"
        );
        echo "Backfill de eventos.estabelecimento concluído.\n";
    } catch (Exception $e) {
        echo "Erro no backfill: " . $e->getMessage() . "\n";
    }

    echo "Migração concluída.\n";
} catch (Exception $e) {
    echo "Erro fatal na migração: " . $e->getMessage() . "\n";
}
