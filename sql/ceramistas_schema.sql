-- Schema do site 2º Encontro de Ceramistas (Arceburgo-MG)
-- Tabelas: expositores_*, programacao_*, atracoes_musicais, ceramistas_config

CREATE TABLE IF NOT EXISTS `expositores_grupos` (
  `id` VARCHAR(40) NOT NULL,
  `nome` VARCHAR(80) NOT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `expositores_grupos` (`id`, `nome`, `ordem`) VALUES
  ('artesao', 'Artesãos / cerâmica', 1),
  ('alimentacao', 'Alimentação e cerveja', 2);

CREATE TABLE IF NOT EXISTS `expositores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `nome` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `resumo` VARCHAR(400) DEFAULT NULL,
  `descricao` TEXT NOT NULL,
  `categoria` VARCHAR(80) DEFAULT NULL,
  `grupo` VARCHAR(40) NOT NULL DEFAULT 'artesao',
  `logo` VARCHAR(255) DEFAULT NULL,
  `foto_destaque` VARCHAR(255) DEFAULT NULL,
  `instagram` VARCHAR(120) DEFAULT NULL,
  `whatsapp` VARCHAR(40) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_expositores_txtid` (`txtid`),
  UNIQUE KEY `uk_expositores_slug` (`slug`),
  KEY `idx_expositores_ativo_ordem` (`ativo`, `ordem`),
  KEY `idx_expositores_grupo` (`grupo`, `ativo`, `ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `expositores_fotos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `expositor_id` INT UNSIGNED NOT NULL,
  `arquivo` VARCHAR(255) NOT NULL,
  `legenda` VARCHAR(255) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `destaque` TINYINT(1) NOT NULL DEFAULT 0,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expositores_fotos_expositor` (`expositor_id`, `ordem`),
  CONSTRAINT `fk_expositores_fotos_expositor`
    FOREIGN KEY (`expositor_id`) REFERENCES `expositores` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `programacao_categorias` (
  `id` VARCHAR(40) NOT NULL,
  `nome` VARCHAR(80) NOT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `programacao_categorias` (`id`, `nome`, `ordem`) VALUES
  ('abertura', 'Abertura', 1),
  ('oficina', 'Oficina', 2),
  ('feira', 'Feira', 3),
  ('musica', 'Música', 4),
  ('kids', 'Espaço Kids', 5),
  ('sabores', 'Sabores', 6);

CREATE TABLE IF NOT EXISTS `programacao_icones` (
  `id` VARCHAR(40) NOT NULL,
  `nome` VARCHAR(80) NOT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `programacao_icones` (`id`, `nome`, `ordem`) VALUES
  ('sun', 'Sol / abertura', 1),
  ('pottery', 'Cerâmica', 2),
  ('music', 'Música', 3),
  ('kids', 'Kids', 4),
  ('taste', 'Sabores', 5),
  ('beer', 'Cerveja', 6),
  ('market', 'Feira', 7);

CREATE TABLE IF NOT EXISTS `programacao` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `titulo` VARCHAR(180) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  `dia` DATE NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_fim` TIME DEFAULT NULL,
  `local` VARCHAR(180) DEFAULT NULL,
  `categoria` VARCHAR(60) DEFAULT NULL,
  `icone` VARCHAR(40) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `destaque` TINYINT(1) NOT NULL DEFAULT 0,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_programacao_txtid` (`txtid`),
  KEY `idx_programacao_dia_hora` (`ativo`, `dia`, `hora_inicio`, `ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `atracoes_musicais` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `nome` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `resumo` VARCHAR(400) DEFAULT NULL,
  `cartaz` VARCHAR(255) DEFAULT NULL,
  `cartaz_alt` VARCHAR(255) DEFAULT NULL,
  `dia` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `local` VARCHAR(180) DEFAULT NULL,
  `instagram` VARCHAR(120) DEFAULT NULL,
  `site` VARCHAR(255) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_atracoes_musicais_txtid` (`txtid`),
  UNIQUE KEY `uk_atracoes_musicais_slug` (`slug`),
  KEY `idx_atracoes_musicais_ativo_dia` (`ativo`, `dia`, `hora`, `ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `atracoes_musicais` (
  `txtid`, `nome`, `slug`, `resumo`, `cartaz`, `cartaz_alt`,
  `dia`, `hora`, `local`, `instagram`, `site`, `ordem`, `ativo`
) VALUES
(
  MD5('atracao-trinca-ferro'),
  'Trinca Ferro',
  'trinca-ferro',
  'Rock and Roll Vintage — clássicos que marcaram época.',
  'ceramistas/cartaz-trinca-ferro.jpg',
  'Cartaz: Trinca Ferro — Rock and Roll Vintage, 5 de setembro a partir das 15h, evento gratuito no Calçadão Pedro Furlan',
  '2026-09-05',
  '15:00:00',
  'Calçadão Pedro Furlan',
  'banda.trincaferro',
  'https://bandatrincaferro.com.br/',
  1,
  1
),
(
  MD5('atracao-joao-ferreira'),
  'João Ferreira',
  'joao-ferreira',
  'Música Popular Brasileira — boas canções, histórias e emoções.',
  'ceramistas/cartaz-joao-ferreira.jpg',
  'Cartaz: João Ferreira — Música Popular Brasileira, 6 de setembro a partir das 15h, evento gratuito no Calçadão Pedro Furlan',
  '2026-09-06',
  '15:00:00',
  'Calçadão Pedro Furlan',
  'ojoaoferreira',
  NULL,
  2,
  1
);

CREATE TABLE IF NOT EXISTS `ceramistas_config` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `data_inicio` DATE NOT NULL,
  `data_fim` DATE NOT NULL,
  `local` VARCHAR(180) NOT NULL,
  `local_complemento` VARCHAR(255) DEFAULT NULL,
  `cidade` VARCHAR(120) NOT NULL DEFAULT 'Arceburgo',
  `uf` CHAR(2) NOT NULL DEFAULT 'MG',
  `endereco` VARCHAR(400) DEFAULT NULL,
  `mapa_query` VARCHAR(255) DEFAULT NULL,
  `whatsapp` VARCHAR(40) NOT NULL,
  `mensagem_whatsapp` VARCHAR(400) DEFAULT NULL,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ceramistas_config_txtid` (`txtid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `ceramistas_config` (
  `id`, `txtid`, `data_inicio`, `data_fim`, `local`, `local_complemento`,
  `cidade`, `uf`, `endereco`, `mapa_query`, `whatsapp`, `mensagem_whatsapp`
) VALUES (
  1,
  'ceramistas-config',
  '2026-09-05',
  '2026-09-06',
  'Praça da Matriz',
  'Calçadão Pedro Furlan',
  'Arceburgo',
  'MG',
  'Entorno do Caramanchão da Praça da Matriz e Calçadão Pedro Furlan · Arceburgo - MG',
  '-21.3644363,-46.938418',
  '35997010196',
  'Olá! Quero saber mais sobre o 2º Encontro de Ceramistas em Arceburgo.'
);
