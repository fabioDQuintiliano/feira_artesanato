<?php
require_once __DIR__ . '/front_includes.php';

try {
    echo "Verificando colunas na tabela `eventos`...\n";

    // Tenta adicionar a coluna `valor`
    try {
        DAO::doQuery("ALTER TABLE eventos ADD COLUMN valor VARCHAR(255) DEFAULT NULL;");
        echo "Coluna `valor` adicionada com sucesso.\n";
    } catch (Exception $e) {
        echo "Coluna `valor` já existe ou erro: " . $e->getMessage() . "\n";
    }

    // Tenta adicionar a coluna `faixa_etaria`
    try {
        DAO::doQuery("ALTER TABLE eventos ADD COLUMN faixa_etaria VARCHAR(100) DEFAULT NULL;");
        echo "Coluna `faixa_etaria` adicionada com sucesso.\n";
    } catch (Exception $e) {
        echo "Coluna `faixa_etaria` já existe ou erro: " . $e->getMessage() . "\n";
    }

    echo "Migração concluída.\n";
} catch (Exception $e) {
    echo "Erro fatal na migração: " . $e->getMessage() . "\n";
}
